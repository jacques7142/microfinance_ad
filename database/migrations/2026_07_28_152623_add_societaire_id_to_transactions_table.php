<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('societaire_id')->nullable()->after('utilisateur_id')->constrained('societaires')->nullOnDelete();
            $table->foreignId('utilisateur_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['societaire_id']);
            $table->dropColumn('societaire_id');
            $table->foreignId('utilisateur_id')->nullable(false)->change();
        });
    }
};