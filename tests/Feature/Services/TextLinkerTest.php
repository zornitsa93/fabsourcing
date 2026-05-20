<?php

namespace Tests\Feature\Services;

use App\Services\TextLinker;
use Tests\TestCase;

class TextLinkerTest extends TestCase
{
    public function test_links_phrase_in_plain_paragraph(): void
    {
        $html   = '<p>La sous-traitance industrielle permet de réduire les coûts.</p>';
        $result = TextLinker::linkify($html, 'fr');
        $this->assertMatchesRegularExpression(
            '/<a href="[^"]+">sous-traitance industrielle<\/a>/',
            $result
        );
    }

    public function test_links_only_first_occurrence(): void
    {
        $html   = '<p>La sous-traitance industrielle est clé. La sous-traitance industrielle réduit les coûts.</p>';
        $result = TextLinker::linkify($html, 'fr');
        $this->assertSame(1, substr_count($result, '<a href='));
    }

    public function test_does_not_nest_inside_existing_anchor(): void
    {
        $html   = '<p><a href="/other">sous-traitance industrielle</a> est notre domaine.</p>';
        $result = TextLinker::linkify($html, 'fr');
        // Should not introduce a second <a> element
        $this->assertSame(1, substr_count($result, '<a '));
    }

    public function test_returns_empty_string_unchanged(): void
    {
        $this->assertSame('', TextLinker::linkify('', 'fr'));
    }

    public function test_links_english_phrase(): void
    {
        $html   = '<p>Industrial subcontracting in Eastern Europe is cost effective.</p>';
        $result = TextLinker::linkify($html, 'en');
        $this->assertStringContainsString('<a href=', $result);
    }

    public function test_links_french_accented_phrase(): void
    {
        $html = '<p>Nous fabriquons des structures métalliques sur mesure.</p>';
        $result = TextLinker::linkify($html, 'fr');
        $this->assertStringContainsString('>structures métalliques<', $result);
        $this->assertMatchesRegularExpression('/<a href="[^"]+">structures m/', $result);
    }
}
