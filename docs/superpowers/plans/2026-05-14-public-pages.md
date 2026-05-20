# Public Pages Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build all remaining public-facing pages: Blog index + detail, Why Eastern Europe, Methodology, About, and Legal stub pages.

**Architecture:** Each page is a Laravel controller extending `WebPagesController` (for `commonForWebPages()`), rendering a Blade view in `resources/views/web/`. Routes live in `routes/web.php` under the `{lang}` prefix group. Bilingual URL paths (FR `/pourquoi-europe-est`, EN `/why-eastern-europe`) follow the same two-route pattern used by Products. CSS is added to existing SCSS partials and compiled via `npm run dev`.

**Tech Stack:** Laravel 12 / PHP 8.2, Blade, spatie/laravel-translatable, MySQL JSON columns, Laravel Mix 6 (webpack SCSS), vanilla JS, no extra packages.

---

## File Map

**New files:**
- `app/Http/Controllers/Web/BlogController.php`
- `app/Http/Controllers/Web/LegalController.php`
- `resources/views/web/blog/index.blade.php`
- `resources/views/web/blog/show.blade.php`
- `resources/views/web/legal.blade.php`
- `resources/views/vendor/pagination/simple-web.blade.php`
- `database/seeders/BlogPageSeeder.php`
- `database/seeders/MethodStepsSeeder.php`

**Modified files:**
- `routes/web.php` — add blog, why.en, method.en, about.en, legal routes; update why/method FR URLs
- `resources/views/partials/nav.blade.php` — add Blog link, bilingual route support
- `resources/views/partials/footer.blade.php` — legal links → proper routes
- `resources/views/web/home.blade.php` — fix "Voir tous les articles" link → blog route
- `app/Http/Controllers/Web/WhyController.php` — pass advantages data + langSwitcherUrls
- `app/Http/Controllers/Web/MethodController.php` — pass langSwitcherUrls
- `app/Http/Controllers/Web/AboutController.php` — pass team/mission data + langSwitcherUrls
- `resources/views/web/why.blade.php` — full implementation
- `resources/views/web/method.blade.php` — full implementation
- `resources/views/web/about.blade.php` — full implementation
- `resources/sass/_sections.scss` — blog article, advantage, commitment, person CSS
- `resources/sass/_utils.scss` — pagination, tag cloud CSS
- `database/seeders/DatabaseSeeder.php` — register new seeders

---

## Task 1: Seed missing data (blog page + method steps)

**Files:**
- Create: `database/seeders/BlogPageSeeder.php`
- Create: `database/seeders/MethodStepsSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Create BlogPageSeeder**

```php
<?php
// database/seeders/BlogPageSeeder.php
namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class BlogPageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::firstOrNew(['slug' => 'blog']);
        if (!$page->exists) {
            $page->priority  = 7;
            $page->published = true;
            $page->setTranslation('title',            'fr', 'Blog');
            $page->setTranslation('title',            'en', 'Blog');
            $page->setTranslation('content',          'fr', '');
            $page->setTranslation('content',          'en', '');
            $page->setTranslation('meta_title',       'fr', 'Blog industriel — Fab Sourcing');
            $page->setTranslation('meta_title',       'en', 'Industrial Blog — Fab Sourcing');
            $page->setTranslation('meta_description', 'fr', 'Conseils, études de cas et actualités sur la sous-traitance industrielle en Europe de l\'Est.');
            $page->setTranslation('meta_description', 'en', 'Advice, case studies and news on industrial subcontracting in Eastern Europe.');
            $page->save();
        }
    }
}
```

- [ ] **Step 2: Create MethodStepsSeeder**

```php
<?php
// database/seeders/MethodStepsSeeder.php
namespace Database\Seeders;

use App\Models\MethodStep;
use Illuminate\Database\Seeder;

class MethodStepsSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'number'     => '01',
                'sort_order' => 1,
                'title'      => ['fr' => 'Analyse du besoin',        'en' => 'Needs analysis'],
                'description'=> ['fr' => 'Étude de vos plans, cahier des charges et contraintes techniques. Premier contact téléphonique ou email pour cadrer le projet.', 'en' => 'Review of your drawings, specifications and technical constraints. First phone or email contact to frame the project.'],
            ],
            [
                'number'     => '02',
                'sort_order' => 2,
                'title'      => ['fr' => 'Étude technique',          'en' => 'Technical study'],
                'description'=> ['fr' => 'Analyse de faisabilité, identification des procédés adaptés (soudage, découpe, traitement de surface) et sélection préliminaire des ateliers.', 'en' => 'Feasibility analysis, identification of suitable processes (welding, cutting, surface treatment) and preliminary workshop selection.'],
            ],
            [
                'number'     => '03',
                'sort_order' => 3,
                'title'      => ['fr' => 'Sélection fournisseur',    'en' => 'Supplier selection'],
                'description'=> ['fr' => 'Consultation de notre réseau d\'ateliers certifiés. Comparatif technique et tarifaire. Envoi du devis consolidé sous 48 h.', 'en' => 'Consultation of our certified workshop network. Technical and price comparison. Consolidated quote sent within 48 h.'],
            ],
            [
                'number'     => '04',
                'sort_order' => 4,
                'title'      => ['fr' => 'Prototype / pré-série',    'en' => 'Prototype / pre-series'],
                'description'=> ['fr' => 'Fabrication d\'une ou plusieurs pièces de validation. Rapport photos et contrôle dimensionnel avant approbation client.', 'en' => 'Manufacture of one or more validation parts. Photo report and dimensional check before client approval.'],
            ],
            [
                'number'     => '05',
                'sort_order' => 5,
                'title'      => ['fr' => 'Production',               'en' => 'Production'],
                'description'=> ['fr' => 'Lancement série après approbation. Suivi hebdomadaire d\'avancement avec notre responsable qualité sur site.', 'en' => 'Series launch after approval. Weekly progress monitoring with our on-site quality manager.'],
            ],
            [
                'number'     => '06',
                'sort_order' => 6,
                'title'      => ['fr' => 'Contrôle qualité',         'en' => 'Quality control'],
                'description'=> ['fr' => 'Inspection finale en atelier : contrôle dimensionnel, visuel et documentaire. Rédaction du rapport de conformité avant expédition.', 'en' => 'Final workshop inspection: dimensional, visual and documentary control. Compliance report issued before shipment.'],
            ],
            [
                'number'     => '07',
                'sort_order' => 7,
                'title'      => ['fr' => 'Livraison',                'en' => 'Delivery'],
                'description'=> ['fr' => 'Organisation du transport (FCA, DAP ou DDP selon accord). Suivi douanier et coordination avec votre service réception.', 'en' => 'Transport arrangement (FCA, DAP or DDP as agreed). Customs monitoring and coordination with your receiving department.'],
            ],
        ];

        foreach ($steps as $data) {
            $step = MethodStep::firstOrNew(['number' => $data['number']]);
            $step->sort_order = $data['sort_order'];
            foreach (['fr', 'en'] as $locale) {
                $step->setTranslation('title',       $locale, $data['title'][$locale]);
                $step->setTranslation('description', $locale, $data['description'][$locale]);
            }
            $step->save();
        }
    }
}
```

- [ ] **Step 3: Register in DatabaseSeeder**

Open `database/seeders/DatabaseSeeder.php` and add the two new seeders to the `run()` method:

```php
$this->call([
    // ... existing seeders ...
    BlogPageSeeder::class,
    MethodStepsSeeder::class,
]);
```

- [ ] **Step 4: Run the seeders**

```bash
cd /Users/zornitsamarinova/code/fabsourcing
php artisan db:seed --class=BlogPageSeeder
php artisan db:seed --class=MethodStepsSeeder
```

Expected output: `INFO  Seeding database.` (twice, no errors)

- [ ] **Step 5: Verify**

```bash
mysql -u root fabsourcing -e "SELECT slug, title FROM pages WHERE slug='blog';"
mysql -u root fabsourcing -e "SELECT number, title FROM method_steps ORDER BY sort_order;"
```

Expected: blog page row + 7 method step rows.

---

## Task 2: Routes + Nav + Footer

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/partials/nav.blade.php`
- Modify: `resources/views/partials/footer.blade.php`
- Modify: `resources/views/web/home.blade.php`

- [ ] **Step 1: Update routes/web.php**

Replace the existing `{lang}` group in `routes/web.php` with the full updated version:

```php
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

Route::get('admin/login',  'Auth\AdminAuthController@getLogin')->name('adminLogin');
Route::post('admin/login', 'Auth\AdminAuthController@postLogin')->name('adminLoginPost');
Route::get('admin/logout', 'Auth\AdminAuthController@logout')->name('adminLogout');

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
```

- [ ] **Step 2: Update nav.blade.php**

Replace the `$navLinks` array and the `@foreach` loop with bilingual-route-aware logic:

```blade
{{-- Navigation — sticky glassmorphism nav --}}
<nav class="nav">
  <div class="nav-inner">

    {{-- Brand logo --}}
    <a href="{{ route('home', $lang) }}" class="brand">
      <img class="brand-logo" src="{{ asset('images/logo-fab-full.png') }}" alt="Fab Sourcing" />
    </a>

    {{-- Desktop nav links --}}
    <div class="nav-links">
      @php
        $currentRoute = Route::currentRouteName();
        $navLinks = [
          ['route' => 'home',     'route_en' => null,         'label' => $lang === 'fr' ? 'Accueil'        : 'Home'],
          ['route' => 'services', 'route_en' => null,         'label' => $lang === 'fr' ? 'Services'       : 'Services'],
          ['route' => 'products', 'route_en' => 'products.en','label' => $lang === 'fr' ? 'Produits'       : 'Products'],
          ['route' => 'why',      'route_en' => 'why.en',     'label' => $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU'],
          ['route' => 'method',   'route_en' => 'method.en',  'label' => $lang === 'fr' ? 'Méthode'        : 'Method'],
          ['route' => 'blog',     'route_en' => null,         'label' => 'Blog'],
          ['route' => 'about',    'route_en' => 'about.en',   'label' => $lang === 'fr' ? 'À propos'       : 'About'],
          ['route' => 'contact',  'route_en' => null,         'label' => 'Contact'],
        ];
      @endphp

      @foreach($navLinks as $link)
        @php
          $routeName = ($lang === 'en' && $link['route_en']) ? $link['route_en'] : $link['route'];
          $href      = route($routeName, $lang);
          $isActive  = $currentRoute === $link['route'] || $currentRoute === $link['route_en'];
        @endphp
        <a href="{{ $href }}" class="nav-link {{ $isActive ? 'active' : '' }}">
          {{ $link['label'] }}
        </a>
      @endforeach
    </div>

    {{-- Right: phone + language toggle + CTA --}}
    <div class="nav-right">
      <a href="tel:+33782085117" class="nav-phone">+33 (0)7 82 08 51 17</a>

      <div class="lang-toggle">
        @foreach($languages as $language)
          @php
            $switchUrl = $langSwitcherUrls[$language->slug] ?? route('home', $language->slug);
          @endphp
          <a href="{{ $switchUrl }}" class="{{ $language->slug === $lang ? 'active' : '' }}">
            {{ strtoupper($language->slug) }}
          </a>
        @endforeach
      </div>

      <a href="{{ route('contact', $lang) }}" class="btn btn-primary">
        {{ $lang === 'fr' ? 'Devis gratuit' : 'Free quote' }}
        <span class="arrow">→</span>
      </a>

      {{-- Mobile hamburger --}}
      <button class="mobile-menu-btn" id="nav-toggle" aria-label="Menu" aria-expanded="false">
        <svg class="ham-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
          <rect class="ham-line ham-line-1" x="2" y="4" width="16" height="2" rx="1" fill="currentColor"/>
          <rect class="ham-line ham-line-2" x="2" y="9" width="16" height="2" rx="1" fill="currentColor"/>
          <rect class="ham-line ham-line-3" x="2" y="14" width="16" height="2" rx="1" fill="currentColor"/>
        </svg>
      </button>
    </div>

  </div>
</nav>

{{-- Mobile drawer overlay --}}
<div class="nav-drawer-overlay" id="nav-overlay" aria-hidden="true"></div>

{{-- Mobile drawer --}}
<div class="nav-mobile-drawer" id="nav-drawer" role="dialog" aria-label="Navigation" aria-modal="true">
  <div class="nav-drawer-header">
    <img src="{{ asset('images/logo-fab-full.png') }}" alt="Fab Sourcing" style="height:32px; width:auto" />
    <button class="nav-drawer-close" id="nav-close" aria-label="{{ $lang === 'fr' ? 'Fermer' : 'Close' }}">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  <nav class="nav-drawer-links">
    @foreach($navLinks as $link)
      @php
        $routeName = ($lang === 'en' && $link['route_en']) ? $link['route_en'] : $link['route'];
        $href      = route($routeName, $lang);
        $isActive  = $currentRoute === $link['route'] || $currentRoute === $link['route_en'];
      @endphp
      <a href="{{ $href }}" class="nav-drawer-link {{ $isActive ? 'active' : '' }}">
        {{ $link['label'] }}
      </a>
    @endforeach
  </nav>

  <div class="nav-drawer-footer">
    <a href="tel:+33782085117" class="nav-drawer-phone">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
      </svg>
      +33 (0)7 82 08 51 17
    </a>

    <div class="lang-toggle" style="margin-top:16px">
      @foreach($languages as $language)
        @php $switchUrl = $langSwitcherUrls[$language->slug] ?? route('home', $language->slug); @endphp
        <a href="{{ $switchUrl }}" class="{{ $language->slug === $lang ? 'active' : '' }}">
          {{ strtoupper($language->slug) }}
        </a>
      @endforeach
    </div>
  </div>
</div>

<script>
(function () {
  var toggle  = document.getElementById('nav-toggle');
  var close   = document.getElementById('nav-close');
  var overlay = document.getElementById('nav-overlay');
  var drawer  = document.getElementById('nav-drawer');

  function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', openDrawer);
  close.addEventListener('click', closeDrawer);
  overlay.addEventListener('click', closeDrawer);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDrawer();
  });
})();
</script>
```

- [ ] **Step 3: Update footer.blade.php legal links**

Find the two `<a href="{{ route('home', $lang) }}?p=...">` links at the bottom of the footer and replace them with proper route links:

```blade
<a href="{{ route($lang === 'en' ? 'legal.mentions.en' : 'legal.mentions', $lang) }}"
   style="color:rgba(255,255,255,0.5); font-family:inherit; font-size:inherit; letter-spacing:inherit; text-decoration:none; transition:color 0.15s"
   onmouseover="this.style.color='rgba(255,255,255,0.85)'"
   onmouseout="this.style.color='rgba(255,255,255,0.5)'">
  {{ $lang === 'fr' ? 'Mentions légales' : 'Legal notice' }}
</a>
<a href="{{ route($lang === 'en' ? 'legal.privacy.en' : 'legal.privacy', $lang) }}"
   style="color:rgba(255,255,255,0.5); font-family:inherit; font-size:inherit; letter-spacing:inherit; text-decoration:none; transition:color 0.15s"
   onmouseover="this.style.color='rgba(255,255,255,0.85)'"
   onmouseout="this.style.color='rgba(255,255,255,0.5)'">
  {{ $lang === 'fr' ? 'Confidentialité' : 'Privacy' }}
</a>
```

- [ ] **Step 4: Fix "Voir tous les articles" link in home.blade.php**

Find `route('why', $lang)` in the blog section and change it to `route('blog', $lang)`:

```blade
<a href="{{ route('blog', $lang) }}" class="btn-link" style="display:inline-flex; align-items:center; gap:8px">
  {{ $lang === 'fr' ? 'Voir tous les articles' : 'View all articles' }}
  <span class="arrow">→</span>
</a>
```

- [ ] **Step 5: Verify routes compile**

```bash
php artisan route:list --path=fr 2>&1 | grep -E "blog|why|method|about|legal|mentions|privacy"
```

Expected: 14+ route lines covering blog, why, why.en, method, method.en, about, about.en, legal.mentions, legal.mentions.en, legal.privacy, legal.privacy.en.

---

## Task 3: BlogController

**Files:**
- Create: `app/Http/Controllers/Web/BlogController.php`

- [ ] **Step 1: Create BlogController**

```php
<?php
// app/Http/Controllers/Web/BlogController.php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\BlogPost;
use App\Models\Page;
use Illuminate\Http\Request;

class BlogController extends WebPagesController
{
    public function index(Request $request, string $lang = 'fr')
    {
        $search = $request->input('search', '');
        $tag    = $request->input('tag', '');

        $query = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($tag) {
            $query->where('tags', 'like', '%' . addslashes($tag) . '%');
        }

        $posts = $query->paginate(10)->withQueryString();

        // Build tag cloud from ALL published posts
        $allTags = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->pluck('tags')
            ->flatMap(function ($tagsJson) use ($lang) {
                if (is_array($tagsJson)) {
                    return $tagsJson[$lang] ?? $tagsJson['fr'] ?? [];
                }
                $decoded = json_decode($tagsJson, true);
                return $decoded[$lang] ?? $decoded['fr'] ?? [];
            })
            ->filter()
            ->countBy()
            ->sortByDesc(fn ($count) => $count)
            ->take(20)
            ->keys()
            ->all();

        $blogPage = Page::where('slug', 'blog')->first();

        $langSwitcherUrls = [
            'fr' => route('blog', 'fr') . ($search ? '?search=' . urlencode($search) : ''),
            'en' => route('blog', 'en') . ($search ? '?search=' . urlencode($search) : ''),
        ];

        return view('web.blog.index', array_merge(
            $this->commonForWebPages($lang),
            compact('posts', 'allTags', 'search', 'tag', 'blogPage', 'langSwitcherUrls')
        ));
    }

    public function show(string $lang, string $slug)
    {
        $post = BlogPost::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('slug', $slug)
            ->firstOrFail();

        // 3 related posts sharing at least one tag
        $tags = $post->getTranslation('tags', $lang, false)
              ?: $post->getTranslation('tags', 'fr', false)
              ?: [];

        $related = collect();
        if (!empty($tags)) {
            $firstTag = $tags[0];
            $related = BlogPost::whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->where('id', '!=', $post->id)
                ->where('tags', 'like', '%' . addslashes($firstTag) . '%')
                ->orderByDesc('published_at')
                ->take(3)
                ->get();
        }
        if ($related->count() < 3) {
            $related = $related->merge(
                BlogPost::whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->where('id', '!=', $post->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->orderByDesc('published_at')
                    ->take(3 - $related->count())
                    ->get()
            );
        }

        $langSwitcherUrls = [
            'fr' => route('blog.show', ['lang' => 'fr', 'slug' => $slug]),
            'en' => route('blog.show', ['lang' => 'en', 'slug' => $slug]),
        ];

        return view('web.blog.show', array_merge(
            $this->commonForWebPages($lang),
            compact('post', 'related', 'langSwitcherUrls')
        ));
    }
}
```

- [ ] **Step 2: Verify no syntax error**

```bash
php artisan route:list --name=blog 2>&1 | head -5
```

Expected: blog and blog.show routes listed with no exception.

---

## Task 4: Blog index view

**Files:**
- Create: `resources/views/web/blog/index.blade.php`

- [ ] **Step 1: Create the view**

```blade
{{-- resources/views/web/blog/index.blade.php --}}
@extends('layouts.web')

@php
  $metaTitle = $blogPage?->getTranslation('meta_title', $lang, false)
             ?: ($lang === 'fr' ? 'Blog industriel — Fab Sourcing' : 'Industrial Blog — Fab Sourcing');
  $metaDesc  = $blogPage?->getTranslation('meta_description', $lang, false)
             ?: ($lang === 'fr' ? 'Conseils et actualités sur la sous-traitance industrielle en Europe de l\'Est.' : 'Advice and news on industrial subcontracting in Eastern Europe.');
@endphp

@section('title', $metaTitle)
@section('description', $metaDesc)

@push('head')
  <link rel="alternate" hreflang="fr" href="{{ $langSwitcherUrls['fr'] ?? '' }}">
  <link rel="alternate" hreflang="en" href="{{ $langSwitcherUrls['en'] ?? '' }}">
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>Blog</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Articles & <em>ressources</em>
          @else
            Articles & <em>resources</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Conseils techniques, retours d\'expérience et actualités sur la sous-traitance industrielle en Europe de l\'Est.'
            : 'Technical advice, experience feedback and news on industrial subcontracting in Eastern Europe.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Main: posts + sidebar --}}
<section class="section">
  <div class="container">
    <div class="blog-layout">

      {{-- Posts column --}}
      <div class="blog-main">

        @if($search || $tag)
          <div class="blog-filter-active">
            @if($search)
              <span>{{ $lang === 'fr' ? 'Recherche :' : 'Search:' }} <strong>{{ $search }}</strong></span>
            @endif
            @if($tag)
              <span>{{ $lang === 'fr' ? 'Tag :' : 'Tag:' }} <strong>{{ $tag }}</strong></span>
            @endif
            <a href="{{ route('blog', $lang) }}" class="blog-filter-clear">
              {{ $lang === 'fr' ? '✕ Effacer' : '✕ Clear' }}
            </a>
          </div>
        @endif

        @forelse($posts as $post)
          @php
            $title   = $post->getTranslation('title',   $lang, false) ?: $post->getTranslation('title',   'fr', false);
            $excerpt = $post->getTranslation('excerpt',  $lang, false) ?: $post->getTranslation('excerpt',  'fr', false);
            $tags    = $post->getTranslation('tags',     $lang, false) ?: $post->getTranslation('tags',     'fr', false);
          @endphp
          <article class="blog-list-item reveal">
            @if($post->featured_image_url)
              <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $post->slug]) }}" class="blog-list-img">
                <img src="{{ $post->featured_image_url }}" alt="{{ $title }}" loading="lazy">
              </a>
            @else
              <div class="blog-list-img">
                <div class="img-placeholder">{{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}</div>
              </div>
            @endif
            <div class="blog-list-body">
              @if(is_array($tags) && count($tags))
                <div class="blog-card-tags" style="margin-bottom:10px">
                  @foreach(array_slice($tags, 0, 3) as $t)
                    <a href="{{ route('blog', $lang) }}?tag={{ urlencode($t) }}" class="blog-card-tag">{{ $t }}</a>
                  @endforeach
                </div>
              @endif
              <h2 class="blog-list-title">
                <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $post->slug]) }}">{{ $title }}</a>
              </h2>
              @if($excerpt)
                <p class="blog-list-excerpt">{{ Str::limit(strip_tags($excerpt), 160) }}</p>
              @endif
              <div class="blog-card-meta" style="margin-top:16px">
                <span>{{ $post->author_name }}</span>
                <span>·</span>
                <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                @if($post->reading_time_minutes)
                  <span>·</span>
                  <span>{{ $post->reading_time_minutes }} min</span>
                @endif
              </div>
              <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $post->slug]) }}"
                 class="btn-link" style="display:inline-flex; align-items:center; gap:8px; margin-top:16px">
                {{ $lang === 'fr' ? 'Lire l\'article' : 'Read article' }}
                <span class="arrow">→</span>
              </a>
            </div>
          </article>
        @empty
          <div style="padding:60px 0; text-align:center; color:#6b7891">
            <p>{{ $lang === 'fr' ? 'Aucun article trouvé.' : 'No articles found.' }}</p>
          </div>
        @endforelse

        {{-- Pagination --}}
        @if($posts->hasPages())
          <div class="blog-pagination">
            {{ $posts->links('vendor.pagination.simple-web') }}
          </div>
        @endif

      </div>

      {{-- Sidebar --}}
      <aside class="blog-sidebar">

        {{-- Search --}}
        <div class="sidebar-block">
          <h3 class="sidebar-title">{{ $lang === 'fr' ? 'Rechercher' : 'Search' }}</h3>
          <form action="{{ route('blog', $lang) }}" method="GET" class="sidebar-search">
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="{{ $lang === 'fr' ? 'Mot-clé…' : 'Keyword…' }}"
                   aria-label="{{ $lang === 'fr' ? 'Rechercher' : 'Search' }}">
            <button type="submit" aria-label="{{ $lang === 'fr' ? 'Rechercher' : 'Search' }}">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
              </svg>
            </button>
          </form>
        </div>

        {{-- Tag cloud --}}
        @if(!empty($allTags))
          <div class="sidebar-block">
            <h3 class="sidebar-title">{{ $lang === 'fr' ? 'Sujets' : 'Topics' }}</h3>
            <div class="tag-cloud">
              @foreach($allTags as $t)
                <a href="{{ route('blog', $lang) }}?tag={{ urlencode($t) }}"
                   class="tag-chip {{ $tag === $t ? 'active' : '' }}">
                  {{ $t }}
                </a>
              @endforeach
            </div>
          </div>
        @endif

      </aside>

    </div>
  </div>
</section>

@endsection
```

---

## Task 5: Blog post detail view

**Files:**
- Create: `resources/views/web/blog/show.blade.php`

- [ ] **Step 1: Create the view**

```blade
{{-- resources/views/web/blog/show.blade.php --}}
@extends('layouts.web')

@php
  $title    = $post->getTranslation('title',            $lang, false) ?: $post->getTranslation('title',            'fr', false);
  $body     = $post->getTranslation('body',             $lang, false) ?: $post->getTranslation('body',             'fr', false);
  $tags     = $post->getTranslation('tags',             $lang, false) ?: $post->getTranslation('tags',             'fr', false);
  $metaT    = $post->getTranslation('meta_title',       $lang, false) ?: $title;
  $metaD    = $post->getTranslation('meta_description', $lang, false)
           ?: ($post->getTranslation('excerpt', $lang, false) ? Str::limit(strip_tags($post->getTranslation('excerpt', $lang, false)), 160) : '');
  $pageUrl  = request()->url();
@endphp

@section('title', $metaT . ' — Fab Sourcing')
@section('description', $metaD)

@push('head')
  <meta property="og:type"        content="article">
  <meta property="og:title"       content="{{ $metaT }} — Fab Sourcing">
  <meta property="og:description" content="{{ $metaD }}">
  @if($post->featured_image_url)
    <meta property="og:image"     content="{{ $post->featured_image_url }}">
  @endif
  <meta property="og:url"         content="{{ $pageUrl }}">
  <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
  <link rel="alternate" hreflang="fr" href="{{ $langSwitcherUrls['fr'] ?? '' }}">
  <link rel="alternate" hreflang="en" href="{{ $langSwitcherUrls['en'] ?? '' }}">
@endpush

@section('content')

{{-- Featured image hero --}}
@if($post->featured_image_url)
  <div class="article-hero-img">
    <img src="{{ $post->featured_image_url }}" alt="{{ $title }}" loading="eager">
  </div>
@endif

{{-- Article content --}}
<div class="section">
  <div class="container">
    <div class="article-layout">

      {{-- Main column --}}
      <article class="article-main">

        {{-- Breadcrumb --}}
        <div class="breadcrumb" style="margin-bottom:32px">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <a href="{{ route('blog', $lang) }}">Blog</a>
          <span>/</span>
          <span>{{ Str::limit($title, 40) }}</span>
        </div>

        {{-- Tags --}}
        @if(is_array($tags) && count($tags))
          <div class="blog-card-tags" style="margin-bottom:20px">
            @foreach($tags as $t)
              <a href="{{ route('blog', $lang) }}?tag={{ urlencode($t) }}" class="blog-card-tag">{{ $t }}</a>
            @endforeach
          </div>
        @endif

        {{-- Title --}}
        <h1 class="h-1" style="margin-bottom:24px">{{ $title }}</h1>

        {{-- Meta bar --}}
        <div class="article-meta">
          <span class="article-meta-author">{{ $post->author_name }}</span>
          <span class="article-meta-sep">·</span>
          <span>{{ $post->published_at?->translatedFormat('d F Y') }}</span>
          @if($post->reading_time_minutes)
            <span class="article-meta-sep">·</span>
            <span>{{ $post->reading_time_minutes }} min {{ $lang === 'fr' ? 'de lecture' : 'read' }}</span>
          @endif
        </div>

        {{-- Body --}}
        <div class="article-body">
          {!! $body !!}
        </div>

        {{-- Share --}}
        <div class="article-share">
          <span class="article-share-label">
            {{ $lang === 'fr' ? 'Partager' : 'Share' }}
          </span>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($pageUrl) }}"
             target="_blank" rel="noopener noreferrer" class="article-share-btn article-share-linkedin">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
            LinkedIn
          </a>
          <a href="mailto:?subject={{ urlencode($title) }}&body={{ urlencode($lang === 'fr' ? 'Je pense que cet article pourrait vous intéresser : ' : 'I think this article might interest you: ') . urlencode($pageUrl) }}"
             class="article-share-btn article-share-email">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            Email
          </a>
        </div>

      </article>

    </div>
  </div>
</div>

{{-- Related articles --}}
@if($related->count())
  <section class="section-tight" style="border-top:1px solid rgba(15,30,61,0.08); background:#f4f6f9">
    <div class="container">
      <div class="eyebrow" style="margin-bottom:32px">
        {{ $lang === 'fr' ? 'Articles similaires' : 'Related articles' }}
      </div>
      <div class="blog-grid reveal">
        @foreach($related as $rel)
          @php
            $relTitle   = $rel->getTranslation('title',   $lang, false) ?: $rel->getTranslation('title',   'fr', false);
            $relExcerpt = $rel->getTranslation('excerpt',  $lang, false) ?: $rel->getTranslation('excerpt',  'fr', false);
            $relTags    = $rel->getTranslation('tags',     $lang, false) ?: $rel->getTranslation('tags',     'fr', false);
          @endphp
          <article class="blog-card">
            <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $rel->slug]) }}" class="blog-card-img-link">
              <div class="blog-card-img">
                @if($rel->featured_image_url)
                  <img src="{{ $rel->featured_image_url }}" alt="{{ $relTitle }}" loading="lazy">
                @else
                  <div class="img-placeholder">{{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}</div>
                @endif
              </div>
            </a>
            <div class="blog-card-body">
              @if(is_array($relTags) && count($relTags))
                <div class="blog-card-tags">
                  @foreach(array_slice($relTags, 0, 2) as $t)
                    <span class="blog-card-tag">{{ $t }}</span>
                  @endforeach
                </div>
              @endif
              <h3 class="blog-card-title">
                <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $rel->slug]) }}">{{ $relTitle }}</a>
              </h3>
              @if($relExcerpt)
                <p class="blog-card-excerpt">{{ Str::limit(strip_tags($relExcerpt), 100) }}</p>
              @endif
              <div class="blog-card-meta">
                <span>{{ $rel->published_at?->translatedFormat('d M Y') }}</span>
                @if($rel->reading_time_minutes)
                  <span>·</span>
                  <span>{{ $rel->reading_time_minutes }} min</span>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Vous avez un projet ?' : 'Got a project?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Parlons-<em>en</em>
          @else
            Let's <em>talk</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Décrivez votre besoin, Thierry vous répond personnellement sous 48 heures.'
            : 'Describe your need, Thierry replies personally within 48 hours.' }}
        </p>
      </div>
      <div>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          {{ $lang === 'fr' ? 'Nous contacter' : 'Contact us' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
```

---

## Task 6: Blog CSS + pagination view

**Files:**
- Modify: `resources/sass/_sections.scss` (append)
- Modify: `resources/sass/_utils.scss` (append)
- Create: `resources/views/vendor/pagination/simple-web.blade.php`

- [ ] **Step 1: Add blog layout CSS to end of _sections.scss**

```scss
// Blog index layout
.blog-layout {
  display: grid;
  grid-template-columns: 1fr 280px;
  gap: 64px;
  align-items: start;

  @media (max-width: $bp-nav) {
    grid-template-columns: 1fr;
    gap: 48px;
  }
}

.blog-main {}

.blog-list-item {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 32px;
  padding: 40px 0;
  border-bottom: 1px solid $line;
  align-items: start;

  &:first-child { border-top: 1px solid $line; }

  @media (max-width: $bp-tablet) {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}

.blog-list-img {
  aspect-ratio: 4/3;
  background: $bg-alt;
  border-radius: $radius;
  overflow: hidden;
  display: block;
  position: relative;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    filter: grayscale(0.1) contrast(1.05);
  }

  &:hover img { transform: scale(1.04); }
}

.blog-list-body {}

.blog-list-title {
  font-family: $font-sans;
  font-weight: 600;
  font-size: clamp(20px, 2vw, 26px);
  line-height: 1.2;
  letter-spacing: -0.025em;
  margin-bottom: 12px;

  a { color: $ink-900; text-decoration: none; &:hover { color: $accent; } }
}

.blog-list-excerpt {
  font-size: 15px;
  line-height: 1.65;
  color: $ink-700;
}

.blog-filter-active {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  background: $bg-alt;
  border-radius: $radius;
  font-size: 14px;
  color: $ink-700;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.blog-filter-clear {
  color: $accent;
  font-size: 13px;
  text-decoration: none;
  &:hover { text-decoration: underline; }
}

.blog-pagination { margin-top: 48px; }

// Sidebar
.blog-sidebar {
  position: sticky;
  top: 100px;
  display: flex;
  flex-direction: column;
  gap: 32px;
}

.sidebar-block {
  background: $bg-alt;
  border-radius: $radius;
  padding: 24px;
}

.sidebar-title {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: $ink-500;
  margin-bottom: 16px;
}

.sidebar-search {
  display: flex;
  align-items: center;
  gap: 0;
  border: 1px solid $line-strong;
  border-radius: $radius;
  overflow: hidden;
  background: #fff;

  input {
    flex: 1;
    border: none;
    padding: 10px 14px;
    font-size: 14px;
    color: $ink-900;
    background: transparent;
    outline: none;
  }

  button {
    padding: 10px 14px;
    background: transparent;
    border: none;
    color: $ink-500;
    cursor: pointer;
    display: flex;
    align-items: center;
    &:hover { color: $accent; }
  }
}

// Blog article (post detail)
.article-hero-img {
  width: 100%;
  aspect-ratio: 21/9;
  overflow: hidden;
  background: $bg-alt;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(0.1) contrast(1.05);
  }

  @media (max-width: $bp-nav) { aspect-ratio: 16/9; }
}

.article-layout {
  display: grid;
  grid-template-columns: min(720px, 100%);
  justify-content: center;
}

.article-main {}

.article-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  font-family: $font-mono;
  font-size: 12px;
  letter-spacing: 0.08em;
  color: $ink-500;
  margin-bottom: 48px;
  flex-wrap: wrap;
}

.article-meta-author { color: $ink-700; font-weight: 500; }
.article-meta-sep    { color: $ink-300; }

.article-body {
  font-size: 17px;
  line-height: 1.75;
  color: $ink-700;

  h2 { font-family: $font-sans; font-weight: 600; font-size: clamp(22px, 2.2vw, 28px); letter-spacing: -0.025em; line-height: 1.2; margin: 40px 0 16px; color: $ink-900; }
  h3 { font-family: $font-sans; font-weight: 600; font-size: clamp(18px, 1.8vw, 22px); letter-spacing: -0.02em; line-height: 1.3; margin: 32px 0 12px; color: $ink-900; }
  p  { margin-bottom: 20px; }
  ul, ol { padding-left: 24px; margin-bottom: 20px; li { margin-bottom: 8px; } }
  strong { color: $ink-900; font-weight: 600; }
  em     { font-style: italic; }
  blockquote { border-left: 3px solid $accent; padding: 16px 24px; margin: 28px 0; background: $bg-alt; border-radius: 0 $radius $radius 0; font-style: italic; color: $ink-700; }
  a { color: $accent; text-decoration: underline; &:hover { text-decoration: none; } }
}

.article-share {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 24px 0;
  border-top: 1px solid $line;
  margin-top: 48px;
  flex-wrap: wrap;
}

.article-share-label {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: $ink-500;
  margin-right: 4px;
}

.article-share-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 16px;
  border-radius: $radius;
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.15s ease;
  border: 1px solid $line-strong;
  color: $ink-700;

  &:hover { border-color: $ink-900; color: $ink-900; }
}

.article-share-linkedin { &:hover { border-color: #0a66c2; color: #0a66c2; } }
.article-share-email    { &:hover { border-color: $accent;  color: $accent; } }

.blog-card-img-link { display: block; text-decoration: none; color: inherit; }
```

- [ ] **Step 2: Add tag-cloud CSS to _utils.scss**

Append to the end of `resources/sass/_utils.scss`:

```scss
// Tag cloud
.tag-cloud {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.tag-chip {
  display: inline-block;
  padding: 5px 12px;
  background: #fff;
  border: 1px solid $line-strong;
  border-radius: 999px;
  font-size: 12px;
  color: $ink-700;
  text-decoration: none;
  transition: all 0.15s ease;

  &:hover { border-color: $ink-900; color: $ink-900; }
  &.active { background: $ink-900; color: #fff; border-color: $ink-900; }
}

// Web pagination
.web-pagination {
  display: flex;
  align-items: center;
  gap: 4px;
  font-family: $font-mono;
  font-size: 12px;
  letter-spacing: 0.06em;

  a, span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 8px;
    border-radius: $radius;
    border: 1px solid $line;
    text-decoration: none;
    transition: all 0.15s ease;
    color: $ink-700;
  }

  a:hover { border-color: $ink-900; color: $ink-900; }

  .active { background: $ink-900; color: #fff; border-color: $ink-900; }
  .disabled { color: $ink-300; pointer-events: none; }
}
```

- [ ] **Step 3: Create pagination view directory and file**

```bash
mkdir -p /Users/zornitsamarinova/code/fabsourcing/resources/views/vendor/pagination
```

Create `resources/views/vendor/pagination/simple-web.blade.php`:

```blade
@if ($paginator->hasPages())
  <nav class="web-pagination" aria-label="Pagination">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <span class="disabled" aria-disabled="true">← {{ __('Préc.') }}</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" rel="prev">← {{ __('Préc.') }}</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="disabled">{{ $element }}</span>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="active" aria-current="page">{{ $page }}</span>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Suiv.') }} →</a>
    @else
      <span class="disabled" aria-disabled="true">{{ __('Suiv.') }} →</span>
    @endif
  </nav>
@endif
```

---

## Task 7: WhyController + Why Eastern Europe view

**Files:**
- Modify: `app/Http/Controllers/Web/WhyController.php`
- Modify: `resources/views/web/why.blade.php`
- Modify: `resources/sass/_sections.scss` (append)

- [ ] **Step 1: Update WhyController**

```php
<?php
// app/Http/Controllers/Web/WhyController.php
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
```

- [ ] **Step 2: Rewrite resources/views/web/why.blade.php**

```blade
@extends('layouts.web')

@section('title', $lang === 'fr'
    ? "Pourquoi l'Europe de l'Est — Fab Sourcing"
    : 'Why Eastern Europe — Fab Sourcing')

@section('description', $lang === 'fr'
    ? "Réduisez vos coûts de fabrication de 30 à 50 % en externalisant en Europe de l'Est. Main-d'œuvre qualifiée, logistique rapide, proximité culturelle."
    : 'Reduce your manufacturing costs by 30–50% by outsourcing to Eastern Europe. Skilled labour, fast logistics, cultural proximity.')

@push('head')
  <link rel="alternate" hreflang="fr" href="{{ $langSwitcherUrls['fr'] ?? '' }}">
  <link rel="alternate" hreflang="en" href="{{ $langSwitcherUrls['en'] ?? '' }}">
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            L'Europe de l'Est,<br>votre <em>avantage compétitif</em>
          @else
            Eastern Europe,<br>your <em>competitive edge</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? "Depuis plus de 15 ans, Fab Sourcing travaille avec des ateliers certifiés en Bulgarie et Roumanie. Voici pourquoi de plus en plus d'industriels français font le même choix."
            : 'For over 15 years, Fab Sourcing has worked with certified workshops in Bulgaria and Romania. Here is why more and more French manufacturers are making the same choice.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- 5 Advantages --}}
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? '5 raisons clés' : '5 key reasons' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Les atouts de <em>l'Europe de l'Est</em>
          @else
            The advantages of <em>Eastern Europe</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Des avantages structurels durables — pas une simple aubaine conjoncturelle — qui expliquent l\'attrait croissant de cette région pour la sous-traitance industrielle.'
            : 'Durable structural advantages — not a short-lived opportunity — that explain the growing appeal of this region for industrial subcontracting.' }}
        </p>
      </div>
    </div>

    @php
    $advantages = $lang === 'fr' ? [
      [
        'num'   => '01',
        'icon'  => '€',
        'title' => 'Réduction des coûts de 30 à 50 %',
        'desc'  => 'Les coûts de main-d\'œuvre en Bulgarie et Roumanie représentent 20 à 30 % de ceux de la France. Cette différence se traduit directement sur le prix de revient, sans compromis sur les normes (EN 1090, ISO 9001).',
        'stat'  => '−40 %',
        'statlabel' => 'coût moyen constaté',
      ],
      [
        'num'   => '02',
        'icon'  => '⚙',
        'title' => "Main-d'œuvre qualifiée",
        'desc'  => 'Les pays d\'Europe de l\'Est disposent d\'une longue tradition industrielle. Les soudeurs, chaudronniers et techniciens sont formés dans des filières solides et régulièrement certifiés.',
        'stat'  => 'EN 1090',
        'statlabel' => 'norme européenne',
      ],
      [
        'num'   => '03',
        'icon'  => '🚛',
        'title' => 'Logistique rapide',
        'desc'  => 'La Bulgarie et la Roumanie sont membres de l\'UE : pas de droits de douane, pas de blocages logistiques. Les délais de transport depuis Sofia ou Bucarest vers la France sont de 3 à 5 jours.',
        'stat'  => '3–5 j',
        'statlabel' => 'délai de livraison',
      ],
      [
        'num'   => '04',
        'icon'  => '🤝',
        'title' => 'Proximité culturelle & géographique',
        'desc'  => 'Le fuseau horaire est identique ou proche (+1 h). Les interlocuteurs parlent souvent français ou anglais. La culture professionnelle est proche de la nôtre, ce qui facilite la communication.',
        'stat'  => '+1 h',
        'statlabel' => 'décalage horaire max',
      ],
      [
        'num'   => '05',
        'icon'  => '🏭',
        'title' => 'Base industrielle solide',
        'desc'  => 'Ces pays hébergent des ateliers modernes équipés de machines CNC, robots de soudage, lignes de traitement de surface. L\'investissement industriel européen y est fort depuis 20 ans.',
        'stat'  => 'ISO',
        'statlabel' => 'certifications en vigueur',
      ],
    ] : [
      [
        'num'   => '01',
        'icon'  => '€',
        'title' => 'Cost reduction of 30–50 %',
        'desc'  => 'Labour costs in Bulgaria and Romania are 20–30 % of those in France. This difference directly impacts the production cost without compromising on standards (EN 1090, ISO 9001).',
        'stat'  => '−40 %',
        'statlabel' => 'average cost reduction',
      ],
      [
        'num'   => '02',
        'icon'  => '⚙',
        'title' => 'Skilled workforce',
        'desc'  => 'Eastern European countries have a long industrial tradition. Welders, fabricators and technicians are trained in solid curricula and regularly certified.',
        'stat'  => 'EN 1090',
        'statlabel' => 'European standard',
      ],
      [
        'num'   => '03',
        'icon'  => '🚛',
        'title' => 'Fast logistics',
        'desc'  => 'Bulgaria and Romania are EU members: no customs duties, no logistical blocks. Transit times from Sofia or Bucharest to France are 3–5 days.',
        'stat'  => '3–5 d',
        'statlabel' => 'delivery time',
      ],
      [
        'num'   => '04',
        'icon'  => '🤝',
        'title' => 'Cultural & geographic proximity',
        'desc'  => 'The time zone is identical or close (+1 h). Counterparts often speak French or English. The professional culture is close to ours, facilitating communication.',
        'stat'  => '+1 h',
        'statlabel' => 'max time difference',
      ],
      [
        'num'   => '05',
        'icon'  => '🏭',
        'title' => 'Solid industrial base',
        'desc'  => 'These countries host modern workshops equipped with CNC machines, welding robots, and surface treatment lines. European industrial investment there has been strong for 20 years.',
        'stat'  => 'ISO',
        'statlabel' => 'certifications in force',
      ],
    ];
    @endphp

    <div class="advantage-grid reveal">
      @foreach($advantages as $adv)
        <div class="advantage-card">
          <div class="advantage-header">
            <span class="advantage-num">{{ $adv['num'] }}</span>
            <div class="advantage-stat">
              <span class="advantage-stat-val">{{ $adv['stat'] }}</span>
              <span class="advantage-stat-lbl">{{ $adv['statlabel'] }}</span>
            </div>
          </div>
          <h3 class="advantage-title">{{ $adv['title'] }}</h3>
          <p class="advantage-desc">{{ $adv['desc'] }}</p>
        </div>
      @endforeach
    </div>

  </div>
</section>

{{-- Comparison table --}}
<section class="section-tight" style="background:$bg-alt; background:#f4f6f9">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Comparatif' : 'Comparison' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Europe de l'Est <em>vs</em> Asie
          @else
            Eastern Europe <em>vs</em> Asia
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Pour les industriels qui hésitent entre les deux zones, voici une comparaison objective sur les critères qui comptent.'
            : 'For manufacturers hesitating between the two zones, here is an objective comparison on the criteria that matter.' }}
        </p>
      </div>
    </div>

    @php
    $rows = $lang === 'fr' ? [
      ['criterion' => 'Délai de livraison',  'east' => '3–5 jours',     'asia' => '4–6 semaines'],
      ['criterion' => 'Coût de transport',   'east' => 'Faible (UE)',    'asia' => 'Élevé (maritime)'],
      ['criterion' => 'Droits de douane',    'east' => 'Aucun (UE)',     'asia' => '0–10 %'],
      ['criterion' => 'Qualité / normes',    'east' => 'EN 1090, ISO',   'asia' => 'Variable'],
      ['criterion' => 'Communication',       'east' => 'Facile (FR/EN)', 'asia' => 'Difficile'],
      ['criterion' => 'Suivi sur site',      'east' => '< 4 h de vol',   'asia' => '> 10 h de vol'],
      ['criterion' => 'Risque géopolitique', 'east' => 'Faible (UE)',    'asia' => 'Moyen/élevé'],
    ] : [
      ['criterion' => 'Delivery time',       'east' => '3–5 days',       'asia' => '4–6 weeks'],
      ['criterion' => 'Transport cost',      'east' => 'Low (EU)',        'asia' => 'High (maritime)'],
      ['criterion' => 'Customs duties',      'east' => 'None (EU)',       'asia' => '0–10 %'],
      ['criterion' => 'Quality / standards', 'east' => 'EN 1090, ISO',   'asia' => 'Variable'],
      ['criterion' => 'Communication',       'east' => 'Easy (FR/EN)',    'asia' => 'Difficult'],
      ['criterion' => 'On-site monitoring',  'east' => '< 4 h flight',   'asia' => '> 10 h flight'],
      ['criterion' => 'Geopolitical risk',   'east' => 'Low (EU)',        'asia' => 'Medium/high'],
    ];
    @endphp

    <div class="comparison-wrap reveal">
      <table class="comparison-table">
        <thead>
          <tr>
            <th>{{ $lang === 'fr' ? 'Critère' : 'Criterion' }}</th>
            <th class="comparison-east">
              {{ $lang === 'fr' ? "Europe de l'Est" : 'Eastern Europe' }}
              <span class="comparison-badge">Fab Sourcing</span>
            </th>
            <th class="comparison-asia">{{ $lang === 'fr' ? 'Asie' : 'Asia' }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $row)
            <tr>
              <td class="comparison-criterion">{{ $row['criterion'] }}</td>
              <td class="comparison-east comparison-val-east">
                <span class="comparison-check">✓</span>
                {{ $row['east'] }}
              </td>
              <td class="comparison-asia comparison-val-asia">{{ $row['asia'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Prêt à franchir le pas ?' : 'Ready to take the step?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Obtenez votre <em>premier devis</em>
          @else
            Get your <em>first quote</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Envoyez vos plans ou décrivez votre besoin. Réponse sous 48 heures avec une analyse technique et un prix.'
            : 'Send your drawings or describe your need. Reply within 48 hours with a technical analysis and a price.' }}
        </p>
      </div>
      <div>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          {{ $lang === 'fr' ? 'Demander un devis' : 'Request a quote' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
```

- [ ] **Step 3: Add advantage + comparison CSS to _sections.scss**

```scss
// Advantage grid (Why page)
.advantage-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2px;
  background: $line;
  border: 1px solid $line;
  border-radius: $radius;
  overflow: hidden;

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; }
}

.advantage-card {
  background: $bg;
  padding: 32px 28px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.advantage-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 4px;
}

.advantage-num {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.14em;
  color: $ink-500;
}

.advantage-stat {
  text-align: right;
}

.advantage-stat-val {
  display: block;
  font-family: $font-sans;
  font-weight: 700;
  font-size: 22px;
  letter-spacing: -0.03em;
  color: $accent;
  line-height: 1;
}

.advantage-stat-lbl {
  display: block;
  font-family: $font-mono;
  font-size: 9px;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: $ink-400;
  margin-top: 2px;
}

.advantage-title {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 17px;
  line-height: 1.3;
  letter-spacing: -0.02em;
  color: $ink-900;
}

.advantage-desc {
  font-size: 14px;
  line-height: 1.65;
  color: $ink-700;
}

// Comparison table (Why page)
.comparison-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.comparison-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;

  th, td {
    padding: 14px 20px;
    text-align: left;
    border-bottom: 1px solid $line;
  }

  th {
    font-family: $font-mono;
    font-size: 11px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: $ink-500;
    background: $bg;
    font-weight: 500;
    white-space: nowrap;
  }

  tbody tr:hover { background: $bg-alt; }
}

.comparison-east {
  background: rgba(43, 98, 217, 0.04);
  font-weight: 500;
}

.comparison-asia {
  color: $ink-500;
}

.comparison-criterion {
  color: $ink-900;
  font-weight: 500;
}

.comparison-badge {
  display: inline-block;
  margin-left: 8px;
  padding: 2px 8px;
  background: $accent;
  color: #fff;
  border-radius: 999px;
  font-size: 9px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-weight: 600;
  vertical-align: middle;
}

.comparison-check {
  color: #22c55e;
  font-weight: 700;
  margin-right: 6px;
}
```

---

## Task 8: MethodController + Methodology view

**Files:**
- Modify: `app/Http/Controllers/Web/MethodController.php`
- Modify: `resources/views/web/method.blade.php`
- Modify: `resources/sass/_sections.scss` (append commitment CSS)

- [ ] **Step 1: Update MethodController**

```php
<?php
// app/Http/Controllers/Web/MethodController.php
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
```

- [ ] **Step 2: Rewrite resources/views/web/method.blade.php**

```blade
@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Méthode Fab Sourcing — Sous-traitance industrielle'
    : 'Fab Sourcing Method — Industrial Subcontracting')

@section('description', $lang === 'fr'
    ? 'Notre processus en 7 étapes : analyse du besoin, étude technique, sélection fournisseur, prototype, production, contrôle qualité, livraison.'
    : 'Our 7-step process: needs analysis, technical study, supplier selection, prototype, production, quality control, delivery.')

@push('head')
  <link rel="alternate" hreflang="fr" href="{{ $langSwitcherUrls['fr'] ?? '' }}">
  <link rel="alternate" hreflang="en" href="{{ $langSwitcherUrls['en'] ?? '' }}">
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'Méthode' : 'Method' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Notre <em>méthode</em> en 7 étapes
          @else
            Our <em>method</em> in 7 steps
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'De la réception de vos plans à la livraison sur votre site, chaque étape est maîtrisée et documentée. Un seul interlocuteur, zéro mauvaise surprise.'
            : 'From receipt of your drawings to delivery on your site, every step is controlled and documented. One point of contact, zero bad surprises.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- 7-step process --}}
<section class="section">
  <div class="container">
    <div class="method-list reveal">
      @forelse($steps as $step)
        @php
          $title = $step->getTranslation('title',       $lang, false) ?: $step->getTranslation('title',       'fr', false);
          $desc  = $step->getTranslation('description', $lang, false) ?: $step->getTranslation('description', 'fr', false);
        @endphp
        <div class="method-step">
          <div class="method-step-num">{{ $step->number }}<em>.</em></div>
          <div>
            <h2 class="method-step-title">{{ $title }}</h2>
          </div>
          <div>
            <p class="method-step-desc">{{ $desc }}</p>
          </div>
        </div>
      @empty
        <p class="lede">{{ $lang === 'fr' ? 'Contenu à venir.' : 'Content coming soon.' }}</p>
      @endforelse
    </div>
  </div>
</section>

{{-- Notre engagement --}}
<section class="section-tight" style="background:#f4f6f9">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Nos engagements' : 'Our commitments' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Ce que vous pouvez<br><em>compter sur nous</em>
          @else
            What you can<br><em>count on us for</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Au-delà du processus, Fab Sourcing s\'engage sur trois principes qui guident chaque mission.'
            : 'Beyond the process, Fab Sourcing commits to three principles that guide every mission.' }}
        </p>
      </div>
    </div>

    @php
    $commitments = $lang === 'fr' ? [
      [
        'num'   => '01',
        'title' => 'Transparence totale',
        'desc'  => 'Vous recevez tous les documents : rapports de contrôle, certificats matière, photos de production. Aucune information n\'est retenue. Vous savez exactement où en est votre commande à tout moment.',
      ],
      [
        'num'   => '02',
        'title' => 'Interlocuteur unique francophone',
        'desc'  => 'Un seul chef de projet Fab Sourcing gère tout de A à Z : devis, technique, qualité, logistique et facturation. Vous n\'avez pas à coordonner plusieurs prestataires.',
      ],
      [
        'num'   => '03',
        'title' => 'Respect des délais',
        'desc'  => 'Les délais convenus sont contractuels. Notre responsable qualité sur site intervient dès la première alerte pour corriger les dérives avant qu\'elles n\'impactent votre planning.',
      ],
    ] : [
      [
        'num'   => '01',
        'title' => 'Full transparency',
        'desc'  => 'You receive all documents: control reports, material certificates, production photos. No information is withheld. You know exactly where your order stands at any time.',
      ],
      [
        'num'   => '02',
        'title' => 'Single French-speaking contact',
        'desc'  => 'One Fab Sourcing project manager handles everything from A to Z: quoting, technical, quality, logistics and invoicing. You do not need to coordinate multiple suppliers.',
      ],
      [
        'num'   => '03',
        'title' => 'On-time delivery',
        'desc'  => 'Agreed deadlines are contractual. Our on-site quality manager steps in at the first alert to correct deviations before they impact your schedule.',
      ],
    ];
    @endphp

    <div class="commitment-grid reveal">
      @foreach($commitments as $c)
        <div class="commitment-card">
          <span class="commitment-num">{{ $c['num'] }}</span>
          <h3 class="commitment-title">{{ $c['title'] }}</h3>
          <p class="commitment-desc">{{ $c['desc'] }}</p>
        </div>
      @endforeach
    </div>

  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Votre projet mérite cette rigueur' : 'Your project deserves this rigour' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Démarrons <em>ensemble</em>
          @else
            Let's start <em>together</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Envoyez-nous vos plans. Thierry Sudol vous répond avec une analyse technique et un premier devis sous 48 heures.'
            : 'Send us your drawings. Thierry Sudol replies with a technical analysis and a first quote within 48 hours.' }}
        </p>
      </div>
      <div>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          {{ $lang === 'fr' ? 'Nous contacter' : 'Contact us' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
```

- [ ] **Step 3: Add commitment CSS to _sections.scss**

```scss
// Commitment grid (Method page)
.commitment-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; }
}

.commitment-card {
  padding: 32px 28px;
  background: $bg;
  border-radius: $radius;
  border: 1px solid $line;
}

.commitment-num {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.14em;
  color: $accent;
  display: block;
  margin-bottom: 16px;
}

.commitment-title {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 20px;
  line-height: 1.25;
  letter-spacing: -0.025em;
  color: $ink-900;
  margin-bottom: 12px;
}

.commitment-desc {
  font-size: 14px;
  line-height: 1.65;
  color: $ink-700;
}
```

---

## Task 9: AboutController + About view

**Files:**
- Modify: `app/Http/Controllers/Web/AboutController.php`
- Modify: `resources/views/web/about.blade.php`
- Modify: `resources/sass/_sections.scss` (append team CSS)

- [ ] **Step 1: Update AboutController**

```php
<?php
// app/Http/Controllers/Web/AboutController.php
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
```

- [ ] **Step 2: Rewrite resources/views/web/about.blade.php**

```blade
@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'À propos de Fab Sourcing — Sous-traitance industrielle'
    : 'About Fab Sourcing — Industrial Subcontracting')

@section('description', $lang === 'fr'
    ? 'Fab Sourcing, fondé par Thierry Sudol. 15 ans d\'expérience en sourcing industriel en Bulgarie et Roumanie. PME, groupes internationaux, bureaux d\'études.'
    : 'Fab Sourcing, founded by Thierry Sudol. 15 years of experience in industrial sourcing in Bulgaria and Romania. SMEs, international groups, engineering offices.')

@push('head')
  <link rel="alternate" hreflang="fr" href="{{ $langSwitcherUrls['fr'] ?? '' }}">
  <link rel="alternate" hreflang="en" href="{{ $langSwitcherUrls['en'] ?? '' }}">
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'À propos' : 'About' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Un partenaire<br><em>de confiance</em> depuis 2008
          @else
            A trusted <em>partner</em><br>since 2008
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Fab Sourcing est né d\'un constat simple : les industriels français avaient besoin d\'un interlocuteur fiable, parlant leur langue, capable de coordonner la sous-traitance en Europe de l\'Est sans les inconvénients habituels.'
            : 'Fab Sourcing was born from a simple observation: French manufacturers needed a reliable contact, speaking their language, capable of coordinating subcontracting in Eastern Europe without the usual drawbacks.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Mission section --}}
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Notre mission' : 'Our mission' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Trois priorités,<br><em>une seule obsession</em>
          @else
            Three priorities,<br><em>one obsession</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Depuis notre création, chaque décision opérationnelle est guidée par ces trois missions : sécuriser, réduire, garantir.'
            : 'Since our founding, every operational decision is guided by these three missions: secure, reduce, guarantee.' }}
        </p>
      </div>
    </div>

    <div class="values-grid reveal">
      @php
      $missions = $lang === 'fr' ? [
        ['num' => '01', 'title' => 'Sécuriser la chaîne',       'desc' => 'Sélectionner les bons ateliers, valider les process, contrôler la production. Notre rôle est d\'être votre filet de sécurité en Europe de l\'Est.'],
        ['num' => '02', 'title' => 'Réduire les coûts',        'desc' => 'Identifier les économies réelles sans sacrifier la qualité. En moyenne, nos clients économisent 40 % sur leur coût de fabrication.'],
        ['num' => '03', 'title' => 'Garantir la qualité',      'desc' => 'Inspections sur site, rapports de conformité, traçabilité complète. Chaque pièce livrée est documentée et conforme à votre cahier des charges.'],
      ] : [
        ['num' => '01', 'title' => 'Secure the supply chain',  'desc' => 'Select the right workshops, validate processes, monitor production. Our role is to be your safety net in Eastern Europe.'],
        ['num' => '02', 'title' => 'Reduce costs',             'desc' => 'Identify real savings without sacrificing quality. On average, our clients save 40 % on their manufacturing cost.'],
        ['num' => '03', 'title' => 'Guarantee quality',        'desc' => 'On-site inspections, compliance reports, full traceability. Every delivered part is documented and conforms to your specifications.'],
      ];
      @endphp
      @foreach($missions as $m)
        <div class="value">
          <span class="value-num">{{ $m['num'] }}</span>
          <h3 class="value-title">{{ $m['title'] }}</h3>
          <p class="value-desc">{{ $m['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Who we work with --}}
<section class="section-tight" style="background:#f4f6f9">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Nos clients' : 'Our clients' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Avec qui nous<br><em>travaillons</em>
          @else
            Who we<br><em>work with</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Nos clients sont des industriels qui cherchent à externaliser tout ou partie de leur fabrication en Europe de l\'Est, avec un interlocuteur unique francophone.'
            : 'Our clients are manufacturers who want to outsource all or part of their production in Eastern Europe, with a single French-speaking contact.' }}
        </p>
      </div>
    </div>

    <div class="client-type-grid reveal">
      @php
      $clients = $lang === 'fr' ? [
        [
          'title' => 'PME industrielles',
          'desc'  => 'TPE/PME du secteur métallurgique, mécanique ou chaudronnerie souhaitant réduire leurs coûts de production sans créer une filiale à l\'étranger.',
          'examples' => 'Charpentiers métalliques, sous-traitants tier 2/3, ateliers de mécano-soudure',
        ],
        [
          'title' => 'Groupes internationaux',
          'desc'  => 'Sites industriels de grands groupes cherchant à diversifier leur sourcing ou à sécuriser une supply chain pour composants ou structures métalliques.',
          'examples' => 'Donneurs d\'ordres Tier 1 & 2, acheteurs industriels, directions travaux',
        ],
        [
          'title' => "Bureaux d'études",
          'desc'  => 'Ingénieristes et bureaux d\'études qui conçoivent des équipements industriels et ont besoin d\'un fabricant compétent, certifié et réactif.',
          'examples' => 'Maîtres d\'œuvre industriels, intégrateurs, concepteurs-réalisateurs',
        ],
      ] : [
        [
          'title' => 'Industrial SMEs',
          'desc'  => 'Small and medium-sized companies in the metalworking, mechanical or fabrication sector looking to reduce production costs without setting up a foreign subsidiary.',
          'examples' => 'Steel fabricators, Tier 2/3 subcontractors, welded assembly workshops',
        ],
        [
          'title' => 'International groups',
          'desc'  => 'Industrial sites of large groups looking to diversify their sourcing or secure a supply chain for components or metal structures.',
          'examples' => 'Tier 1 & 2 principals, industrial buyers, project directors',
        ],
        [
          'title' => 'Engineering offices',
          'desc'  => 'Engineering firms and design offices that design industrial equipment and need a competent, certified and responsive manufacturer.',
          'examples' => 'Industrial project managers, integrators, design-build specialists',
        ],
      ];
      @endphp
      @foreach($clients as $c)
        <div class="client-card">
          <h3 class="client-card-title">{{ $c['title'] }}</h3>
          <p class="client-card-desc">{{ $c['desc'] }}</p>
          <p class="client-card-examples">{{ $c['examples'] }}</p>
        </div>
      @endforeach
    </div>

  </div>
</section>

{{-- Team --}}
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'L\'équipe' : 'The team' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Votre interlocuteur<br><em>direct</em>
          @else
            Your <em>direct</em><br>contact
          @endif
        </h2>
      </div>
    </div>

    <div class="team-card reveal">
      <div class="team-card-photo">
        <div class="img-placeholder" style="position:static; width:100%; height:100%">
          {{ $lang === 'fr' ? 'Photo à venir' : 'Photo coming soon' }}
        </div>
      </div>
      <div class="team-card-body">
        <div class="eyebrow no-line" style="margin-bottom:8px">Fab Sourcing</div>
        <h2 class="h-3" style="margin-bottom:4px">Thierry Sudol</h2>
        <p style="font-family:var(--font-mono, monospace); font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:#6b7891; margin-bottom:24px">
          {{ $lang === 'fr' ? 'Fondateur & Directeur' : 'Founder & Director' }}
        </p>
        <p class="body" style="margin-bottom:20px">
          {{ $lang === 'fr'
            ? 'Thierry Sudol a fondé Fab Sourcing après plus de 15 ans passés sur le terrain en Europe de l\'Est, à accompagner des industriels français dans leurs projets de sous-traitance. Ingénieur de formation, il maîtrise les aspects techniques, logistiques et réglementaires de la fabrication métallique en Bulgarie et Roumanie.'
            : 'Thierry Sudol founded Fab Sourcing after more than 15 years spent in the field in Eastern Europe, supporting French manufacturers in their subcontracting projects. An engineer by training, he masters the technical, logistical and regulatory aspects of metal fabrication in Bulgaria and Romania.' }}
        </p>
        <p class="body" style="margin-bottom:32px">
          {{ $lang === 'fr'
            ? 'Il est l\'interlocuteur unique de chaque client, de la demande de devis jusqu\'à la livraison. Sa connaissance intime du tissu industriel local et ses relations de confiance avec les ateliers partenaires sont le cœur de valeur de Fab Sourcing.'
            : 'He is the single point of contact for every client, from the quote request through to delivery. His intimate knowledge of the local industrial fabric and trusted relationships with partner workshops are the core value of Fab Sourcing.' }}
        </p>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary">
          {{ $lang === 'fr' ? 'Contacter Thierry' : 'Contact Thierry' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>

  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'On travaille ensemble ?' : 'Work together?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Discutons de votre <em>projet</em>
          @else
            Let's discuss your <em>project</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Un premier échange sans engagement pour comprendre votre besoin et voir si nous pouvons vous aider.'
            : 'A first no-obligation conversation to understand your need and see if we can help.' }}
        </p>
      </div>
      <div>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          {{ $lang === 'fr' ? 'Nous contacter' : 'Contact us' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
```

- [ ] **Step 3: Add client type + team CSS to _sections.scss**

```scss
// Client type grid (About page)
.client-type-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; }
}

.client-card {
  background: $bg;
  border-radius: $radius;
  padding: 28px;
  border: 1px solid $line;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.client-card-title {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 18px;
  letter-spacing: -0.02em;
  color: $ink-900;
}

.client-card-desc {
  font-size: 14px;
  line-height: 1.65;
  color: $ink-700;
}

.client-card-examples {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.06em;
  color: $ink-500;
  margin-top: auto;
  padding-top: 12px;
  border-top: 1px solid $line;
}

// Team card (About page)
.team-card {
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 64px;
  align-items: start;
  padding: 48px;
  background: $bg-alt;
  border-radius: $radius-lg;
  border: 1px solid $line;

  @media (max-width: $bp-nav) {
    grid-template-columns: 1fr;
    gap: 32px;
    padding: 32px;
  }
}

.team-card-photo {
  aspect-ratio: 3/4;
  background: $bg;
  border-radius: $radius;
  overflow: hidden;
  position: relative;
}

.team-card-body {}
```

---

## Task 10: LegalController + Legal view

**Files:**
- Create: `app/Http/Controllers/Web/LegalController.php`
- Create: `resources/views/web/legal.blade.php`

- [ ] **Step 1: Create LegalController**

```php
<?php
// app/Http/Controllers/Web/LegalController.php
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
```

- [ ] **Step 2: Create resources/views/web/legal.blade.php**

```blade
@extends('layouts.web')

@php
  $pageTitle = $page->getTranslation('title',            $lang, false) ?: $page->getTranslation('title', 'fr', false);
  $content   = $page->getTranslation('content',          $lang, false) ?: $page->getTranslation('content', 'fr', false);
  $metaTitle = $page->getTranslation('meta_title',       $lang, false) ?: $pageTitle;
  $metaDesc  = $page->getTranslation('meta_description', $lang, false) ?: '';
@endphp

@section('title', $metaTitle . ' — Fab Sourcing')
@section('description', $metaDesc)

@push('head')
  <link rel="alternate" hreflang="fr" href="{{ $langSwitcherUrls['fr'] ?? '' }}">
  <link rel="alternate" hreflang="en" href="{{ $langSwitcherUrls['en'] ?? '' }}">
@endpush

@section('content')

<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $pageTitle }}</span>
        </div>
        <h1 class="h-1">{{ $pageTitle }}</h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Informations légales et réglementaires concernant l\'utilisation de ce site.'
            : 'Legal and regulatory information regarding the use of this site.' }}
        </p>
      </div>
    </div>
  </div>
</div>

<section class="section">
  <div class="container">
    @if($content)
      <div class="legal-body article-layout">
        <div class="article-body">
          {!! $content !!}
        </div>
      </div>
    @else
      <div class="legal-placeholder">
        <p class="lede" style="color:#6b7891">
          {{ $lang === 'fr'
            ? 'Ce contenu sera ajouté prochainement. Vous pouvez l\'éditer depuis l\'administration.'
            : 'This content will be added shortly. You can edit it from the administration.' }}
        </p>
        <a href="{{ route('contact', $lang) }}" class="btn btn-ghost" style="margin-top:24px">
          {{ $lang === 'fr' ? 'Nous contacter' : 'Contact us' }}
        </a>
      </div>
    @endif
  </div>
</section>

@endsection
```

---

## Task 11: Compile and smoke test

**Files:** No changes — compilation only.

- [ ] **Step 1: Compile assets**

```bash
cd /Users/zornitsamarinova/code/fabsourcing
npm run dev 2>&1 | grep -E "compiled|error|Error"
```

Expected: `webpack compiled successfully`

- [ ] **Step 2: Check all routes resolve**

```bash
php artisan route:list --path=fr 2>&1 | grep -E "blog|why|method|about|legal|mentions|privacy"
```

Expected: 14+ route lines, no exceptions.

- [ ] **Step 3: Smoke test key URLs**

```bash
# Each should return 200 (not 302 or 500)
for url in \
  "http://localhost:8000/fr/blog" \
  "http://localhost:8000/en/blog" \
  "http://localhost:8000/fr/blog/pourquoi-externaliser-production-europe-est" \
  "http://localhost:8000/fr/pourquoi-europe-est" \
  "http://localhost:8000/en/why-eastern-europe" \
  "http://localhost:8000/fr/methodologie" \
  "http://localhost:8000/en/methodology" \
  "http://localhost:8000/fr/a-propos" \
  "http://localhost:8000/en/about" \
  "http://localhost:8000/fr/mentions-legales" \
  "http://localhost:8000/en/legal-notice" \
  "http://localhost:8000/fr/politique-confidentialite" \
  "http://localhost:8000/en/privacy-policy"; do
  code=$(curl -s -o /dev/null -w "%{http_code}" "$url")
  echo "$code  $url"
done
```

Expected: all 200.

- [ ] **Step 4: Verify bilingual language switcher on Why page**

```bash
curl -s "http://localhost:8000/fr/pourquoi-europe-est" | grep -o 'href="[^"]*why-eastern-europe[^"]*"'
```

Expected: `href="/en/why-eastern-europe"` (the EN language switcher link).

---

## Self-Review

**Spec coverage:**
- ✅ Blog index: hero, list with pagination, search, tag cloud, sidebar
- ✅ Blog post: featured image, title/date/author/tags, body, share (LinkedIn + email), related articles, CTA
- ✅ Why Eastern Europe: hero, 5 advantages, comparison table (East EU vs Asia), CTA
- ✅ Methodology: hero, 7-step timeline, 3 commitments, CTA
- ✅ About: hero, mission (3 points), who we work with (3 types), team (Thierry), CTA
- ✅ Legal: stub pages routing to DB content, editable in admin
- ✅ All pages use Pages table (legal) or hardcoded structure (why/method/about) with DB for steps
- ✅ Language switcher works on all pages via `langSwitcherUrls` override
- ✅ Footer legal links updated to proper routes
- ✅ "Voir tous les articles" in home fixed to link to blog

**Placeholder scan:** No TBD/TODO placeholders found. All steps have complete code.

**Type consistency:** `langSwitcherUrls` array pattern is consistent across all controllers. `array_merge` used consistently. `getTranslation($field, $lang, false)` pattern used consistently.
