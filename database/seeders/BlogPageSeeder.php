<?php
namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class BlogPageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::firstOrNew(['slug' => 'blog']);
        if (!$page->exists) {
            $page->priority  = 7;
            $page->published = true;
            $page->setTranslation('title',            'fr', 'Blog');
            $page->setTranslation('title',            'en', 'Blog');
            $page->setTranslation('content',          'fr', '');
            $page->setTranslation('content',          'en', '');
            $page->setTranslation('meta_title',       'fr', 'Blog industriel — Fab Sourcing');
            $page->setTranslation('meta_title',       'en', 'Industrial Blog — Fab Sourcing');
            $page->setTranslation('meta_description', 'fr', 'Conseils, études de cas et actualités sur la sous-traitance industrielle en Europe de l\'Est.');
            $page->setTranslation('meta_description', 'en', 'Advice, case studies and news on industrial subcontracting in Eastern Europe.');
            $page->save();
        }
    }
}
