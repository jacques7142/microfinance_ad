<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dashboards_se_renderent_pour_chaque_role(): void
    {
        $roles = ['administrateur', 'gerant', 'agent_credit', 'agent_promotion', 'caissier', 'comptable'];

        foreach ($roles as $role) {
            $user = User::where('role', $role)->first() ?? User::factory()->create(['role' => $role, 'agence_id' => User::first()->agence_id]);

            $response = $this->actingAs($user)->get('/dashboard');

            $response->assertOk();
        }
    }

    public function test_portail_societaire_se_rend(): void
    {
        $societaire = \App\Models\Societaire::first();
        $this->assertNotNull($societaire, 'Aucun sociétaire seedé');

        $response = $this->actingAs($societaire, 'societaire')->get('/espace-societaire');
        $response->assertOk();
    }
}