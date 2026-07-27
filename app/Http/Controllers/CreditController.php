<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Echeance;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\Societaire;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class CreditController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $credits = Credit::with(['societaire', 'agentCredit', 'gerant'])
            ->when($user->role === User::ROLE_AGENT_CREDIT, fn ($q) => $q->whereHas('societaire', fn ($sq) => $sq->where('agence_id', $user->agence_id)))
            ->when($user->role === User::ROLE_GERANT, fn ($q) => $q->whereHas('societaire', fn ($sq) => $sq->where('agence_id', $user->agence_id)))
            ->orderByDesc('date_demande')
            ->paginate(20);

        return view('credits.index', ['credits' => $credits]);
    }

    public function create(): View
    {
        return view('credits.create', [
            'societaires' => Societaire::orderBy('nom')->get(['id', 'nom', 'prenom', 'numero_societaire']),
            'sousTypesOrdinaire' => Credit::SOUS_TYPES_ORDINAIRE,
        ]);
    }

    /** Enregistrement d'une nouvelle demande de crédit (saisie par un agent pour le compte d'un sociétaire). */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'societaire_id' => ['required', 'exists:societaires,id'],
            'type' => ['required', 'in:ordinaire,partenariat,tontine'],
            'sous_type' => ['nullable', 'required_if:type,ordinaire', 'in:'.implode(',', Credit::SOUS_TYPES_ORDINAIRE)],
            'partenaire' => ['nullable', 'required_if:type,partenariat', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:1000'],
            'duree_mois' => ['required', 'integer', 'min:1', 'max:60'],
            'taux_interet' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        // Crédit tontine : vérifie l'adossement et le plafond de garantie avant tout enregistrement.
        if ($data['type'] === Credit::TYPE_TONTINE) {
            $societaire = Societaire::with('compteTontine')->findOrFail($data['societaire_id']);
            $compteTontine = $societaire->compteTontine;

            if (! $compteTontine) {
                throw ValidationException::withMessages(['societaire_id' => "Ce sociétaire n'a pas de compte tontine LOGOKU actif."]);
            }

            $plafond = $compteTontine->plafondCreditAdosse();
            if ($data['montant'] > $plafond) {
                throw ValidationException::withMessages(['montant' => "Montant supérieur au plafond de garantie tontine ({$plafond} F)."]);
            }

            $data['compte_tontine_id'] = $compteTontine->id;
            $data['proportion_garantie'] = round(($data['montant'] / (float) $compteTontine->solde_accumule) * 100, 2);
        }

        $data['agent_credit_id'] = $request->user()->role === User::ROLE_AGENT_CREDIT ? $request->user()->id : null;
        $data['date_demande'] = now();
        $data['statut'] = Credit::STATUT_RECUE;

        $credit = Credit::create($data);

        JournalActivite::enregistrer('creation_credit', "Nouvelle demande de crédit #{$credit->id}", $credit);

        return redirect()->route('credits.show', $credit)->with('success', 'Demande de crédit enregistrée.');
    }

    public function show(Credit $credit): View
    {
        $credit->load(['societaire', 'agentCredit', 'gerant', 'echeances', 'frais']);

        return view('credits.show', ['credit' => $credit]);
    }

    /** L'agent de crédit instruit le dossier (avis) et le transmet au gérant. */
    public function instruire(Request $request, Credit $credit): RedirectResponse
    {
        $this->authorizeRole($request, [User::ROLE_AGENT_CREDIT]);

        $data = $request->validate(['avis_agent' => ['required', 'string', 'max:2000']]);

        $credit->update([
            'avis_agent' => $data['avis_agent'],
            'agent_credit_id' => $request->user()->id,
            'statut' => Credit::STATUT_TRANSMISE_GERANT,
        ]);

        JournalActivite::enregistrer('instruction_credit', "Dossier crédit #{$credit->id} instruit et transmis au gérant", $credit);

        return back()->with('success', 'Dossier transmis au gérant.');
    }

    /** Le gérant valide — génère l'échéancier et notifie le sociétaire. */
    public function valider(Request $request, Credit $credit): RedirectResponse
    {
        $this->authorizeRole($request, [User::ROLE_GERANT]);
        $gerant = $request->user();

        if ($credit->montant > (float) $gerant->seuil_validation && (float) $gerant->seuil_validation > 0) {
            // Le seuil du gérant ne couvre pas ce montant : à faire remonter à la Direction Générale.
            return back()->withErrors(['montant' => 'Ce montant dépasse votre seuil de validation ('.$gerant->seuil_validation.' F) — à transmettre à la Direction Générale.']);
        }

        if ($credit->depasseLePlafondUsure()) {
            return back()->withErrors(['taux_interet' => 'Le TAEG calculé ('.$credit->taegApproximatif().'%) dépasse le plafond légal en vigueur ('.config('coopec.taux_usure_plafond').'%).']);
        }

        $credit->update(['gerant_id' => $gerant->id, 'statut' => Credit::STATUT_VALIDEE]);

        $this->genererEcheancier($credit);

        Notification::create([
            'societaire_id' => $credit->societaire_id,
            'type' => 'sms',
            'contenu' => "Votre crédit de {$credit->montant} F a été validé. Échéancier disponible sur votre espace.",
            'date_envoi' => now(),
            'statut_envoi' => 'envoyee',
        ]);

        JournalActivite::enregistrer('validation_credit', "Crédit #{$credit->id} validé par {$gerant->nomComplet()}", $credit);

        return back()->with('success', 'Crédit validé, échéancier généré.');
    }

    public function rejeter(Request $request, Credit $credit): RedirectResponse
    {
        $this->authorizeRole($request, [User::ROLE_GERANT]);

        $data = $request->validate(['motif_rejet' => ['required', 'string', 'max:1000']]);

        $credit->update(['gerant_id' => $request->user()->id, 'statut' => Credit::STATUT_REJETEE]);

        Notification::create([
            'societaire_id' => $credit->societaire_id,
            'type' => 'sms',
            'contenu' => 'Votre demande de crédit a été rejetée. Motif : '.$data['motif_rejet'],
            'date_envoi' => now(),
            'statut_envoi' => 'envoyee',
        ]);

        JournalActivite::enregistrer('rejet_credit', "Crédit #{$credit->id} rejeté", $credit);

        return back()->with('success', 'Crédit rejeté, sociétaire notifié.');
    }

    /** Échéancier linéaire simple (capital + intérêts répartis à parts égales sur la durée). */
    private function genererEcheancier(Credit $credit): void
    {
        $mensualite = round(
            ((float) $credit->montant * (1 + (float) $credit->taux_interet / 100)) / $credit->duree_mois,
            2
        );

        for ($i = 1; $i <= $credit->duree_mois; $i++) {
            Echeance::create([
                'credit_id' => $credit->id,
                'date_echeance' => now()->addMonths($i)->endOfMonth(),
                'montant_du' => $mensualite,
                'montant_paye' => 0,
                'statut' => Echeance::STATUT_A_VENIR,
            ]);
        }
    }

    private function authorizeRole(Request $request, array $roles): void
    {
        abort_unless(in_array($request->user()->role, $roles, true), 403);
    }
}
