<?php

namespace App\Services;

class TextLinker
{
    public static function linkify(string $html, string $lang): string
    {
        if (trim($html) === '') {
            return '';
        }

        $map    = static::phraseMap($lang);
        $linked = [];

        $doc = new \DOMDocument();
        $prevLibxmlErrors = libxml_use_internal_errors(true);
        $doc->loadHTML(
            '<html><head><meta charset="utf-8"></head><body><div id="tl-root">' . $html . '</div></body></html>',
            LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($prevLibxmlErrors);

        $xpath     = new \DOMXPath($doc);
        $textNodes = $xpath->query('//div[@id="tl-root"]//text()[not(ancestor::a)]');

        foreach ($textNodes as $node) {
            $text = $node->nodeValue;

            foreach ($map as $phrase => $url) {
                if (isset($linked[$phrase])) {
                    continue;
                }

                $pos = mb_stripos($text, $phrase, 0, 'UTF-8');
                if ($pos === false) {
                    continue;
                }

                $before = mb_substr($text, 0, $pos, 'UTF-8');
                $match  = mb_substr($text, $pos, mb_strlen($phrase, 'UTF-8'), 'UTF-8');
                $after  = mb_substr($text, $pos + mb_strlen($phrase, 'UTF-8'), null, 'UTF-8');
                $parent = $node->parentNode;

                if ($before !== '') {
                    $parent->insertBefore($doc->createTextNode($before), $node);
                }

                $a = $doc->createElement('a');
                $a->setAttribute('href', $url);
                $a->appendChild($doc->createTextNode($match));
                $parent->insertBefore($a, $node);

                $node->nodeValue = $after;
                $text            = $after;

                $linked[$phrase] = true;
            }
        }

        $root = $doc->getElementById('tl-root');
        if ($root === null) {
            return $html;
        }
        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $doc->saveHTML($child);
        }

        return $result;
    }

    private static function phraseMap(string $lang): array
    {
        if ($lang === 'en') {
            return [
                'industrial subcontracting'  => route('why.en', ['lang' => 'en']),
                'Eastern Europe'             => route('why.en', ['lang' => 'en']),
                'Bulgaria'                   => route('why.en', ['lang' => 'en']),
                'metal structures'           => route('products.category.en', ['lang' => 'en', 'categorySlug' => 'steel-structure-metalwork']),
                'metal stairs'               => route('products.category.en', ['lang' => 'en', 'categorySlug' => 'custom-metal-stairs']),
                'railings'                   => route('products.category.en', ['lang' => 'en', 'categorySlug' => 'stainless-steel-railings-handrails']),
                'outsourcing'                => route('why.en', ['lang' => 'en']),
                'our methodology'            => route('method.en', ['lang' => 'en']),
            ];
        }

        return [
            'sous-traitance industrielle'  => route('why', ['lang' => 'fr']),
            "Europe de l'Est"              => route('why', ['lang' => 'fr']),
            'Bulgarie'                     => route('why', ['lang' => 'fr']),
            'structures métalliques'       => route('products.category', ['lang' => 'fr', 'categorySlug' => 'charpente-structures-metalliques-acier']),
            'escaliers métalliques'        => route('products.category', ['lang' => 'fr', 'categorySlug' => 'escaliers-metalliques-sur-mesure']),
            'garde-corps'                  => route('products.category', ['lang' => 'fr', 'categorySlug' => 'garde-corps-rampes-inox-acier']),
            'externalisation'              => route('why', ['lang' => 'fr']),
            'notre méthodologie'           => route('method', ['lang' => 'fr']),
        ];
    }
}
