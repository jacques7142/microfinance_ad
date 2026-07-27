<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agences', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('adresse');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('secteur')->nullable()->after('longitude');
            $table->string('telephone_agence')->nullable()->after('secteur');
            $table->text('description')->nullable()->after('telephone_agence');
            $table->json('horaires_fonctionnement')->nullable()->after('description');
            $table->boolean('actif')->default(true)->after('est_siege');
        });
    }

    public function down(): void
    {
        Schema::table('agences', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'secteur', 'telephone_agence', 'description', 'horaires_fonctionnement', 'actif']);
        });
    }
};
