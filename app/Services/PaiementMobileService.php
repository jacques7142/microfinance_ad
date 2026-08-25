<?php

namespace App\Services;

use App\Models\AlerteLbc;
use App\Models\CompteEpargne;
use App\Models\Credit;
use App\Models\Echeance;
use App\Models\JournalActivite;
use App\Models\Notification;
use App\Models\PaiementMobile;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PaiementMobileService
{
    /**
     * Finalise une demande de paiement mobile (payin ou payout) une fois le
     * paiement confirmé par l'opérateur (callback LigdiCash ou mode démo).
     */
    public function finaliser(PaiementMobile $paiement, array $payload = []): void
    {
        if ($paiement->estFinalise()) {
            return;
        }

        if ($paiement->sens === PaiementMobile::SENS_PAYIN) {
            $this->finaliserPayin($paiement, $payload);
        } else {
            $this->finaliserPayout($paiement, $payload);
        }
    }

    /**
     * Mode démo : marque le paiement comme « completed » et exécute la même
     * finalisation métier que si l'opérateur avait confirmé l'USSD push.
     */
    public function simulerConfirmation(PaiementMobile $paiement): void
    {
        $payload = [
            'demo' => true,
            'external_id' => $paiement->reference_interne,
            'status' => 'completed',
            'simulation' => 'Paiement validé par l\'opérateur (mode démo).',
        ];

        $this->finaliser($paiement, $payload);
    }

    /**
     * Finalise un payin (dépôt ou remboursement) une fois le paiement confirmé.
     */
    protected function finaliserPayin(PaiementMobile $paiement, array $payload): void
    {
        DB::transaction(function () use ($paiement, $payload) {
            $societaire = $paiement->societaire;

            if ($paiement->type === PaiementMobile::TYPE_DEPOT) {
                $compte = CompteEpargne::findOrFail($paiement->compte_epargne_id);
                $compte->solde = (float) $compte->solde + (float) $paiement->montant;
                $compte->save();

                $transaction = $this->creerTransaction($paiement, Transaction::TYPE_DEPOT);
                $contenu = "Dépôt de {$paiement->montant} F confirmé par {$paiement->operateurLibelle()} sur votre compte {$compte->type}.";
                $libelle = "Dépôt mobile ({$paiement->operateurLibelle()}) de {$paiement->montant} F — compte {$compte->type} #{$compte->id}";

                $this->creerAlerteLbc($transaction, $societaire);
            } else {
                // Remboursement de crédit via échéance.
                $echeance = Echeance::with('credit')->findOrFail($paiement->echeance_id);
                $echeance->montant_paye = (float) $echeance->montant_paye + (float) $paiement->montant;
                if ((float) $echeance->montant_paye >= (float) $echeance->montant_du) {
                    $echeance->statut = Echeance::STATUT_PAYEE;
                }
                $echeance->save();

                if ($echeance->credit->echeances()->where('statut', '!=', Echeance::STATUT_PAYEE)->count() === 0) {
                    $echeance->credit->statut = Credit::STATUT_SOLDEE;
                    $echeance->credit->save();
                }

                $transaction = $this->creerTransaction($paiement, Transaction::TYPE_REMBOURSEMENT);
                $contenu = "Remboursement de {$paiement->montant} F reçu par {$paiement->operateurLibelle()} pour le crédit #{$echeance->credit_id}.";
                $libelle = "Remboursement mobile ({$paiement->operateurLibelle()}) de {$paiement->montant} F — échéance #{$echeance->id}";
            }

            Notification::create([
                'societaire_id' => $societaire->id,
                'type' => 'sms',
                'contenu' => $contenu,
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
                'lien' => route('societaire.mon-compte'),
            ]);

            JournalActivite::enregistrer('paiement_mobile_'.$paiement->type, $libelle, $transaction);

            $paiement->transaction_id = $transaction->id;
            $paiement->finaliser(PaiementMobile::STATUT_COMPLETED, null, $payload);
        });
    }

    /**
     * Finalise un payout (retrait vers mobile money) une fois le transfert confirmé.
     */
    protected function finaliserPayout(PaiementMobile $paiement, array $payload): void
    {
        DB::transaction(function () use ($paiement, $payload) {
            $compte = CompteEpargne::findOrFail($paiement->compte_epargne_id);
            $societaire = $paiement->societaire;

            if ((float) $paiement->montant > (float) $compte->solde) {
                throw new \RuntimeException('Solde insuffisant pour finaliser le retrait.');
            }

            $compte->solde = (float) $compte->solde - (float) $paiement->montant;
            $compte->save();

            $transaction = $this->creerTransaction($paiement, Transaction::TYPE_RETRAIT);
            $libelle = "Retrait mobile ({$paiement->operateurLibelle()}) de {$paiement->montant} F — compte {$compte->type} #{$compte->id}";

            Notification::create([
                'societaire_id' => $societaire->id,
                'type' => 'sms',
                'contenu' => "Retrait de {$paiement->montant} F envoyé sur votre {$paiement->operateurLibelle()} ({$paiement->telephone}).",
                'date_envoi' => now(),
                'statut_envoi' => 'envoyee',
                'lien' => route('societaire.mon-compte'),
            ]);

            JournalActivite::enregistrer('paiement_mobile_retrait', $libelle, $transaction);

            $paiement->transaction_id = $transaction->id;
            $paiement->finaliser(PaiementMobile::STATUT_COMPLETED, null, $payload);
        });
    }

    protected function creerTransaction(PaiementMobile $paiement, string $type): Transaction
    {
        return Transaction::create([
            'agence_id' => $paiement->societaire->agence_id,
            'societaire_id' => $paiement->societaire_id,
            'compte_epargne_id' => $paiement->compte_epargne_id,
            'credit_id' => $paiement->credit_id,
            'type' => $type,
            'montant' => $paiement->montant,
            'date_operation' => now(),
            'statut' => 'validee',
        ]);
    }

    protected function creerAlerteLbc(Transaction $transaction, $societaire): void
    {
        $seuilVigilance = (float) config('coopec.seuil_vigilance_lbc');
        if ((float) $transaction->montant >= $seuilVigilance) {
            AlerteLbc::create([
                'transaction_id' => $transaction->id,
                'societaire_id' => $societaire->id,
                'motif' => 'montant_seuil_depasse',
                'niveau_risque' => 'moyen',
                'statut' => 'nouvelle',
                'date_alerte' => now(),
            ]);
        }
    }
}