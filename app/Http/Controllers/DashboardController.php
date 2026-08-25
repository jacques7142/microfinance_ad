<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\CollecteTontine;
use App\Models\CompteEpargne;
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

    /** Palette de couleurs pour les graphiques (Chart.js). */
    public static function palette(int $index): string
    {
        $couleurs = [
            '#011f62', '#0a3a8f', '#e8a33d', '#1e8a5f', '#c4453b',
            '#7c3aed', '#0e7490', '#c97f1e', '#3b82f6', '#16a085',
            '#f06020', '#FFD200', '#6b7280', '#be185d',
        ];

        return $couleurs[$index % count($couleurs)];
    }

    private function admin(): View
    {
        $agences = Agence::withCount('societaires')
            ->withCount('transactions')
            ->orderBy('nom')
            ->get();

        // Vue multi-agences : agences regroupées par région (modèle réseau COOPEC-AD)
        $regionsAgences = collect(Agence::REGIONS_TOGO)
            ->map(fn ($region) => [
                'region' => $region,
                'agences' => $agences->where('region', $region)->values(),
            ])
            ->values();
        
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

        // Données du diagramme circulaire : collaborateurs par rôle.
        $rolesCoopec = [
            ['label' => 'Gérants d\'agence', 'total' => $utilisateursParlRole->get('Gérants d\'agence', 0)],
            ['label' => 'Agents de crédit', 'total' => $utilisateursParlRole->get('Agents de crédit', 0)],
            ['label' => 'Agents de promotion', 'total' => $utilisateursParlRole->get('Agents de promotion', 0)],
            ['label' => 'Caissiers', 'total' => $utilisateursParlRole->get('Cassiers', 0)],
            ['label' => 'Comptables', 'total' => $utilisateursParlRole->get('Comptables', 0)],
        ];

        // Répartition des transactions (dépôts / retraits / autres) sur le réseau.
        $repartitionTransactions = Transaction::selectRaw('type, count(*) as total')
            ->where('date_operation', '>=', now()->subDays(30))
            ->groupBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => (new Transaction(['type' => $row->type]))->libelleType(),
                'total' => $row->total,
            ]);

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
            'regionsAgences' => $regionsAgences,
            'connexionsEchouees' => \App\Models\JournalActivite::where('statut', 'echec')
                ->where('date_action', '>=', now()->subDay())
                ->count(),
            'activiteLabels' => $labels,
            'activiteData' => $activiteData,
            'utilisateursParlRole' => $utilisateursParlRole,
            'rolesCoopec' => $rolesCoopec,
            'repartitionTransactions' => $repartitionTransactions,
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

        // --- Statistiques / diagrammes circulaires ---
        $statutsCredit = Credit::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->get()
            ->map(fn ($row) => [
                'label' => Credit::STATUT_SOLDEE === $row->statut ? 'Soldée' : (new Credit(['statut' => $row->statut]))->libelleStatut(),
                'total' => $row->total,
            ]);

        $typesCredit = Credit::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => match ($row->type) {
                    Credit::TYPE_ORDINAIRE => 'Crédit ordinaire',
                    Credit::TYPE_PARTENARIAT => 'Crédit de partenariat',
                    Credit::TYPE_TONTINE => 'Crédit tontine adossé',
                    default => $row->type,
                },
                'total' => $row->total,
            ]);

        $societairesParStatut = Societaire::where('agence_id', $agenceId)
            ->selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->get()
            ->map(fn ($row) => [
                'label' => ucfirst(str_replace('_', ' ', $row->statut)),
                'total' => $row->total,
            ]);

        // Activité mensuelle de l'agence (dépôts / retraits / remboursements).
        $activiteMensuelle = Transaction::where('agence_id', $agenceId)
            ->where('date_operation', '>=', now()->startOfMonth())
            ->selectRaw('type, sum(montant) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => (new Transaction(['type' => $row->type]))->libelleType(),
                'total' => round((float) $row->total, 2),
            ]);

        $totalEpargne = CompteEpargne::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))->sum('solde');
        $collectesMois = CollecteTontine::whereHas('compteTontine.societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->where('date_collecte', '>=', now()->startOfMonth())
            ->sum('montant');
        $operationsMois = Transaction::where('agence_id', $agenceId)
            ->where('date_operation', '>=', now()->startOfMonth())
            ->count();

        // --- Suivi des services de l'agence : activité par collaborateur ---
        $equipe = User::withCount(['creditsInstruits', 'collectesEnregistrees', 'transactions', 'rapports'])
            ->where('agence_id', $agenceId)
            ->orderBy('role')
            ->get();

        return view('dashboards.gerant', [
            'creditsAValider' => $creditsAValider,
            'portefeuille' => $encoursCredit,
            'nbSocietaires' => $nbSocietaires,
            'nbCreditsEnAttente' => $nbCreditsEnAttente,
            'nbCreditsMois' => $nbCreditsMois,
            'derniersRapports' => $derniersRapports,
            'statutsCredit' => $statutsCredit,
            'typesCredit' => $typesCredit,
            'societairesParStatut' => $societairesParStatut,
            'activiteMensuelle' => $activiteMensuelle,
            'totalEpargne' => $totalEpargne,
            'collectesMois' => $collectesMois,
            'operationsMois' => $operationsMois,
            'equipe' => $equipe,
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

        // Diagramme circulaire du pipeline.
        $pipeline = collect([
            'recue' => 'Reçues',
            'en_instruction' => 'En instruction',
            'transmise_gerant' => 'Transmises au gérant',
            'validee' => 'Validées',
            'rejetee' => 'Rejetées',
        ])->map(fn ($label, $statut) => [
            'label' => $label,
            'total' => ($credits[$statut] ?? collect())->count(),
        ])->values();

        return view('dashboards.agent_credit', [
            'creditsParStatut' => $credits,
            'pipeline' => $pipeline,
            'totalDossiers' => collect($credits)->flatten(1)->count(),
        ]);
    }

    private function agentPromotion(User $user): View
    {
        $collectes = CollecteTontine::with('compteTontine.societaire')
            ->where('agent_promotion_id', $user->id)
            ->whereDate('date_collecte', today())
            ->get();

        // Collectes des 7 derniers jours pour le graphique.
        $collectes7Jours = [];
        $labels7Jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Jours[] = $date->format('d/m');
            $collectes7Jours[] = CollecteTontine::where('agent_promotion_id', $user->id)
                ->whereDate('date_collecte', $date->toDateString())
                ->sum('montant');
        }

        // Répartition des collectes du mois par mode de confirmation.
        $repartitionModes = CollecteTontine::where('agent_promotion_id', $user->id)
            ->where('date_collecte', '>=', now()->startOfMonth())
            ->selectRaw('mode_confirmation, count(*) as total')
            ->groupBy('mode_confirmation')
            ->get()
            ->map(fn ($row) => [
                'label' => ucfirst(str_replace('_', ' ', $row->mode_confirmation)),
                'total' => $row->total,
            ]);

        return view('dashboards.agent_promotion', [
            'collectesDuJour' => $collectes,
            'totalCollecte' => $collectes->sum('montant'),
            'collectes7Jours' => $collectes7Jours,
            'labels7Jours' => $labels7Jours,
            'repartitionModes' => $repartitionModes,
        ]);
    }

    private function caissier(User $user): View
    {
        $operations = Transaction::with(['compteEpargne.societaire', 'compteTontine.societaire'])
            ->where('agence_id', $user->agence_id)
            ->whereDate('date_operation', today())
            ->orderByDesc('date_operation')
            ->get();

        $totalDepots = $operations->where('type', Transaction::TYPE_DEPOT)->sum('montant');
        $totalRetraits = $operations->where('type', Transaction::TYPE_RETRAIT)->sum('montant');

        // Activité des 7 derniers jours (dépôts vs retraits).
        $serieDepots = [];
        $serieRetraits = [];
        $labels7Jours = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels7Jours[] = $date->format('d/m');
            $serieDepots[] = Transaction::where('agence_id', $user->agence_id)
                ->whereDate('date_operation', $date->toDateString())
                ->where('type', Transaction::TYPE_DEPOT)
                ->sum('montant');
            $serieRetraits[] = Transaction::where('agence_id', $user->agence_id)
                ->whereDate('date_operation', $date->toDateString())
                ->where('type', Transaction::TYPE_RETRAIT)
                ->sum('montant');
        }

        return view('dashboards.caissier', [
            'operationsDuJour' => $operations,
            'totalDepots' => $totalDepots,
            'totalRetraits' => $totalRetraits,
            'serieDepots' => $serieDepots,
            'serieRetraits' => $serieRetraits,
            'labels7Jours' => $labels7Jours,
        ]);
    }

    private function comptable(User $user): View
    {
        $agenceId = $user->agence_id;

        $creditsParType = Credit::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => match ($row->type) {
                    Credit::TYPE_ORDINAIRE => 'Crédit ordinaire',
                    Credit::TYPE_PARTENARIAT => 'Crédit de partenariat',
                    Credit::TYPE_TONTINE => 'Crédit tontine adossé',
                    default => $row->type,
                },
                'total' => $row->total,
            ]);

        // Répartition de l'encours épargne par type de compte.
        $epargneParType = CompteEpargne::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))
            ->selectRaw('type, sum(solde) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->type === 'DAV' ? 'DAV — Dépôt à vue' : 'DAT — Dépôt à terme',
                'total' => round((float) $row->total, 2),
            ]);

        return view('dashboards.comptable', [
            'totalEpargne' => CompteEpargne::whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))->sum('solde'),
            'encoursCredit' => Credit::where('statut', Credit::STATUT_VALIDEE)->whereHas('societaire', fn ($q) => $q->where('agence_id', $agenceId))->sum('montant'),
            'creditsParType' => $creditsParType,
            'epargneParType' => $epargneParType,
        ]);
    }
}