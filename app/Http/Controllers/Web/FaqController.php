<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;

class FaqController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        return view('web.faq', $this->commonForWebPages($lang));
    }
}
