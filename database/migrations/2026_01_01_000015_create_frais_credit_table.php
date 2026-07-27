<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frais_credit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->constrained('credits')->cascadeOnDelete();

            // Toute charge obligatoire imposée à l'emprunteur entre dans le calcul du TAEG
            // (Avis BCEAO n°007-12-2025) : frais de dossier, commissions, assurance, etc.
            $table->enum('type_frais', ['dossier', 'commission', 'assurance', 'autre']);
            $table->string('libelle')->nullable();
            $table->decimal('montant', 12, 2);
            $table->date('date_application');

            $table->timestamps();

            $table->index('credit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frais_credit');
    }
};
