<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomeWhySectionSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('slug', 'home')->firstOrFail();

        $page->setTranslation('why_eyebrow', 'fr', "Pourquoi l'Europe de l'Est");
        $page->setTranslation('why_eyebrow', 'en', 'Why Eastern Europe');

        $page->setTranslation('why_heading', 'fr', 'Qualité européenne, coûts maîtrisés');
        $page->setTranslation('why_heading', 'en', 'European quality, controlled costs');

        $page->setTranslation('why_caption', 'fr', "Réseau d'usines partenaires");
        $page->setTranslation('why_caption', 'en', 'Partner workshop network');

        $page->why_metric1_value = '30–50%';
        $page->setTranslation('why_metric1_label', 'fr', 'd\'économies');
        $page->setTranslation('why_metric1_label', 'en', 'savings');

        $page->why_metric2_value = '3–4';
        $page->setTranslation('why_metric2_label', 'fr', 'jours vers la France');
        $page->setTranslation('why_metric2_label', 'en', 'days to France');

        $items = [
            1 => [
                'fr' => ['title' => 'Coûts réduits de 40 à 60 %',   'desc' => "Coûts de main-d'œuvre 40–60 % inférieurs à la France ou l'Allemagne, sans compromis sur la qualité."],
                'en' => ['title' => '40–60% lower costs',            'desc' => 'Labour costs 40–60% lower than in France or Germany, with no compromise on quality.'],
            ],
            2 => [
                'fr' => ['title' => 'Mêmes normes européennes',      'desc' => "Les ateliers partenaires respectent les mêmes exigences européennes que leurs homologues français : conformité CE, traçabilité, qualité et respect des délais."],
                'en' => ['title' => 'Same European standards',       'desc' => 'Partner workshops meet the same European requirements as their French counterparts: CE compliance, traceability, quality and on-time delivery.'],
            ],
            3 => [
                'fr' => ['title' => 'Logistique rapide',             'desc' => 'Transport optimisé vers la France en 3 à 4 jours par route. Accessibilité facilitée pour les contrôles qualité sur site.'],
                'en' => ['title' => 'Fast logistics',                'desc' => 'Optimised transport to France in 3–4 days by road. Easy access for on-site quality checks.'],
            ],
            4 => [
                'fr' => ['title' => 'Cadre réglementaire UE commun', 'desc' => "État membre de l'UE : propriété intellectuelle protégée, conformité CE, cadre juridique harmonisé avec la France."],
                'en' => ['title' => 'Shared EU regulatory framework', 'desc' => 'EU member state: IP protection, CE compliance, legal framework harmonised with France.'],
            ],
        ];

        foreach ($items as $n => $locales) {
            foreach (['fr', 'en'] as $lang) {
                $page->setTranslation("why_item{$n}_title", $lang, $locales[$lang]['title']);
                $page->setTranslation("why_item{$n}_desc",  $lang, $locales[$lang]['desc']);
            }
        }

        $page->save();
    }
}
