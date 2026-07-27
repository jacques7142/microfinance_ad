<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->restrictOnDelete();
            // NULL = rapport consolidé multi-agences (ex. Administrateur)
            $table->foreignId('agence_id')->nullable()->constrained('agences')->nullOnDelete();

            $table->string('type_rapport');
            $table->string('periode');
            $table->enum('format_export', ['pdf', 'excel', 'csv']);
            $table->dateTime('date_generation');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
