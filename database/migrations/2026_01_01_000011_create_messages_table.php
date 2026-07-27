<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societaire_id')->constrained('societaires')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->nullable()->constrained('users')->nullOnDelete(); // agent ayant répondu

            $table->enum('expediteur', ['societaire', 'agence']);
            $table->text('contenu');
            $table->dateTime('date_envoi');
            $table->boolean('lu')->default(false);

            $table->timestamps();

            $table->index(['societaire_id', 'date_envoi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
