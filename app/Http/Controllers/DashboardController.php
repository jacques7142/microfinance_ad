<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\CollecteTontine;
use App\Models\Credit;
use App\Models\Rapport;
use App\Models\Societaire;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Point d'entrée unique après connexion : chaque rôle a son propre écran. */
    public function index(Request $request): View
    {
        $user = $request->user();

        return match ($user->role) {
            User::ROLE_ADMIN => $this->admin(),
            User::ROLE_GERANT => $this->gerant($user),
            User::ROLE_AGENT_CREDIT => $this->agentCredit($user),
            User::ROLE_AGENT_PROMOTION => $this->agentPromotion($user),
            User::ROLE_CAISSIER => $this->caissier($user),
            User::ROLE_COMPTABLE => $this->comptable($user),
            default => abort(403),
        };
    }

    private function admin(): View
    {
        $agences = Agence::withCount('societaires')
            ->withCount('transactions')
            ->orderBy('nom')
            ->get();
        
        // Données pour le graphique d'activité (30 derniers jours, top 5 agences)
        $top5Agences = Agence::withCount('transactions')
            ->orderByDesc('transactions_count')
            ->take(5)
            ->get()
            ->pluck('id')
            ->toArray();
        
        $activiteData = [];
        $labels = [];
        
        // Générer les labels pour les 30 derniers jours
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
        }
        
        // Récupérer les données d'activité pour chaque agence
        foreach ($top5Agences as $agenceId) {
            $agence = Agence::find($agenceId);
            $data = [];
            
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = Transaction::where('agence_id', $agenceId)
                    ->whereDate('date_operation', $date->toDateString())
                    ->count();
                $data[] = $count;
            }
            
            $activiteData[] = [
                'label' => $agence->nom ?? 'Agence ' . $agenceId,
                'data' => $data,
            ];
        }
        
        // Gestion des utilisateurs par rôle
        $utilisateursParlRole = User::select('role')
            ->selectRaw('count(*) as count')
            ->groupBy('role')
            ->get()
            ->mapWithKeys(function ($item) {
                $roleLabels = [
                    'administrateur' => 'Gérants d\'agence',
                    'gerant' => 'Gérants d\'agence',
                    'agent_credit' => 'Agents de crédit',
                    'agent_promotion' => 'Agents de promotion',
                    'caissier' => 'Cassiers',
                    'comptable' => 'Comptables',
                ];
                return [$roleLabels[$item->role] ?? $item->role => $item->count];
            });
        
        // Journal d'activité (10 dernières entrées)
        $journalActivite = \App\Models\JournalActivite::with('utilisateur')
            ->latest('date_action')
            ->take(10)
            ->get();

        return view('dashboards.admin', [
            'nbSocietaires' => Societaire::count(),
            'nbAgences' => Agence::where('est_siege', false)->count(),
            'nbUtilisateurs' => User::count(),
            'agences' => $agences,
            'connexionsEchouees' => \App\Models\JournalActivite::where('statut', 'echec')
                ->where('date_action', '>=', now()->subDay())
                ->count(),
            'activiteLabels' => $labels,
            'activiteData' => $activiteData,
            'utilisateursParlRole' => $utilisateursParlRole,
            'journalActivite' => $journalActivite,
        ]);
    }

    private function gerant(User $user): View
    {
        $agenceId = $user->agence_id;

        $creditsAValider = Credit::with(['societaire', 'agentCredit'])
            ->whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->where('statut', Credit::STATUT_TRANSMISE_GERANT)
            ->orderByDesc('date_demande')
            ->get();

        $nbCreditsEnAttente = $creditsAValider->count();

        $encoursCredit = Credit::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->where('statut', Credit::STATUT_VALIDEE)
            ->sum('montant');

        $nbCreditsMois = Credit::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->whereMonth('date_demande', now()->month)
            ->whereYear('date_demande', now()->year)
            ->count();

        $nbSocietaires = Societaire::where('agence_id', $agenceId)->count();

        $derniersRapports = Rapport::with('agence', 'utilisateur')
            ->where('agence_id', $agenceId)
            ->latest('date_generation')
            ->take(5)
            ->get();

        return view('dashboards.gerant', [
            'creditsAValider' => $creditsAValider,
            'portefeuille' => $encoursCredit,
            'nbSocietaires' => $nbSocietaires,
            'nbCreditsEnAttente' => $nbCreditsEnAttente,
            'nbCreditsMois' => $nbCreditsMois,
            'derniersRapports' => $derniersRapports,
        ]);
    }

    private function agentCredit(User $user): View
    {
        $credits = Credit::with('societaire')
            ->where('agent_credit_id', $user->id)
            ->orWhere(function ($q) use ($user) {
                $q->whereNull('agent_credit_id')
                    ->whereHas('societaire', fn ($sq) => $sq->where('agence_id', $user->agence_id));
            })
            ->orderByDesc('date_demande')
            ->get()
            ->groupBy('statut');

        return view('dashboards.agent_credit', ['creditsParStatut' => $credits]);
    }

    private function agentPromotion(User $user): View
    {
        $collectes = CollecteTontine::with('compteTontine.societaire')
            ->where('agent_promotion_id', $user->id)
            ->whereDate('date_collecte', today())
            ->get();

        return view('dashboards.agent_promotion', [
            'collectesDuJour' => $collectes,
            'totalCollecte' => $collectes->sum('montant'),
        ]);
    }

    private function caissier(User $user): View
    {
        $operations = Transaction::with(['compteEpargne.societaire', 'compteTontine.societaire'])
            ->where('agence_id', $user->agence_id)
            ->whereDate('date_operation', today())
            ->orderByDesc('date_operation')
            ->get();

        return view('dashboards.caissier', [
            'operationsDuJour' => $operations,
            'totalDepots' => $operations->where('type', Transaction::TYPE_DEPOT)->sum('montant'),
            'totalRetraits' => $operations->where('type', Transaction::TYPE_RETRAIT)->sum('montant'),
        ]);
    }

    private function comptable(User $user): View
    {
        $agenceId = $user->agence_id;

        return view('dashboards.comptable', [
            'totalEpargne' => \App\Models\CompteEpargne::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))->sum('solde'),
            'encoursCredit' => Credit::where('statut', Credit::STATUT_VALIDEE)->whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))->sum('montant'),
            'creditsParType' => Credit::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))->selectRaw('type, count(*) as total')->groupBy('type')->get(),
        ]);
    }
}
