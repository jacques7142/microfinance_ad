<?php

namespace App\Http\Controllers;

use App\Models\CompteEpargne;
use App\Models\Echeance;
use App\Models\PaiementMobile;
use App\Services\LigdiCashService;
use App\Services\PaiementMobileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SocietaireOperationController extends Controller
{
    public function __construct(
        private readonly LigdiCashService $ligdiCash,
        private readonly PaiementMobileService $paiementService,
    ) {}

    public function depotForm(): View
    {
        $societaire = Auth::guard('societaire')->user()->load('comptesEpargne');

        return view('societaires.depot', ['societaire' => $societaire]);
    }

    public function depot(Request $request): RedirectResponse
    {
        $data = $this->validerOperation($request, 'depot');

        $societaire = Auth::guard('societaire')->user();
        $compte = CompteEpargne::findOrFail($data['compte_epargne_id']);

        if ($compte->societaire_id !== $societaire->id) {
            return back()->withErrors(['compte_epargne_id' => 'Ce compte ne vous appartient pas.']);
        }

        $paiement = $this->creerPaiement($societaire, PaiementMobile::TYPE_DEPOT, PaiementMobile::SENS_PAYIN, $data, $compte);

        return $this->initier($request, $paiement);
    }

    public function retraitForm(): View
    {
        $societaire = Auth::guard('societaire')->user()->load('comptesEpargne');

        return view('societaires.retrait', ['societaire' => $societaire]);
    }

    public function retrait(Request $request): RedirectResponse
    {
        $data = $this->validerOperation($request, 'retrait');

        $societaire = Auth::guard('societaire')->user();
        $compte = CompteEpargne::findOrFail($data['compte_epargne_id']);

        if ($compte->societaire_id !== $societaire->id) {
            return back()->withErrors(['compte_epargne_id' => 'Ce compte ne vous appartient pas.']);
        }

        if ($data['montant'] > (float) $compte->solde) {
            return back()->withErrors(['montant' => 'Solde insuffisant pour ce retrait.']);
        }

        if ($compte->plafond_retrait_journalier && $data['montant'] > (float) $compte->plafond_retrait_journalier) {
            return back()->withErrors(['montant' => 'Montant supérieur au plafond de retrait journalier.']);
        }

        $paiement = $this->creerPaiement($societaire, PaiementMobile::TYPE_RETRAIT, PaiementMobile::SENS_PAYOUT, $data, $compte);

        return $this->initier($request, $paiement);
    }

    public function remboursementForm(): View
    {
        $societaire = Auth::guard('societaire')->user()
            ->load(['credits' => fn ($q) => $q->whereIn('statut', ['validee', 'soldee'])->with('echeances')]);

        return view('societaires.remboursement', ['societaire' => $societaire]);
    }

    public function rembourser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'echeance_id' => ['required', 'exists:echeances,id'],
            'montant' => ['required', 'numeric', 'min:1'],
            'operateur' => ['required', 'in:yas,flooz'],
            'telephone' => ['required', 'numeric', 'digits_between:8,11'],
        ]);

        $societaire = Auth::guard('societaire')->user();
        $echeance = Echeance::with('credit')->findOrFail($data['echeance_id']);

        if ($echeance->credit->societaire_id !== $societaire->id) {
            return back()->withErrors(['echeance_id' => 'Cette échéance ne vous appartient pas.']);
        }

        if ($echeance->statut === Echeance::STATUT_PAYEE) {
            return back()->withErrors(['echeance_id' => 'Cette échéance est déjà payée.']);
        }

        $resteADu = (float) $echeance->montant_du - (float) $echeance->montant_paye;
        if ($data['montant'] > $resteADu) {
            return back()->withErrors(['montant' => "Le montant restant dû est de {$resteADu} F CFA."]);
        }

        $paiement = PaiementMobile::create([
            'societaire_id' => $societaire->id,
            'reference_interne' => $this->genererReference(),
            'type' => PaiementMobile::TYPE_REMBOURSEMENT,
            'sens' => PaiementMobile::SENS_PAYIN,
            'operateur' => $data['operateur'],
            'telephone' => $data['telephone'],
            'montant' => $data['montant'],
            'statut' => PaiementMobile::STATUT_PENDING,
            'credit_id' => $echeance->credit_id,
            'echeance_id' => $echeance->id,
            'date_initiation' => now(),
        ]);

        return $this->initier($request, $paiement);
    }

    /**
     * Validation commune des champs de dépôt et de retrait.
     */
    protected function validerOperation(Request $request, string $type): array
    {
        return $request->validate([
            'compte_epargne_id' => ['required', 'exists:comptes_epargne,id'],
            'montant' => ['required', 'numeric', 'min:100'],
            'operateur' => ['required', 'in:yas,flooz'],
            'telephone' => ['required', 'numeric', 'digits_between:8,11'],
        ]);
    }

    /**
     * Crée la demande de paiement mobile (hors appel API) pour un dépôt ou un retrait.
     */
    protected function creerPaiement($societaire, string $type, string $sens, array $data, CompteEpargne $compte): PaiementMobile
    {
        return PaiementMobile::create([
            'societaire_id' => $societaire->id,
            'reference_interne' => $this->genererReference(),
            'type' => $type,
            'sens' => $sens,
            'operateur' => $data['operateur'],
            'telephone' => $data['telephone'],
            'montant' => $data['montant'],
            'statut' => PaiementMobile::STATUT_PENDING,
            'compte_epargne_id' => $compte->id,
            'date_initiation' => now(),
        ]);
    }

    /**
     * Initie l'appel LigdiCash et redirige vers la page d'attente.
     */
    protected function initier(Request $request, PaiementMobile $paiement): RedirectResponse
    {
        $callbackUrl = config('services.ligdicash.callback_url');

        // Mode démo : le paiement est simulé et confirmé immédiatement afin de
        // pouvoir présenter le parcours complet (choix Yas/Flooz → validation)
        // au jury, sans identifiants LigdiCash réels.
        if ($this->ligdiCash->estEnModeDemo()) {
            try {
                $paiement->token = 'demo-'.substr(md5($paiement->reference_interne), 0, 12);
                $paiement->save();

                $this->paiementService->simulerConfirmation($paiement);
            } catch (\Throwable $e) {
                $paiement->finaliser(PaiementMobile::STATUT_FAILED);

                return back()->withErrors(['paiement' => $e->getMessage()]);
            }

            return redirect()->route('societaire.paiement.statut', $paiement)
                ->with('success', 'Paiement simulé (mode démo) — votre opération a été enregistrée avec succès.');
        }

        try {
            if ($paiement->sens === PaiementMobile::SENS_PAYIN) {
                $resultat = $this->ligdiCash->createPayin([
                    'montant' => $paiement->montant,
                    'description' => "COOPEC-AD — {$paiement->typeLibelle()} #{$paiement->reference_interne}",
                    'telephone' => $paiement->telephone,
                    'nom' => $paiement->societaire->nom,
                    'prenom' => $paiement->societaire->prenom,
                    'email' => $paiement->societaire->email ?? '',
                ], $paiement->reference_interne, $callbackUrl);
            } else {
                $resultat = $this->ligdiCash->createPayout([
                    'montant' => $paiement->montant,
                    'description' => "COOPEC-AD — Retrait #{$paiement->reference_interne}",
                    'telephone' => $paiement->telephone,
                ], $paiement->reference_interne, $callbackUrl);
            }
        } catch (\Throwable $e) {
            $paiement->finaliser(PaiementMobile::STATUT_FAILED);

            return back()->withErrors(['paiement' => $e->getMessage()]);
        }

        // response_code "00" = requête acceptée ; on stocke le token pour le callback.
        if (($resultat['response_code'] ?? '') !== '00' || empty($resultat['token'])) {
            $paiement->finaliser(PaiementMobile::STATUT_FAILED, null, $resultat);

            return back()->withErrors(['paiement' => 'Le paiement n\'a pas pu être initié. '.($resultat['response_text'] ?? '').' (code '.($resultat['response_code'] ?? 'inconnu').')']);
        }

        $paiement->token = $resultat['token'];
        $paiement->save();

        return redirect()->route('societaire.paiement.statut', $paiement)
            ->with('success', 'Paiement initié. Validez l\'opération sur votre téléphone.');
    }

    /**
     * Génère une référence interne unique pour la demande de paiement.
     */
    protected function genererReference(): string
    {
        return 'MP-'.strtoupper(substr(md5(uniqid((string) random_int(1, 999999), true)), 0, 14));
    }

    /**
     * Page d'attente de validation d'un paiement mobile (interroge le statut en AJAX).
     */
    public function statutPaiement(PaiementMobile $paiement): View
    {
        $societaire = Auth::guard('societaire')->user();

        if ($paiement->societaire_id !== $societaire->id) {
            abort(403);
        }

        return view('societaires.paiement_statut', ['paiement' => $paiement, 'societaire' => $societaire]);
    }

    /**
     * Historique des demandes de paiement mobile du sociétaire.
     */
    public function paiements(): View
    {
        $societaire = Auth::guard('societaire')->user();

        $paiements = PaiementMobile::where('societaire_id', $societaire->id)
            ->with(['compteEpargne', 'credit', 'echeance'])
            ->orderByDesc('date_initiation')
            ->paginate(15);

        return view('societaires.paiements', ['societaire' => $societaire, 'paiements' => $paiements]);
    }
}
