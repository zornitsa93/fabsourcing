<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;

class WhyController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $langSwitcherUrls = [
            'fr' => route('why',    ['lang' => 'fr']),
            'en' => route('why.en', ['lang' => 'en']),
        ];

        return view('web.why', array_merge(
            $this->commonForWebPages($lang),
            compact('langSwitcherUrls')
        ));
    }
}
