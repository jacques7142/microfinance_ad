<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journaux_activite', function (Blueprint $table) {
            $table->id();

            // Auteur de l'action : soit un utilisateur interne, soit un sociétaire connecté
            // en ligne. Les deux sont nullable et exclusifs (l'un des deux doit être renseigné,
            // règle à faire respecter côté application).
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('societaire_id')->nullable()->constrained('societaires')->nullOnDelete();

            $table->string('action'); // ex: "connexion", "validation_credit", "modification_parametre"
            $table->text('description')->nullable();
            $table->string('cible_type')->nullable(); // ex: "Credit", "Societaire" (nom du modèle concerné)
            $table->unsignedBigInteger('cible_id')->nullable(); // id de l'enregistrement concerné

            $table->string('adresse_ip', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('statut', ['succes', 'echec'])->default('succes');
            $table->dateTime('date_action');

            $table->timestamps();

            $table->index(['date_action']);
            $table->index(['cible_type', 'cible_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journaux_activite');
    }
};
