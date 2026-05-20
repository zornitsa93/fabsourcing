<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocalizationController extends Controller {

    public function index($locale): \Illuminate\Http\RedirectResponse
    {

        App::setlocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }

    public function translateJson ($json, $lang)
    {	
        $obj = json_decode($json);
        return $obj->$lang;
    }
}
