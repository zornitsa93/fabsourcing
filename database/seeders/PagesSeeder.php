<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'     => 'home',
                'priority' => 1,
                'title'    => ['fr' => 'Accueil',                       'en' => 'Home'],
            ],
            [
                'slug'     => 'services',
                'priority' => 2,
                'title'    => ['fr' => 'Services',                       'en' => 'Services'],
            ],
            [
                'slug'     => 'why-eastern-europe',
                'priority' => 3,
                'title'    => ['fr' => "Pourquoi l'Est de l'Europe",     'en' => 'Why Eastern Europe'],
            ],
            [
                'slug'     => 'methodology',
                'priority' => 4,
                'title'    => ['fr' => 'Méthodologie',                   'en' => 'Methodology'],
            ],
            [
                'slug'     => 'about',
                'priority' => 5,
                'title'    => ['fr' => 'À propos',                       'en' => 'About'],
            ],
            [
                'slug'     => 'contact',
                'priority' => 6,
                'title'    => ['fr' => 'Contact',                        'en' => 'Contact'],
            ],
        ];

        foreach ($pages as $data) {
            $page = Page::firstOrNew(['slug' => $data['slug']]);

            if (!$page->exists) {
                $page->priority  = $data['priority'];
                $page->published = false;

                foreach (['fr', 'en'] as $locale) {
                    $page->setTranslation('title',   $locale, $data['title'][$locale]);
                    $page->setTranslation('content', $locale, '');
                }

                $page->save();
            } else {
                $changed = false;
                foreach (['fr', 'en'] as $locale) {
                    if (!$page->getTranslation('title', $locale, false)) {
                        $page->setTranslation('title', $locale, $data['title'][$locale]);
                        $changed = true;
                    }
                }
                if ($changed) {
                    $page->save();
                }
            }
        }
    }
}
