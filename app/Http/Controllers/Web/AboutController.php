<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;

class AboutController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $langSwitcherUrls = [
            'fr' => route('about',    ['lang' => 'fr']),
            'en' => route('about.en', ['lang' => 'en']),
        ];

        return view('web.about', array_merge(
            $this->commonForWebPages($lang),
            compact('langSwitcherUrls')
        ));
    }
}
