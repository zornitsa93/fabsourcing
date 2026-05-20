# SEO Layer Design — fab-sourcing.fr

## Goal

Add a complete SEO layer to the Laravel 12 site: per-page meta tags, Schema.org structured data, XML sitemaps, robots.txt, keyword-rich URL slugs, internal auto-linking in rich text, GA4 with GDPR cookie consent, and performance improvements.

## Architecture

Approach A: Blade component for meta tags, SitemapController for XML, TextLinker service for auto-linking, vanilla JS cookie consent stored in localStorage. No new packages. All changes are additive and non-breaking to the existing layout and routing.

## Tech Stack

- Laravel 12 / PHP 8.2 / Blade components / Laravel Mix 6
- Spatie Laravel Translatable (JSON columns, `getTranslation($field, $locale, false)`)
- Vanilla JS (ES5-compatible) for cookie consent
- JSON-LD for Schema.org (inline per view, no shared component)

---

## Section 1: `<x-seo>` Blade Component

### Layout change (`resources/views/layouts/web.blade.php`)

Add `@stack('seo')` between the existing `<meta name="description">` and `@stack('head')`:

```html
<meta name="description" content="@yield('description', '...')">
@stack('seo')
@stack('head')
```

### Component (`resources/views/components/seo.blade.php`)

Props: `$title`, `$description`, `$canonical`, `$lang`, `$hreflangFr`, `$hreflangEn`, `$ogType` (default `'website'`), `$ogImage`.

Outputs only:
- `<link rel="canonical" href="{{ $canonical }}">`
- `<link rel="alternate" hreflang="fr" href="{{ $hreflangFr }}">`
- `<link rel="alternate" hreflang="en" href="{{ $hreflangEn }}">`
- `<link rel="alternate" hreflang="x-default" href="{{ $hreflangFr }}">`
- OG tags: `og:title`, `og:description`, `og:image`, `og:url`, `og:type`, `og:locale` (`fr_FR` or `en_US` from `$lang`)
- Twitter Card: `<meta name="twitter:card" content="summary_large_image">`, `twitter:title`, `twitter:description`, `twitter:image`

Does NOT output `<title>` or `<meta name="description">` — those stay as `@yield('title')` / `@yield('description')`.

### Usage in views

```blade
@push('seo')
<x-seo
  :title="$page?->getTranslation('meta_title', $lang, false) ?: 'Fab Sourcing'"
  :description="$page?->getTranslation('meta_description', $lang, false) ?: ''"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en']"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush
```

Views to update: `welcome.blade.php`, `contacts.blade.php`, `web/why.blade.php`, `web/method.blade.php`, `web/about.blade.php`, `web/legal.blade.php`, `web/blog/index.blade.php`, `web/blog/show.blade.php`, `web/products/index.blade.php`, `web/products/category.blade.php`, `web/products/detail.blade.php`.

---

## Section 2: Schema.org Structured Data

All schemas are injected via `@push('scripts')` as `<script type="application/ld+json">` blocks in the relevant view. No shared component.

### Organization (home page — `welcome.blade.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Fab Sourcing",
  "url": "https://fab-sourcing.fr",
  "logo": "https://fab-sourcing.fr/images/logo.png",
  "address": { "@type": "PostalAddress", "addressCountry": "FR" },
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "sales",
    "email": "contact@fab-sourcing.fr"
  }
}
```

### LocalBusiness (contact page — `contacts.blade.php`)

Same as Organization but `"@type": "LocalBusiness"` with `"areaServed": ["BG", "RO"]`.

### Article (`web/blog/show.blade.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ $post->getTranslation('title', $lang, false) }}",
  "datePublished": "{{ $post->published_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": { "@type": "Person", "name": "{{ $post->author }}" },
  "image": "{{ asset($post->cover_image) }}",
  "url": "{{ $langSwitcherUrls[$lang] }}"
}
```

### Product (`web/products/detail.blade.php`)

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ $product->getTranslation('name', $lang, false) }}",
  "description": "{{ strip_tags($product->getTranslation('short_description', $lang, false)) }}",
  "image": "{{ asset($product->main_image) }}",
  "brand": { "@type": "Brand", "name": "Fab Sourcing" },
  "category": "{{ $category->getTranslation('name', $lang, false) }}"
}
```

### BreadcrumbList

Added to: `web/products/category.blade.php`, `web/products/detail.blade.php`, `web/blog/show.blade.php`.

Each has 2–3 items: Home → Category → Item (where applicable).

---

## Section 3: Sitemaps

### Routes (outside language prefix group in `routes/web.php`)

```php
Route::get('/sitemap.xml',    [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-fr.xml', [SitemapController::class, 'lang'])->name('sitemap.fr');
Route::get('/sitemap-en.xml', [SitemapController::class, 'lang'])->name('sitemap.en');
```

### `app/Http/Controllers/Web/SitemapController.php`

- `index()`: returns XML listing `sitemap-fr.xml` and `sitemap-en.xml`
- `lang(Request $request)`: detects lang by checking `str_contains(request()->path(), '-fr.')` → `'fr'`, else `'en'`; returns URL set for that language

### Content per language sitemap

| URL type | changefreq | priority | lastmod |
|---|---|---|---|
| Static pages (home, services, products, why, method, about, contact, legal) | monthly | 0.8 | — |
| Product categories (published) | monthly | 0.7 | `updated_at` |
| Products (published) | weekly | 0.6 | `updated_at` |
| Blog posts (published) | weekly | 0.6 | `updated_at` |

No package dependency — pure Laravel, XML returned via `response()->stream()` with `Content-Type: application/xml`.

---

## Section 4: robots.txt

Static file at `public/robots.txt`:

```
User-agent: *
Allow: /
Disallow: /admin

Sitemap: https://fab-sourcing.fr/sitemap.xml
```

---

## Section 5: URL Slug Enrichment

A data-only migration (no schema changes) updates `slug` and `slug_en` on `product_categories` and `slug` / `slug_en` on `products` with keyword-rich values.

### Category slug map

| Current `slug` | New `slug` | New `slug_en` |
|---|---|---|
| structures-metalliques | charpente-structures-metalliques-acier | steel-structure-metalwork |
| escaliers-metalliques | escaliers-metalliques-sur-mesure | custom-metal-stairs |
| garde-corps-rampes | garde-corps-rampes-inox-acier | stainless-steel-railings-handrails |
| menuiseries-metalliques | fenetres-portes-menuiseries-metalliques | metal-windows-doors-joinery |
| bardages-facades | bardage-facade-metallique-industriel | industrial-metal-cladding-facade |
| verrieres-cloisons | verriere-atelier-cloison-vitree | workshop-glass-roof-partition |
| portails-clotures | portail-cloture-acier-sur-mesure | custom-steel-gate-fence |
| terrasses-balcons | terrasse-balcon-garde-corps-exterieur | terrace-balcony-outdoor-railing |
| racks-shelters | rack-industriel-shelter-stockage | industrial-rack-storage-shelter |

Product slugs follow the same pattern, derived from product names in the DB.

**Note**: This changes live URLs. Acceptable because the site is not yet indexed.

---

## Section 6: Internal Auto-Linking

### `app/Services/TextLinker.php`

A static service class with one public method:

```php
TextLinker::linkify(string $html, string $lang): string
```

- Defines a phrase → URL map (~10 phrases per language)
- Wraps the **first occurrence only** of each phrase per call
- Skips text already inside `<a>` tags — use DOMDocument to walk text nodes safely (not regex on the full HTML string)
- Returns the modified HTML string

**Phrase map examples (FR):**
- `'sous-traitance industrielle'` → `route('why')`
- `'structures métalliques'` → `route('products.category', ['fr', 'charpente-structures-metalliques-acier'])`
- `'Europe de l\'Est'` → `route('why')`
- `'externalisation'` → `route('why')`

### Usage

In `web/blog/show.blade.php` and `web/products/detail.blade.php` only:

```blade
{!! \App\Services\TextLinker::linkify($body, $lang) !!}
```

Replaces the current `{!! $body !!}` / `{!! $fullDescription !!}`.

---

## Section 7: GA4 + GDPR Cookie Consent

### Config

Add to `config/services.php`:
```php
'ga_id' => env('GOOGLE_ANALYTICS_ID'),
```

### Layout change (`resources/views/layouts/web.blade.php`)

Immediately before `</body>`:

```html
@if(config('services.ga_id'))
  @include('partials.cookie-consent')
@endif
```

### `resources/views/partials/cookie-consent.blade.php`

Contains:
1. A `<div id="cookie-banner">` with inline CSS (fixed bottom bar, white background, shadow, two buttons: "Accepter" / "Refuser"). Banner text is bilingual — detected from `document.documentElement.lang`.
2. A `<script>` block (~40 lines vanilla JS, ES5-compatible):
   - On load: reads `localStorage.getItem('cookie_consent')`
     - `'accepted'` → load GA immediately, hide banner
     - `'declined'` → hide banner, do nothing
     - `null` → show banner
   - Accept: `localStorage.setItem('cookie_consent', 'accepted')`, load GA, hide banner
   - Decline: `localStorage.setItem('cookie_consent', 'declined')`, hide banner
3. GA loader function: creates `<script src="https://www.googletagmanager.com/gtag/js?id=GA_ID">`, then calls `gtag('config', 'GA_ID')`.

The GA measurement ID is rendered server-side from `config('services.ga_id')` into the JS.

---

## Section 8: Performance

### Lazy-load images

Add `loading="lazy"` to `<img>` tags in:
- `web/blog/index.blade.php` — blog card images
- `web/blog/show.blade.php` — body images (via `TextLinker` output, not directly) — hero gets `loading="eager"`
- `web/products/category.blade.php` — product grid images
- `web/products/detail.blade.php` — gallery images; main image gets `loading="eager"`
- Related products grids in detail views

### Asset minification

Verify `webpack.mix.js` has `.version()` enabled. If not, add it. Run `npm run prod` to produce content-hashed assets. Switch `asset('css/web.css')` calls in layout to `mix('css/web.css')` if not already using the Mix helper.

### Cache headers check

Verify `config/app.php` `cache_duration` is set (used by `WebPagesController::commonForWebPages()`). No code change unless misconfigured.
