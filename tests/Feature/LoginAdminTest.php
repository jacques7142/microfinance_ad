<?php

namespace Tests\Feature;

use App\Models\Agence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_login_with_default_credentials(): void
    {
        $this->seed(\Database\Seeders\AgenceSeeder::class);
        $this->seed(\Database\Seeders\UserSeeder::class);

        $response = $this->post('/login', [
            'identifier' => 'admin@coopec-ad.tg',
            'password' => 'coopecad2026',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs(User::where('email', 'admin@coopec-ad.tg')->first());
    }
}
