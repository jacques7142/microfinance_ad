<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('echeances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')->constrained('credits')->cascadeOnDelete();

            $table->date('date_echeance');
            $table->decimal('montant_du', 12, 2);
            $table->decimal('montant_paye', 12, 2)->default(0);
            $table->enum('statut', ['a_venir', 'payee', 'en_retard'])->default('a_venir');

            $table->timestamps();

            $table->index(['credit_id', 'date_echeance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('echeances');
    }
};
