<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_index_returns_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $this->assertStringContainsString('<sitemapindex', $response->getContent());
        $this->assertStringContainsString('sitemap-fr.xml', $response->getContent());
        $this->assertStringContainsString('sitemap-en.xml', $response->getContent());
    }

    public function test_fr_sitemap_contains_home_and_blog(): void
    {
        $response = $this->get('/sitemap-fr.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString('<urlset', $response->getContent());
        $this->assertStringContainsString(route('home', 'fr'), $response->getContent());
        $this->assertStringContainsString(route('blog', 'fr'), $response->getContent());
    }

    public function test_en_sitemap_contains_home_url(): void
    {
        $response = $this->get('/sitemap-en.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString(route('home', 'en'), $response->getContent());
    }
}
