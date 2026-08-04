<?php

namespace App\Http\Controllers;

use App\Models\CompteEpargne;
use App\Models\Credit;
use App\Models\Echeance;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SocietaireOperationController extends Controller
{
    public function depotForm(): View
    {
        $societaire = Auth::guard('societaire')->user()->load('comptesEpargne');

        return view('societaires.depot', ['societaire' => $societaire]);
    }

    public function depot(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'compte_epargne_id' => ['required', 'exists:comptes_epargne,id'],
            'montant' => ['required', 'numeric', 'min:100'],
        ]);

        $societaire = Auth::guard('societaire')->user();
        $compte = CompteEpargne::findOrFail($data['compte_epargne_id']);

        if ($compte->societaire_id !== $societaire->id) {
            return back()->withErrors(['compte_epargne_id' => 'Ce compte ne vous appartient pas.']);
        }

        DB::transaction(function () use ($compte, $data, $societaire) {
            $compte->solde = (float) $compte->solde + $data['montant'];
            $compte->save();

            $transaction = Transaction::create([
                'agence_id' => $societaire->agence_id,
                'societaire_id' => $societaire->id,
                'compte_epargne_id' => $compte->id,
                'type' => Transaction::TYPE_DEPOT,
                'montant' => $data['montant'],
                'date_operation' => now(),
                'statut' => 'validee',
            ]);

            Notification::create([
                'societaire_id' => $societaire->id,
                'type' => 'sms',
                'contenu' => "Dépôt de {$data['montant']} F confirmé sur votre compte {$compte->type}.",
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
            ]);

            JournalActivite::enregistrer('depot_societaire', "Dépôt de {$data['montant']} F — compte {$compte->type} #{$compte->id}", $transaction);
        });

        return redirect()->route('societaire.dashboard')->with('success', 'Votre dépôt a été enregistré avec succès.');
    }

    public function retraitForm(): View
    {
        $societaire = Auth::guard('societaire')->user()->load('comptesEpargne');

        return view('societaires.retrait', ['societaire' => $societaire]);
    }

    public function retrait(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'compte_epargne_id' => ['required', 'exists:comptes_epargne,id'],
            'montant' => ['required', 'numeric', 'min:100'],
        ]);

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

        DB::transaction(function () use ($compte, $data, $societaire) {
            $compte->solde = (float) $compte->solde - $data['montant'];
            $compte->save();

            $transaction = Transaction::create([
                'agence_id' => $societaire->agence_id,
                'societaire_id' => $societaire->id,
                'compte_epargne_id' => $compte->id,
                'type' => Transaction::TYPE_RETRAIT,
                'montant' => $data['montant'],
                'date_operation' => now(),
                'statut' => 'validee',
            ]);

            Notification::create([
                'societaire_id' => $societaire->id,
                'type' => 'sms',
                'contenu' => "Retrait de {$data['montant']} F confirmé sur votre compte {$compte->type}.",
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
            ]);

            JournalActivite::enregistrer('retrait_societaire', "Retrait de {$data['montant']} F — compte {$compte->type} #{$compte->id}", $transaction);
        });

        return redirect()->route('societaire.dashboard')->with('success', 'Votre retrait a été enregistré avec succès.');
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

        DB::transaction(function () use ($echeance, $data, $societaire) {
            $echeance->montant_paye = (float) $echeance->montant_paye + $data['montant'];
            if ((float) $echeance->montant_paye >= (float) $echeance->montant_du) {
                $echeance->statut = Echeance::STATUT_PAYEE;
            }
            $echeance->save();

            $toutesPayees = $echeance->credit->echeances()->where('statut', '!=', Echeance::STATUT_PAYEE)->count() === 0;
            if ($toutesPayees) {
                $echeance->credit->statut = Credit::STATUT_SOLDEE;
                $echeance->credit->save();
            }

            $transaction = Transaction::create([
                'agence_id' => $societaire->agence_id,
                'societaire_id' => $societaire->id,
                'credit_id' => $echeance->credit_id,
                'type' => Transaction::TYPE_REMBOURSEMENT,
                'montant' => $data['montant'],
                'date_operation' => now(),
                'statut' => 'validee',
            ]);

            Notification::create([
                'societaire_id' => $societaire->id,
                'type' => 'sms',
                'contenu' => "Remboursement de {$data['montant']} F reçu pour le crédit #{$echeance->credit_id}.",
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
            ]);

            JournalActivite::enregistrer('remboursement_societaire', "Remboursement de {$data['montant']} F — échéance #{$echeance->id} du crédit #{$echeance->credit_id}", $transaction);
        });

        return redirect()->route('societaire.remboursement')->with('success', 'Remboursement enregistré.');
    }
}