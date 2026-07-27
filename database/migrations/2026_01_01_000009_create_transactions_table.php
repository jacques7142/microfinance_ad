<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agence_id')->constrained('agences')->restrictOnDelete();
            $table->foreignId('utilisateur_id')->constrained('users')->restrictOnDelete(); // effectuée par

            // Un seul de ces trois FK est renseigné selon le compte impacté par l'opération
            $table->foreignId('compte_epargne_id')->nullable()->constrained('comptes_epargne')->nullOnDelete();
            $table->foreignId('compte_tontine_id')->nullable()->constrained('comptes_tontine')->nullOnDelete();
            $table->foreignId('credit_id')->nullable()->constrained('credits')->nullOnDelete();

            $table->enum('type', [
                'depot', 'retrait', 'remboursement', 'collecte_tontine',
                'decaissement_credit', 'correction',
            ]);
            $table->decimal('montant', 14, 2);
            $table->dateTime('date_operation');
            $table->enum('statut', ['validee', 'annulee', 'tracee'])->default('validee');
            $table->boolean('corrigee')->default(false);

            $table->timestamps();

            $table->index(['agence_id', 'date_operation']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
