<?php

namespace Database\Seeders;

use App\Models\CollecteTontine;
use App\Models\CompteEpargne;
use App\Models\CompteTontine;
use App\Models\Credit;
use App\Models\Echeance;
use App\Models\Societaire;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $societaires = Societaire::all();
        $agentCredit = User::where('role', User::ROLE_AGENT_CREDIT)->first();
        $agentPromotion = User::where('role', User::ROLE_AGENT_PROMOTION)->first();
        $gerant = User::where('role', User::ROLE_GERANT)->first();
        $caissier = User::where('role', User::ROLE_CAISSIER)->first();

        foreach ($societaires as $i => $societaire) {
            // Compte DAV pour tous
            $dav = CompteEpargne::create([
                'societaire_id' => $societaire->id,
                'type' => CompteEpargne::TYPE_DAV,
                'solde' => rand(20000, 500000),
                'date_ouverture' => $societaire->date_adhesion,
                'plafond_retrait_journalier' => 200000,
            ]);

            // Un DAT pour un sociétaire sur deux
            if ($i % 2 === 0) {
                CompteEpargne::create([
                    'societaire_id' => $societaire->id,
                    'type' => CompteEpargne::TYPE_DAT,
                    'solde' => rand(300000, 1500000),
                    'date_ouverture' => $societaire->date_adhesion,
                    'date_echeance' => now()->addMonths(6),
                    'taux_remuneration' => 4.5,
                    'duree_blocage_mois' => 12,
                ]);
            }

            // Compte tontine pour un sociétaire sur trois
            if ($i % 3 === 0) {
                $tontine = CompteTontine::create([
                    'societaire_id' => $societaire->id,
                    'solde_accumule' => rand(20000, 200000),
                    'mise_habituelle' => [500, 1500, 3000][array_rand([500, 1500, 3000])],
                    'zone_tournee' => 'Kodjoviakopé Centre',
                    'date_adhesion' => $societaire->date_adhesion,
                ]);

                for ($c = 0; $c < 4; $c++) {
                    CollecteTontine::create([
                        'compte_tontine_id' => $tontine->id,   // ← corrigé
                        'agent_promotion_id' => $agentPromotion->id,
                        'date_collecte' => now()->subDays($c * 5),
                        'montant' => $tontine->mise_habituelle,
                        'lieu' => 'Domicile — Kodjoviakopé',
                        'mode_confirmation' => 'signature',
                        'statut_validation' => 'validee',
                    ]);
                }
            }

            // Dépôt initial
            Transaction::create([
                'agence_id' => $societaire->agence_id,
                'utilisateur_id' => $caissier->id,
                'compte_epargne_id' => $dav->id,
                'type' => Transaction::TYPE_DEPOT,
                'montant' => $dav->solde,
                'date_operation' => $societaire->date_adhesion,
                'statut' => 'validee',
            ]);
        }

        // === Crédits de démonstration ===
        $s1 = $societaires[0];
        $s2 = $societaires[1];
        $s3 = $societaires[2];

        Credit::create([
            'societaire_id' => $s1->id,
            'type' => Credit::TYPE_ORDINAIRE,
            'sous_type' => 'salaire',
            'montant' => 350000,
            'duree_mois' => 12,
            'taux_interet' => 12,
            'date_demande' => now()->subDays(2),
            'statut' => Credit::STATUT_RECUE,
        ]);

        $c2 = Credit::create([
            'societaire_id' => $s2->id,
            'agent_credit_id' => $agentCredit->id,
            'type' => Credit::TYPE_ORDINAIRE,
            'sous_type' => 'exploitation',
            'montant' => 180000,
            'duree_mois' => 10,
            'taux_interet' => 12,
            'date_demande' => now()->subDays(5),
            'statut' => Credit::STATUT_TRANSMISE_GERANT,
            'avis_agent' => 'Dossier cohérent, historique de remboursement sans incident.',
        ]);

        $c3 = Credit::create([
            'societaire_id' => $s3->id,
            'agent_credit_id' => $agentCredit->id,
            'gerant_id' => $gerant->id,
            'type' => Credit::TYPE_ORDINAIRE,
            'sous_type' => 'investissement',
            'montant' => 450000,
            'duree_mois' => 18,
            'taux_interet' => 13,
            'date_demande' => now()->subDays(20),
            'statut' => Credit::STATUT_VALIDEE,
            'avis_agent' => 'Favorable — garanties suffisantes.',
        ]);

        // Échéancier pour le crédit validé
        $mensualite = round(((float) $c3->montant * (1 + (float) $c3->taux_interet / 100)) / $c3->duree_mois, 2);
        for ($i = 1; $i <= $c3->duree_mois; $i++) {
            Echeance::create([
                'credit_id' => $c3->id,
                'date_echeance' => now()->subDays(20)->addMonths($i),
                'montant_du' => $mensualite,
                'montant_paye' => $i <= 2 ? $mensualite : 0,
                'statut' => $i <= 2 ? Echeance::STATUT_PAYEE : ($i === 3 ? Echeance::STATUT_EN_RETARD : Echeance::STATUT_A_VENIR),
            ]);
        }
    }
}