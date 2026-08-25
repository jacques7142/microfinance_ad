<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement_mobile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societaire_id')->constrained('societaires')->restrictOnDelete();

            // Référence interne marchand (externe à LigdiCash) pour réconcilier le callback.
            $table->string('reference_interne')->unique();

            // Opération métier et sens du flux d'argent.
            $table->enum('type', ['depot', 'remboursement', 'retrait']);
            $table->enum('sens', ['payin', 'payout']);
            $table->enum('operateur', ['yas', 'flooz']);
            $table->string('telephone');
            $table->decimal('montant', 14, 2);
            $table->enum('statut', ['pending', 'completed', 'notcompleted', 'failed'])->default('pending');

            // Liens vers l'opération interne (compte/échéance/transaction).
            $table->foreignId('compte_epargne_id')->nullable()->constrained('comptes_epargne')->nullOnDelete();
            $table->foreignId('credit_id')->nullable()->constrained('credits')->nullOnDelete();
            $table->foreignId('echeance_id')->nullable()->constrained('echeances')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();

            // Références LigdiCash.
            $table->text('token')->nullable();
            $table->string('request_id')->nullable();
            $table->json('callback_payload')->nullable();

            $table->dateTime('date_initiation');
            $table->dateTime('date_finalisation')->nullable();
            $table->timestamps();

            $table->index(['societaire_id', 'statut']);
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement_mobile');
    }
};
