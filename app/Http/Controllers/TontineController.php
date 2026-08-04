<?php

namespace App\Http\Controllers;

use App\Models\CollecteTontine;
use App\Models\CompteTontine;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TontineController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $comptes = CompteTontine::with('societaire')
            ->where('zone_tournee', $user->zone_tournee)
            ->get();

        $collectesDuJour = CollecteTontine::where('agent_promotion_id', $user->id)
            ->whereDate('date_collecte', today())
            ->pluck('compte_tontine_id');

        return view('tontine.index', [
            'comptes' => $comptes,
            'collectesDuJour' => $collectesDuJour,
        ]);
    }

    /** Enregistre une collecte terrain — reproduit le diagramme de séquence "collecte LOGOKU". */
    public function collecter(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'compte_tontine_id' => ['required', 'exists:comptes_tontine,id'],
            'montant' => ['required', 'numeric', 'min:100'],
            'lieu' => ['nullable', 'string', 'max:255'],
            'geolocalisation' => ['nullable', 'string', 'max:255'],
            'mode_confirmation' => ['required', 'in:signature,otp_sms'],
        ]);

        $compte = CompteTontine::findOrFail($data['compte_tontine_id']);
        $user = $request->user();

        DB::transaction(function () use ($compte, $data, $user) {
            $collecte = CollecteTontine::create([
                'compte_tontine_id' => $compte->id,
                'agent_promotion_id' => $user->id,
                'date_collecte' => now(),
                'montant' => $data['montant'],
                'lieu' => $data['lieu'] ?? null,
                'geolocalisation' => $data['geolocalisation'] ?? null,
                'mode_confirmation' => $data['mode_confirmation'],
                'statut_validation' => 'en_attente', // validée ensuite par le caissier
            ]);

            $compte->solde_accumule = (float) $compte->solde_accumule + $data['montant'];
            $compte->save();

            Transaction::create([
                'agence_id' => $user->agence_id,
                'utilisateur_id' => $user->id,
                'compte_tontine_id' => $compte->id,
                'type' => Transaction::TYPE_COLLECTE_TONTINE,
                'montant' => $data['montant'],
                'date_operation' => now(),
                'statut' => 'validee',
            ]);

            Notification::create([
                'societaire_id' => $compte->societaire_id,
                'type' => 'sms',
                'contenu' => "Collecte tontine de {$data['montant']} F enregistrée. Solde : {$compte->solde_accumule} F.",
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
            ]);

            JournalActivite::enregistrer('collecte_tontine', "Collecte de {$data['montant']} F — compte tontine #{$compte->id}", $collecte);
        });

        return back()->with('success', 'Collecte enregistrée, reçu envoyé au membre.');
    }

    /** Le caissier valide les collectes du jour en fin de journée. */
    public function valider(Request $request, CollecteTontine $collecte): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === 'caissier', 403);

        $collecte->load('compteTontine.societaire');
        if ($collecte->compteTontine->societaire->agence_id !== $user->agence_id) {
            abort(403);
        }

        $collecte->update(['caissier_id' => $user->id, 'statut_validation' => 'validee']);

        JournalActivite::enregistrer('validation_collecte', "Collecte tontine #{$collecte->id} validée par le caissier", $collecte);

        return back()->with('success', 'Collecte validée.');
    }
}
