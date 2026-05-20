<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductsController extends WebPagesController
{
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
        $category = ProductCategory::where('slug', $categorySlug)
            ->orWhere('slug_en', $categorySlug)
            ->where('published', true)
            ->firstOrFail();

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
        $category = ProductCategory::where('slug', $categorySlug)
            ->orWhere('slug_en', $categorySlug)
            ->where('published', true)
            ->firstOrFail();

        $product = Product::where('product_category_id', $category->id)
            ->where(function ($q) use ($productSlug) {
                $q->where('slug', $productSlug)->orWhere('slug_en', $productSlug);
            })
            ->where('published', true)
            ->firstOrFail();

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
