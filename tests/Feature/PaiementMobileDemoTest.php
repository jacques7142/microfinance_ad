<?php

namespace Tests\Feature;

use App\Models\Agence;
use App\Models\CompteEpargne;
use App\Models\PaiementMobile;
use App\Models\Societaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PaiementMobileDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function makeContext(): array
    {
        $agence = Agence::create([
            'nom' => 'Agence Kodjoviakopé',
            'ville' => 'Lomé',
            'telephone' => '90000000',
            'adresse' => 'Kodjoviakopé, Lomé',
        ]);

        $societaire = Societaire::create([
            'agence_id' => $agence->id,
            'numero_societaire' => 'COOP-26-00001',
            'nom' => 'ADJOVI',
            'prenom' => 'Koffi',
            'telephone' => '90000001',
            'adresse' => 'Kodjoviakopé, Lomé',
            'date_adhesion' => now()->subMonths(3),
            'part_sociale' => 5000,
            'droit_adhesion' => 2000,
            'statut' => 'actif',
            'password' => Hash::make('coopecad2026'),
        ]);

        $compte = CompteEpargne::create([
            'societaire_id' => $societaire->id,
            'type' => CompteEpargne::TYPE_DAV,
            'solde' => 50000,
            'date_ouverture' => now(),
            'plafond_retrait_journalier' => 500000,
        ]);

        return [$societaire, $compte];
    }

    public function test_depot_en_mode_demo_est_confirme_immediatement(): void
    {
        config(['services.ligdicash.demo' => true]);

        [$societaire, $compte] = $this->makeContext();

        $response = $this->actingAs($societaire, 'societaire')->post('/depot', [
            'compte_epargne_id' => $compte->id,
            'montant' => 25000,
            'operateur' => 'yas',
            'telephone' => '90000001',
        ]);

        $paiement = PaiementMobile::where('societaire_id', $societaire->id)->firstOrFail();

        $response->assertRedirect(route('societaire.paiement.statut', $paiement));
        $this->assertSame(PaiementMobile::STATUT_COMPLETED, $paiement->statut);
        $this->assertStringStartsWith('demo-', $paiement->token);

        $compte->refresh();
        $this->assertEquals(75000, (float) $compte->solde);
    }

    public function test_retrait_en_mode_demo_debite_le_compte(): void
    {
        config(['services.ligdicash.demo' => true]);

        [$societaire, $compte] = $this->makeContext();

        $response = $this->actingAs($societaire, 'societaire')->post('/retrait', [
            'compte_epargne_id' => $compte->id,
            'montant' => 10000,
            'operateur' => 'flooz',
            'telephone' => '90000001',
        ]);

        $paiement = PaiementMobile::where('societaire_id', $societaire->id)->firstOrFail();

        $response->assertRedirect(route('societaire.paiement.statut', $paiement));
        $this->assertSame(PaiementMobile::STATUT_COMPLETED, $paiement->statut);

        $compte->refresh();
        $this->assertEquals(40000, (float) $compte->solde);
    }

    public function test_mode_demo_est_automatique_sans_identifiants_api(): void
    {
        config(['services.ligdicash.demo' => null]);
        config(['services.ligdicash.api_key' => null]);
        config(['services.ligdicash.auth_token' => null]);

        $this->assertTrue(app(\App\Services\LigdiCashService::class)->estEnModeDemo());
    }
}
