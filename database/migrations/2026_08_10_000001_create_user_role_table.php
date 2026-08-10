<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->timestamps();

            $table->primary(['user_id', 'role']);
            $table->index('role');
        });

        // Backfill : chaque utilisateur existant reçoit son rôle actuel.
        DB::table('users')->select(['id', 'role'])->orderBy('id')->each(function ($user) {
            DB::table('user_role')->insert([
                'user_id' => $user->id,
                'role' => $user->role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_role');
    }
};
