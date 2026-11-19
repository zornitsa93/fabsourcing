<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductsController extends WebPagesController
{
    /**
     * Old English category slugs that no longer match the category content.
     * They stay resolvable so the old URLs can 301 to the current ones.
     */
    private const LEGACY_CATEGORY_SLUGS = [
        'industrial-metal-cladding-facade' => 'balcons',               // now: metal-terraces-balconies
        'workshop-glass-roof-partition'    => 'skid-machines-shelter', // now: machine-skids-shelters
    ];

    private function findCategory(string $slug): ProductCategory
    {
        $slug = self::LEGACY_CATEGORY_SLUGS[$slug] ?? $slug;

        return ProductCategory::where('published', true)
            ->where(fn ($q) => $q->where('slug', $slug)->orWhere('slug_en', $slug))
            ->firstOrFail();
    }

    /** 301 to the canonical URL when the requested path is not exactly it (old slug, wrong language, ...). */
    private function redirectIfNotCanonical(string $canonicalUrl)
    {
        $canonicalPath = trim((string) parse_url($canonicalUrl, PHP_URL_PATH), '/');

        if (trim(request()->path(), '/') === $canonicalPath) {
            return null;
        }

        $query = request()->getQueryString();

        return redirect()->to(url($canonicalPath) . ($query ? '?' . $query : ''), 301);
    }

    // ── /fr/produits or /en/products ─────────────────────────────────────────
    public function index(string $lang = 'fr')
    {
        $categories = ProductCategory::forCatalog()->get();

        $langSwitcherUrls = [
            'fr' => route('products',    ['lang' => 'fr']),
            'en' => route('products.en', ['lang' => 'en']),
        ];

        return view('web.products.index', array_merge($this->commonForWebPages($lang), compact(
            'categories', 'langSwitcherUrls'
        )));
    }

    // ── /fr/produits/{slug} or /en/products/{slug} ────────────────────────────
    public function category(string $lang, string $categorySlug)
    {
        $category = $this->findCategory($categorySlug);

        if ($redirect = $this->redirectIfNotCanonical($category->getUrlForLang($lang))) {
            return $redirect;
        }

        $products = $category->products()
            ->where('published', true)
            ->get();

        $langSwitcherUrls = [
            'fr' => route('products.category',    ['lang' => 'fr', 'categorySlug' => $category->slug]),
            'en' => route('products.category.en', ['lang' => 'en', 'categorySlug' => $category->slug_en ?: $category->slug]),
        ];

        return view('web.products.category', array_merge($this->commonForWebPages($lang), compact(
            'category', 'products', 'langSwitcherUrls'
        )));
    }

    // ── /fr/produits/{cat}/{prod} or /en/products/{cat}/{prod} ────────────────
    public function detail(string $lang, string $categorySlug, string $productSlug)
    {
        $category = $this->findCategory($categorySlug);

        $product = Product::where('product_category_id', $category->id)
            ->where(function ($q) use ($productSlug) {
                $q->where('slug', $productSlug)->orWhere('slug_en', $productSlug);
            })
            ->where('published', true)
            ->firstOrFail();

        $canonical = $lang === 'en'
            ? route('products.detail.en', ['lang' => 'en', 'categorySlug' => $category->slug_en ?: $category->slug, 'productSlug' => $product->slug_en ?: $product->slug])
            : route('products.detail',    ['lang' => $lang, 'categorySlug' => $category->slug, 'productSlug' => $product->slug]);

        if ($redirect = $this->redirectIfNotCanonical($canonical)) {
            return $redirect;
        }

        $related = Product::where('product_category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->where('published', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $langSwitcherUrls = [
            'fr' => route('products.detail',    [
                'lang'         => 'fr',
                'categorySlug' => $category->slug,
                'productSlug'  => $product->slug,
            ]),
            'en' => route('products.detail.en', [
                'lang'         => 'en',
                'categorySlug' => $category->slug_en ?: $category->slug,
                'productSlug'  => $product->slug_en  ?: $product->slug,
            ]),
        ];

        return view('web.products.detail', array_merge($this->commonForWebPages($lang), compact(
            'category', 'product', 'related', 'langSwitcherUrls'
        )));
    }
}
