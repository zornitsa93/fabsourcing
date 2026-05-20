<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;

class WebPagesController extends Controller
{
    protected function commonForWebPages(string $lang): array
    {
        $languages = cache()->remember('languages', Config::get('app.cache_duration'), function () {
            return Language::active()->get();
        })->reject(fn($l) => $l->slug === 'en');

        if (!$languages->contains('slug', $lang)) {
            $lang = $languages->first()?->slug ?? 'bg';
        }

        App::setLocale($lang);

        $page = Page::where('slug', Route::currentRouteName())->first();

        $mainMenu_pages = cache()->remember('mainMenuPages', Config::get('app.cache_duration'), function () {
            $menu = Menu::where('name', 'Main Menu')->first();
            return $menu?->pages()->get() ?? collect();
        });

        $footerMenu_pages = cache()->remember('footerMenuPages', Config::get('app.cache_duration'), function () {
            $menu = Menu::where('name', 'Footer Menu')->first();
            return $menu?->pages()->get() ?? collect();
        });

        // Build language-switcher URLs for simple routes (controllers override for parameterised routes)
        $currentRouteName = Route::currentRouteName();
        $langSwitcherUrls = [];
        foreach ($languages as $language) {
            try {
                $langSwitcherUrls[$language->slug] = route($currentRouteName, $language->slug);
            } catch (\Throwable) {
                $langSwitcherUrls[$language->slug] = route('home', $language->slug);
            }
        }

        return [
            'page'              => $page,
            'mainMenu_pages'    => $mainMenu_pages,
            'footerMenu_pages'  => $footerMenu_pages,
            'lang'              => $lang,
            'languages'         => $languages,
            'langSwitcherUrls'  => $langSwitcherUrls,
        ];
    }

    public function home(string $lang = 'bg')
    {
        return view('welcome', $this->commonForWebPages($lang));
    }

    public function contacts(string $lang = 'bg')
    {
        return view('contacts', $this->commonForWebPages($lang));
    }
}
