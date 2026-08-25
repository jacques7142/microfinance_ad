<?php

namespace App\Http\Controllers;

use App\Models\CompteEpargne;
use App\Models\Credit;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SocietairePortalController extends Controller
{
    public function dashboard(): View
    {
        $societaire = Auth::guard('societaire')
            ->user()
            ->load([
                'agence',
                'comptesEpargne',
                'compteTontine',
                'credits.echeances',
            ]);

        // Diagramme circulaire : répartition de l'épargne.
        $epargneParType = CompteEpargne::where('societaire_id', $societaire->id)
            ->selectRaw('type, sum(solde) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->type === 'DAV' ? 'DAV — Dépôt à vue' : 'DAT — Dépôt à terme',
                'total' => round((float) $row->total, 2),
            ]);

        if ($societaire->compteTontine && (float) $societaire->compteTontine->solde_accumule > 0) {
            $epargneParType->push([
                'label' => 'Tontine LOGOKU',
                'total' => round((float) $societaire->compteTontine->solde_accumule, 2),
            ]);
        }

        // Diagramme circulaire : statut des demandes de crédit.
        $creditsParStatut = $societaire->credits
            ->groupBy('statut')
            ->map(fn ($groupe, $statut) => [
                'label' => (new Credit(['statut' => $statut]))->libelleStatut(),
                'total' => $groupe->count(),
            ])
            ->values();

        return view('societaires.portal', [
            'societaire' => $societaire,
            'credits' => $societaire->credits()->orderByDesc('date_demande')->limit(5)->get(),
            'sousTypesOrdinaire' => Credit::SOUS_TYPES_ORDINAIRE,
            'epargneParType' => $epargneParType,
            'creditsParStatut' => $creditsParStatut,
        ]);
    }

    public function monCompte(): View
    {
        $societaire = Auth::guard('societaire')
            ->user()
            ->load([
                'agence',
                'comptesEpargne',
                'compteTontine',
                'credits.echeances',
            ]);

        $transactions = Transaction::where('societaire_id', $societaire->id)
            ->with(['compteEpargne', 'credit'])
            ->orderByDesc('date_operation')
            ->limit(50)
            ->get();

        $notifications = $societaire->notifications()
            ->orderByDesc('date_envoi')
            ->limit(30)
            ->get();

        return view('societaires.mon_compte', [
            'societaire' => $societaire,
            'transactions' => $transactions,
            'notifications' => $notifications,
            'credits' => $societaire->credits()->orderByDesc('date_demande')->get(),
        ]);
    }

    public function lireNotifications(): RedirectResponse
    {
        $societaire = Auth::guard('societaire')->user();

        $societaire->notifications()
            ->where('lu', false)
            ->update(['lu' => true]);

        return back()->with('success', 'Toutes vos notifications ont été marquées comme lues.');
    }

    public function markNotificationsRead(Request $request): RedirectResponse
    {
        $societaire = Auth::guard('societaire')->user();

        $societaire->notifications()
            ->where('lu', false)
            ->update(['lu' => true]);

        return back();
    }
}
