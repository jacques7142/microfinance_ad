<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societaire_id')->constrained('societaires')->cascadeOnDelete();

            $table->enum('type', ['sms', 'email']);
            $table->text('contenu');
            $table->dateTime('date_envoi')->nullable();
            $table->enum('statut_envoi', ['en_attente', 'envoyee', 'echouee'])->default('en_attente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
