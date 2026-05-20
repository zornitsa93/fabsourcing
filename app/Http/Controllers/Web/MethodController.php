<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\MethodStep;

class MethodController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $steps = MethodStep::orderBy('sort_order')->get();

        $langSwitcherUrls = [
            'fr' => route('method',    ['lang' => 'fr']),
            'en' => route('method.en', ['lang' => 'en']),
        ];

        return view('web.method', array_merge(
            $this->commonForWebPages($lang),
            compact('steps', 'langSwitcherUrls')
        ));
    }
}
