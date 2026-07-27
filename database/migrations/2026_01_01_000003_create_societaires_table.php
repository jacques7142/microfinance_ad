<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('societaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agence_id')->constrained('agences')->restrictOnDelete();

            $table->string('numero_societaire')->unique();
            
            // On réduit la longueur (recommandé pour les noms)
            $table->string('nom', 100);
            $table->string('prenom', 100);
            
            $table->string('telephone')->unique();
            $table->string('adresse')->nullable();
            $table->date('date_adhesion');
            $table->decimal('part_sociale', 12, 2)->default(0);
            $table->decimal('droit_adhesion', 12, 2)->default(0);
            $table->enum('statut', ['actif', 'suspendu', 'archive'])->default('actif');

            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();

            $table->timestamps();

            // Index corrigé
            $table->index(['nom', 'prenom']);           // Laravel va prendre les 100 premiers caractères
            // Ou version explicite :
            // $table->index(['nom(100)', 'prenom(100)']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('societaires');
    }
};