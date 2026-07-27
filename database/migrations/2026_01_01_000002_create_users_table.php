<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agence_id')->nullable()->constrained('agences')->nullOnDelete();

            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('password'); // mot de passe (hash) — convention Laravel
            $table->string('telephone')->nullable();

            // Spécialisation par rôle (héritage à table unique — Single Table Inheritance)
            $table->enum('role', [
                'administrateur',
                'gerant',
                'agent_credit',
                'agent_promotion',
                'caissier',
                'comptable',
            ]);

            // Attributs spécifiques à certains rôles (nullable selon le rôle)
            $table->decimal('seuil_validation', 12, 2)->nullable();   // Gerant
            $table->string('zone_tournee')->nullable();               // AgentPromotion

            $table->boolean('actif')->default(true);
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
