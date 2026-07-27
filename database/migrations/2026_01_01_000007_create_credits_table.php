<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societaire_id')->constrained('societaires')->restrictOnDelete();
            $table->foreignId('agent_credit_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('gerant_id')->nullable()->constrained('users')->nullOnDelete();

            // Renseigné uniquement pour les crédits de type "tontine" (adossement — cf. diagramme de classes)
            $table->foreignId('compte_tontine_id')->nullable()->constrained('comptes_tontine')->nullOnDelete();

            // Héritage à table unique : Credit <|-- CreditOrdinaire, CreditPartenariat, CreditTontine
            $table->enum('type', ['ordinaire', 'partenariat', 'tontine']);

            // Spécifique CreditOrdinaire : les 8 sous-types réels (cf. cahier des charges §3.3)
            $table->enum('sous_type', [
                'investissement', 'exploitation', 'financement_marche', 'salaire',
                'consommation', 'agricole', 'energies_vertes', 'activites_culturelles',
            ])->nullable();

            // Spécifique CreditPartenariat
            $table->string('partenaire')->nullable();

            // Spécifique CreditTontine
            $table->decimal('proportion_garantie', 5, 2)->nullable();

            $table->decimal('montant', 12, 2);
            $table->integer('duree_mois');
            $table->decimal('taux_interet', 5, 2);
            $table->date('date_demande');
            $table->text('avis_agent')->nullable();
            $table->enum('statut', [
                'recue', 'en_instruction', 'transmise_gerant', 'validee', 'rejetee', 'soldee',
            ])->default('recue');

            $table->timestamps();

            $table->index(['societaire_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
