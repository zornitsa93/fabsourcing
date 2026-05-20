<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\Page;

class LegalController extends WebPagesController
{
    public function mentions(string $lang = 'fr')
    {
        $page = Page::where('slug', 'mentions-legales')->firstOrFail();

        $langSwitcherUrls = [
            'fr' => route('legal.mentions',    ['lang' => 'fr']),
            'en' => route('legal.mentions.en', ['lang' => 'en']),
        ];

        return view('web.legal', array_merge(
            $this->commonForWebPages($lang),
            compact('page', 'langSwitcherUrls')
        ));
    }

    public function privacy(string $lang = 'fr')
    {
        $page = Page::where('slug', 'politique-de-confidentialite')->firstOrFail();

        $langSwitcherUrls = [
            'fr' => route('legal.privacy',    ['lang' => 'fr']),
            'en' => route('legal.privacy.en', ['lang' => 'en']),
        ];

        return view('web.legal', array_merge(
            $this->commonForWebPages($lang),
            compact('page', 'langSwitcherUrls')
        ));
    }
}
