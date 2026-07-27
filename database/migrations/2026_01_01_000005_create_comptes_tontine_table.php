<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comptes_tontine', function (Blueprint $table) {
            $table->id();
            // Association 1—0..1 : un sociétaire adhère à au plus un compte tontine LOGOKU
            $table->foreignId('societaire_id')->unique()->constrained('societaires')->cascadeOnDelete();

            $table->decimal('solde_accumule', 14, 2)->default(0);
            $table->decimal('mise_habituelle', 10, 2)->nullable();
            $table->string('zone_tournee')->nullable();
            $table->date('date_adhesion');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comptes_tontine');
    }
};
