<?php

namespace App\Http\Controllers;

use App\Models\CompteEpargne;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\Societaire;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompteEpargneController extends Controller
{
    public function index(Request $request): View
    {
        $societaires = Societaire::where('agence_id', $request->user()->agence_id)
            ->orderBy('nom')->get(['id', 'nom', 'prenom', 'numero_societaire']);

        return view('comptes_epargne.index', ['societaires' => $societaires]);
    }

    /** Recherche un sociétaire et ses comptes (appelé en AJAX/GET depuis le guichet). */
    public function comptes(Request $request, Societaire $societaire): View
    {
        $user = $request->user();
        if (!$user->hasRole('administrateur') && $societaire->agence_id !== $user->agence_id) {
            abort(403);
        }

        $societaire->load('comptesEpargne');

        return view('comptes_epargne.comptes', ['societaire' => $societaire]);
    }

    /** Opération de dépôt ou de retrait — cœur du diagramme de séquence "guichet". */
    public function operation(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'compte_epargne_id' => ['required', 'exists:comptes_epargne,id'],
            'type' => ['required', 'in:depot,retrait'],
            'montant' => ['required', 'numeric', 'min:100'],
        ]);

        $compte = CompteEpargne::with('societaire')->findOrFail($data['compte_epargne_id']);
        $user = $request->user();

        if (!$user->hasRole('administrateur') && $compte->societaire->agence_id !== $user->agence_id) {
            abort(403);
        }

        if ($data['type'] === 'retrait') {
            if ($data['montant'] > (float) $compte->solde) {
                return back()->withErrors(['montant' => 'Solde insuffisant pour ce retrait.']);
            }
            if ($compte->plafond_retrait_journalier && $data['montant'] > (float) $compte->plafond_retrait_journalier) {
                return back()->withErrors(['montant' => 'Montant supérieur au plafond de retrait journalier.']);
            }
        }

        DB::transaction(function () use ($compte, $data, $user) {
            $compte->solde = $data['type'] === 'depot'
                ? (float) $compte->solde + $data['montant']
                : (float) $compte->solde - $data['montant'];
            $compte->save();

            $transaction = Transaction::create([
                'agence_id' => $user->agence_id,
                'utilisateur_id' => $user->id,
                'compte_epargne_id' => $compte->id,
                'type' => $data['type'] === 'depot' ? Transaction::TYPE_DEPOT : Transaction::TYPE_RETRAIT,
                'montant' => $data['montant'],
                'date_operation' => now(),
                'statut' => 'validee',
            ]);

            // Vigilance LBC/FT : seuil illustratif — à ajuster selon la politique interne réelle.
            $seuilVigilance = (float) config('coopec.seuil_vigilance_lbc');
            if ($data['montant'] >= $seuilVigilance) {
                \App\Models\AlerteLbc::create([
                    'transaction_id' => $transaction->id,
                    'societaire_id' => $compte->societaire_id,
                    'motif' => 'montant_seuil_depasse',
                    'niveau_risque' => 'moyen',
                    'statut' => 'nouvelle',
                    'date_alerte' => now(),
                ]);
            }

            Notification::create([
                'societaire_id' => $compte->societaire_id,
                'type' => 'sms',
                'contenu' => ucfirst($data['type'])." de {$data['montant']} F confirmé sur votre compte {$compte->type}.",
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
            ]);

            JournalActivite::enregistrer(
                'operation_guichet',
                ucfirst($data['type'])." de {$data['montant']} F — compte {$compte->type} #{$compte->id}",
                $transaction
            );
        });

        return back()->with('success', 'Opération enregistrée.');
    }
}
