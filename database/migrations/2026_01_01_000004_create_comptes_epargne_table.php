<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comptes_epargne', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societaire_id')->constrained('societaires')->cascadeOnDelete();

            // Héritage à table unique : DAV et DAT partagent la même table,
            // distingués par la colonne "type" (cf. diagramme de classes : CompteEpargne <|-- DAV, DAT)
            $table->enum('type', ['DAV', 'DAT']);
            $table->decimal('solde', 14, 2)->default(0);
            $table->date('date_ouverture');

            // Attributs spécifiques DAV
            $table->decimal('plafond_retrait_journalier', 12, 2)->nullable();

            // Attributs spécifiques DAT
            $table->date('date_echeance')->nullable();
            $table->decimal('taux_remuneration', 5, 2)->nullable();
            $table->integer('duree_blocage_mois')->nullable();

            $table->timestamps();

            $table->index(['societaire_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comptes_epargne');
    }
};
