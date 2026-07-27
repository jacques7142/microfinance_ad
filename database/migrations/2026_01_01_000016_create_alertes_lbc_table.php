<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertes_lbc', function (Blueprint $table) {
            $table->id();

            // Une alerte peut porter sur une opération précise et/ou sur le profil
            // du sociétaire (ex: dossier KYC incomplet) — les deux sont nullable.
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->foreignId('societaire_id')->nullable()->constrained('societaires')->nullOnDelete();

            $table->string('motif'); // ex: "montant_seuil_depasse", "operation_inhabituelle", "kyc_incomplet"
            $table->enum('niveau_risque', ['faible', 'moyen', 'eleve'])->default('faible');
            $table->enum('statut', [
                'nouvelle', 'en_cours_examen', 'declaree_cellule', 'classee_sans_suite',
            ])->default('nouvelle');

            $table->foreignId('traite_par')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_alerte');
            $table->dateTime('date_traitement')->nullable();
            $table->text('commentaire')->nullable();

            $table->timestamps();

            $table->index(['statut', 'niveau_risque']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes_lbc');
    }
};
