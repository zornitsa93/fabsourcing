<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ServicesController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\WhyController;
use App\Http\Controllers\Web\MethodController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\BlogController;
use App\Http\Controllers\Web\LegalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Auth (accessible without adminauth middleware)
|--------------------------------------------------------------------------
*/
Route::get('admin/login',  'Auth\AdminAuthController@getLogin')->name('adminLogin');
Route::post('admin/login', 'Auth\AdminAuthController@postLogin')->name('adminLoginPost');
Route::get('admin/logout', 'Auth\AdminAuthController@logout')->name('adminLogout');

/*
|--------------------------------------------------------------------------
| Public site — /{lang}/...
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('home', 'fr');
})->name('root');

Route::group(['prefix' => '{lang}', 'middleware' => 'setlocale', 'where' => ['lang' => '[a-z]{2}']], function () {
    Route::get('/',               [HomeController::class,    'index'])->name('home');
    Route::get('/services',       [ServicesController::class, 'index'])->name('services');

    // Products (FR /produits, EN /products) — specific before generic
    Route::get('/produits/{categorySlug}/{productSlug}', [ProductsController::class, 'detail'])->name('products.detail');
    Route::get('/produits/{categorySlug}',               [ProductsController::class, 'category'])->name('products.category');
    Route::get('/produits',                              [ProductsController::class, 'index'])->name('products');
    Route::get('/products/{categorySlug}/{productSlug}', [ProductsController::class, 'detail'])->name('products.detail.en');
    Route::get('/products/{categorySlug}',               [ProductsController::class, 'category'])->name('products.category.en');
    Route::get('/products',                              [ProductsController::class, 'index'])->name('products.en');

    // Blog (same path both languages)
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog',        [BlogController::class, 'index'])->name('blog');

    // Why Eastern Europe (bilingual paths)
    Route::get('/pourquoi-europe-est',   [WhyController::class,    'index'])->name('why');
    Route::get('/why-eastern-europe',    [WhyController::class,    'index'])->name('why.en');

    // Methodology (bilingual paths)
    Route::get('/methodologie',          [MethodController::class, 'index'])->name('method');
    Route::get('/methodology',           [MethodController::class, 'index'])->name('method.en');

    // About (bilingual paths)
    Route::get('/a-propos',              [AboutController::class,  'index'])->name('about');
    Route::get('/about',                 [AboutController::class,  'index'])->name('about.en');

    // Contact
    Route::get('/contact',  [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    // Legal pages (bilingual paths)
    Route::get('/mentions-legales',          [LegalController::class, 'mentions'])->name('legal.mentions');
    Route::get('/legal-notice',              [LegalController::class, 'mentions'])->name('legal.mentions.en');
    Route::get('/politique-confidentialite', [LegalController::class, 'privacy'])->name('legal.privacy');
    Route::get('/privacy-policy',            [LegalController::class, 'privacy'])->name('legal.privacy.en');
});

/*
|--------------------------------------------------------------------------
| Sitemaps (no lang prefix)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Web\SitemapController;

Route::get('/sitemap.xml',    [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-fr.xml', [SitemapController::class, 'lang'])->name('sitemap.fr');
Route::get('/sitemap-en.xml', [SitemapController::class, 'lang'])->name('sitemap.en');
