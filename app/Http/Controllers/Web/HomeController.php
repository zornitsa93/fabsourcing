<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\BlogPost;
use App\Models\ProductCategory;
use App\Models\Service;

class HomeController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $services = Service::where('published', true)->orderBy('sort_order')->get();

        $featuredCategories = ProductCategory::featured()->take(6)->get();

        $recentPosts = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('web.home', $this->commonForWebPages($lang) + compact(
            'services', 'featuredCategories', 'recentPosts'
        ));
    }
}
