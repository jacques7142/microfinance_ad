<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo_profil')->nullable()->after('role');
            $table->text('bio')->nullable()->after('photo_profil');
            $table->timestamp('derniere_modification_profil')->nullable()->after('bio');
        });

        Schema::table('societaires', function (Blueprint $table) {
            $table->string('photo_profil')->nullable()->after('telephone');
            $table->text('bio')->nullable()->after('photo_profil');
            $table->timestamp('derniere_modification_profil')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['photo_profil', 'bio', 'derniere_modification_profil']);
        });

        Schema::table('societaires', function (Blueprint $table) {
            $table->dropColumn(['photo_profil', 'bio', 'derniere_modification_profil']);
        });
    }
};
