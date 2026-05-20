@extends('layouts.web')

@php
  $catName = $category->getTranslation('name', $lang, false) ?: $category->getTranslation('name', 'fr', false);
  $catDesc = $category->getTranslation('description', $lang, false) ?: $category->getTranslation('description', 'fr', false);
  $productsRouteName = $lang === 'en' ? 'products.en' : 'products';
@endphp

@section('title', ($lang === 'fr' ? $catName . ' — Fab Sourcing' : $catName . ' — Fab Sourcing'))

@section('description', $catDesc ? Str::limit(strip_tags($catDesc), 160) : ($lang === 'fr' ? 'Fabrication métallique sur mesure en Bulgarie et en Roumanie.' : 'Custom metalwork fabrication in Bulgaria and Romania.'))

@push('seo')
<x-seo
  :title="$catName . ' — Fab Sourcing'"
  :description="$catDesc ? Str::limit(strip_tags($catDesc), 155) : ($lang === 'fr' ? 'Fabrication métallique sur mesure en Bulgarie et en Roumanie.' : 'Custom metalwork fabrication in Bulgaria and Romania.')"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en']"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@push('scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "{{ $lang === 'fr' ? 'Accueil' : 'Home' }}",
      "item": "{{ route('home', $lang) }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{ $lang === 'fr' ? 'Produits' : 'Products' }}",
      "item": "{{ route($lang === 'en' ? 'products.en' : 'products', $lang) }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ addslashes($catName) }}",
      "item": "{{ request()->url() }}"
    }
  ]
}
</script>
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
          <a href="{{ route($productsRouteName, $lang) }}">{{ $lang === 'fr' ? 'Produits' : 'Products' }}</a>
          <span>/</span>
          <span>{{ $catName }}</span>
        </div>
        <h1 class="h-1"><em>{{ $catName }}</em></h1>
      </div>
      @if($catDesc)
        <div><p class="lede">{{ $catDesc }}</p></div>
      @endif
    </div>
  </div>
</div>

{{-- Products grid --}}
<section class="section">
  <div class="container">

    @if($products->isEmpty())
      <div style="text-align:center; padding:80px 0">
        <div class="eyebrow no-line" style="margin-bottom:16px">
          {{ $lang === 'fr' ? 'Catalogue en cours' : 'Catalogue in progress' }}
        </div>
        <h2 class="h-3">
          {{ $lang === 'fr'
            ? 'Les produits de cette catégorie seront disponibles prochainement.'
            : 'Products in this category will be available soon.' }}
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'En attendant, envoyez-nous vos plans — nous fabriquons sur mesure.'
            : 'In the meantime, send us your drawings — we fabricate to specification.' }}
        </p>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="margin-top:28px">
          {{ $lang === 'fr' ? 'Demander un devis' : 'Request a quote' }}
          <span class="arrow">→</span>
        </a>
      </div>
    @else
      <div class="products-grid reveal">
        @foreach($products as $product)
          @php
            $prodName = $product->getTranslation('name', $lang, false) ?: $product->getTranslation('name', 'fr', false);
            $prodDesc = $product->getTranslation('short_description', $lang, false) ?: $product->getTranslation('short_description', 'fr', false);
            $detailRouteName = $lang === 'en' ? 'products.detail.en' : 'products.detail';
          @endphp
          <a href="{{ route($detailRouteName, ['lang' => $lang, 'categorySlug' => $category->getSlugForLang($lang), 'productSlug' => $product->getSlugForLang($lang)]) }}"
             class="product-card">
            <div class="product-card-img">
              @if($product->main_image_url)
                <img src="{{ $product->main_image_url }}" alt="{{ $prodName }}" loading="lazy">
              @else
                <div class="img-placeholder">
                  {{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}
                </div>
              @endif
              @if($product->tag_number)
                <span class="product-card-tag">{{ $product->tag_number }}</span>
              @endif
            </div>
            <div class="product-card-body">
              <h2 class="product-card-title">{{ $prodName }}</h2>
              @if($prodDesc)
                <p class="product-card-desc">{{ Str::limit(strip_tags($prodDesc), 90) }}</p>
              @endif
              <span class="btn-link" style="margin-top:16px; display:inline-flex; align-items:center; gap:6px">
                {{ $lang === 'fr' ? 'En savoir plus' : 'Learn more' }}
                <span class="arrow">→</span>
              </span>
            </div>
          </a>
        @endforeach
      </div>
    @endif

  </div>
</section>

{{-- Other categories strip --}}
<section class="section-tight" style="border-top:1px solid rgba(15,30,61,0.08)">
  <div class="container">
    <div class="eyebrow reveal" style="margin-bottom:28px">
      {{ $lang === 'fr' ? 'Autres catégories' : 'Other categories' }}
    </div>
    <div style="display:flex; flex-wrap:wrap; gap:10px" class="reveal">
      @foreach(\App\Models\ProductCategory::where('published', true)->where('id', '!=', $category->id)->orderBy('sort_order')->get() as $otherCat)
        @php $otherName = $otherCat->getTranslation('name', $lang, false) ?: $otherCat->getTranslation('name', 'fr', false); @endphp
        <a href="{{ $otherCat->getUrlForLang($lang) }}" class="filter-chip">{{ $otherName }}</a>
      @endforeach
    </div>
  </div>
</section>

@endsection
