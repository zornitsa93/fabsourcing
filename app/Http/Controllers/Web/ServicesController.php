<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\Service;

class ServicesController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $services = Service::orderBy('sort_order')->get();
        return view('web.services', $this->commonForWebPages($lang) + compact('services'));
    }
}
