<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collectes_tontine', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_tontine_id')->constrained('comptes_tontine')->cascadeOnDelete();
            $table->foreignId('agent_promotion_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('caissier_id')->nullable()->constrained('users')->nullOnDelete();

            $table->dateTime('date_collecte');
            $table->decimal('montant', 10, 2);
            $table->string('lieu')->nullable();
            $table->string('geolocalisation')->nullable();
            $table->enum('mode_confirmation', ['signature', 'otp_sms'])->default('signature');
            $table->enum('statut_validation', ['en_attente', 'validee', 'anomalie'])->default('en_attente');

            $table->timestamps();

            $table->index(['compte_tontine_id', 'date_collecte']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collectes_tontine');
    }
};
