<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societaire_id')->constrained('societaires')->cascadeOnDelete();

            $table->enum('type_piece', [
                'cni', 'passeport', 'justificatif_domicile', 'photo_identite',
                'bulletin_salaire', 'registre_commerce', 'autre',
            ]);
            $table->string('chemin_fichier'); // stockage local ou cloud (S3, etc.)
            $table->string('nom_original')->nullable();

            $table->enum('statut_verification', ['en_attente', 'valide', 'rejete', 'expire'])->default('en_attente');
            $table->foreignId('verifie_par')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_verification')->nullable();
            $table->text('motif_rejet')->nullable();

            $table->date('date_expiration')->nullable(); // ex: validité CNI
            $table->dateTime('date_televersement');

            $table->timestamps();

            $table->index(['societaire_id', 'type_piece']);
            $table->index('statut_verification');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
