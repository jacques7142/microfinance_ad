<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalRenderSanityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_portail_rendu_sans_css_brut(): void
    {
        $societaire = \App\Models\Societaire::first();
        $this->assertNotNull($societaire);

        $html = $this->actingAs($societaire, 'societaire')->get('/espace-societaire')->getContent();

        $this->assertStringContainsString('<style>', $html);
        $this->assertStringContainsString('class="hero"', $html);
        $this->assertStringContainsString('pm-logo pm-yas', $html);
        $this->assertStringContainsString('pm-logo pm-flooz', $html);
        $this->assertStringContainsString('Mixx by Yas', $html);

        $horsStyle = preg_replace('/<style\b[^>]*>.*?<\/style>/s', '', $html);
        $this->assertStringNotContainsString('.hero{display:grid', $horsStyle);
        $this->assertStringNotContainsString('.ops-tabs{display:flex', $horsStyle);
    }

    public function test_pages_operations_utilisent_composant(): void
    {
        $societaire = \App\Models\Societaire::first();

        foreach (['/depot', '/retrait', '/remboursement'] as $path) {
            $html = $this->actingAs($societaire, 'societaire')->get($path)->getContent();
            $this->assertStringContainsString('pay-method-card', $html);
            $this->assertStringNotContainsString('lbl-yas', $html);
        }
    }
}