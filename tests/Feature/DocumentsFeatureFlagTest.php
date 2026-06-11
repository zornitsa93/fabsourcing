<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentsFeatureFlagTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_hidden_when_feature_disabled(): void
    {
        config(['documents.enabled' => false]);

        $this->get('/fr/inscription')->assertNotFound();
        $this->get('/fr/connexion')->assertNotFound();
        $this->get('/fr/documents')->assertNotFound();
    }

    public function test_public_pages_are_available_when_feature_enabled(): void
    {
        config(['documents.enabled' => true]);

        $this->get('/fr/inscription')->assertOk();
        $this->get('/fr/connexion')->assertOk();
    }
}
