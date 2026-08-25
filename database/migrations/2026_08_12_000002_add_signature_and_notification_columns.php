<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('signature')->nullable()->after('corrigee');
            $table->dateTime('signe_le')->nullable()->after('signature');
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->text('signature_societaire')->nullable()->after('avis_agent');
            $table->dateTime('signe_le')->nullable()->after('signature_societaire');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('lu')->default(false)->after('statut_envoi');
            $table->string('lien')->nullable()->after('lu');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['signature', 'signe_le']);
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->dropColumn(['signature_societaire', 'signe_le']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['lu', 'lien']);
        });
    }
};
