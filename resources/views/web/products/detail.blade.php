@extends('layouts.web')

@php
  $prodName    = $product->getTranslation('name',              $lang, false) ?: $product->getTranslation('name',              'fr', false);
  $prodDesc    = $product->getTranslation('short_description', $lang, false) ?: $product->getTranslation('short_description', 'fr', false);
  $fullDesc    = $product->getTranslation('full_description',  $lang, false) ?: $product->getTranslation('full_description',  'fr', false);
  $features    = $product->getTranslation('features',          $lang, false) ?: $product->getTranslation('features',          'fr', false);
  $materials   = $product->getTranslation('materials',         $lang, false) ?: $product->getTranslation('materials',         'fr', false);
  $specs       = $product->getTranslation('specifications',    $lang, false) ?: $product->getTranslation('specifications',    'fr', false);
  $metaTitle   = $product->getTranslation('meta_title',        $lang, false) ?: $prodName;
  $metaDesc    = $product->getTranslation('meta_description',  $lang, false) ?: ($prodDesc ? Str::limit(strip_tags($prodDesc), 160) : '');
  $catName     = $category->getTranslation('name', $lang, false) ?: $category->getTranslation('name', 'fr', false);
  $productsUrl = route($lang === 'en' ? 'products.en' : 'products', $lang);
  $catUrl      = $category->getUrlForLang($lang);

  // All images: main + gallery for lightbox
  $allImages = array_filter(array_merge(
    $product->main_image_url ? [$product->main_image_url] : [],
    $product->gallery_urls ?? []
  ));
  $allImages = array_values($allImages);
@endphp

@section('title', $metaTitle . ' — Fab Sourcing')
@section('description', $metaDesc)

@push('seo')
<x-seo
  :title="$metaTitle . ' — Fab Sourcing'"
  :description="$metaDesc"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en'] ?? null"
  og-type="product"
  :og-image="$product->main_image_url ?? asset('images/og-default.jpg')"
/>
@endpush

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Product",
  "name": "{{ addslashes($prodName) }}",
  @if($metaDesc)"description": "{{ addslashes(Str::limit(strip_tags($metaDesc), 160)) }}",@endif
  @if($product->main_image_url)"image": "{{ $product->main_image_url }}",@endif
  "brand": {
    "@type": "Brand",
    "name": "Fab Sourcing"
  },
  "manufacturer": {
    "@type": "Organization",
    "name": "Fab Sourcing",
    "url": "{{ url('/') }}"
  },
  "offers": {
    "@type": "Offer",
    "priceCurrency": "EUR",
    "availability": "https://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "Fab Sourcing"
    }
  }
}
</script>
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
      "item": "{{ $catUrl }}"
    },
    {
      "@type": "ListItem",
      "position": 4,
      "name": "{{ addslashes($prodName) }}",
      "item": "{{ request()->url() }}"
    }
  ]
}
</script>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div style="border-bottom:1px solid rgba(15,30,61,0.08)">
  <div class="container">
    <div class="breadcrumb" style="padding:20px 0">
      <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
      <span>/</span>
      <a href="{{ $productsUrl }}">{{ $lang === 'fr' ? 'Produits' : 'Products' }}</a>
      <span>/</span>
      <a href="{{ $catUrl }}">{{ $catName }}</a>
      <span>/</span>
      <span>{{ $prodName }}</span>
    </div>
  </div>
</div>

{{-- Product detail --}}
<section class="section">
  <div class="container">
    <div class="product-detail-grid reveal">

      {{-- Left: images --}}
      <div class="product-detail-media">

        {{-- Main image --}}
        <div class="product-detail-main-img {{ count($allImages) ? 'clickable' : '' }}"
             @if(count($allImages)) data-lightbox="0" @endif>
          @if($product->main_image_url)
            <img src="{{ $product->main_image_url }}"
                 alt="{{ $prodName }}"
                 loading="eager"
                 style="width:100%; height:100%; object-fit:cover">
          @else
            <div class="img-placeholder">
              {{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}
            </div>
          @endif
          @if(count($allImages))
            <div class="product-zoom-hint">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35M11 8v6M8 11h6"/></svg>
              {{ $lang === 'fr' ? 'Agrandir' : 'Enlarge' }}
            </div>
          @endif
        </div>

        {{-- Gallery thumbnails --}}
        @if(count($product->gallery_urls))
          <div class="product-gallery-thumbs">
            @foreach($product->gallery_urls as $i => $url)
              <button class="product-gallery-thumb" data-lightbox="{{ $i + 1 }}" type="button">
                <img src="{{ $url }}" alt="{{ $prodName }} — {{ $i + 2 }}" loading="lazy">
              </button>
            @endforeach
          </div>
        @endif

      </div>

      {{-- Right: content --}}
      <div class="product-detail-content">

        <div class="eyebrow" style="margin-bottom:16px">{{ $catName }}</div>
        <h1 class="h-1">{{ $prodName }}</h1>

        @if($prodDesc)
          <p class="lede" style="margin-top:20px">{{ strip_tags($prodDesc) }}</p>
        @endif

        {{-- Full description --}}
        @if($fullDesc)
          <div class="product-rich-body" style="margin-top:32px">
            {!! \App\Services\TextLinker::linkify($fullDesc, $lang) !!}
          </div>
        @endif

        {{-- Features --}}
        @if($features)
          <div class="product-section" style="margin-top:32px">
            <h3 class="h-4" style="margin-bottom:16px">
              {{ $lang === 'fr' ? 'Caractéristiques' : 'Features' }}
            </h3>
            @if(is_array($features))
              <ul class="product-feature-list">
                @foreach($features as $feature)
                  @if(trim($feature))
                    <li>{{ $feature }}</li>
                  @endif
                @endforeach
              </ul>
            @else
              <div class="product-rich-body">{!! $features !!}</div>
            @endif
          </div>
        @endif

        {{-- Materials --}}
        @if($materials)
          <div class="product-section" style="margin-top:28px">
            <h3 class="h-4" style="margin-bottom:12px">
              {{ $lang === 'fr' ? 'Matériaux' : 'Materials' }}
            </h3>
            @if(is_array($materials))
              <p class="body">{{ implode(', ', array_filter($materials)) }}</p>
            @else
              <div class="product-rich-body">{!! $materials !!}</div>
            @endif
          </div>
        @endif

        {{-- Specifications --}}
        @if($specs)
          <div class="product-section" style="margin-top:28px">
            <h3 class="h-4" style="margin-bottom:12px">
              {{ $lang === 'fr' ? 'Spécifications' : 'Specifications' }}
            </h3>
            @if(is_array($specs))
              <ul class="product-feature-list">
                @foreach($specs as $spec)
                  @if(trim($spec))
                    <li>{{ $spec }}</li>
                  @endif
                @endforeach
              </ul>
            @else
              <div class="product-rich-body">{!! $specs !!}</div>
            @endif
          </div>
        @endif

        {{-- CTA --}}
        <div class="product-cta" style="margin-top:40px">
          @php
            $quoteMsg = $lang === 'fr'
              ? 'Je souhaite obtenir un devis pour le produit : ' . $prodName
              : 'I would like to request a quote for the product: ' . $prodName;
          @endphp
          <a href="{{ route('contact', $lang) }}?product={{ urlencode($prodName) }}"
             class="btn btn-primary" style="font-size:16px; padding:18px 28px">
            {{ $lang === 'fr' ? 'Demander un devis pour ce produit' : 'Request a quote for this product' }}
            <span class="arrow">→</span>
          </a>
          <a href="{{ $catUrl }}" class="btn btn-ghost" style="margin-top:12px">
            {{ $lang === 'fr' ? '← Retour à ' . $catName : '← Back to ' . $catName }}
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- Related products --}}
@if($related->isNotEmpty())
<section class="section-tight" style="border-top:1px solid rgba(15,30,61,0.08)">
  <div class="container">
    <div class="eyebrow reveal" style="margin-bottom:32px">
      {{ $lang === 'fr' ? 'Dans la même catégorie' : 'In the same category' }}
    </div>
    <div class="products-grid reveal" style="grid-template-columns: repeat({{ min(4, $related->count()) }}, 1fr)">
      @foreach($related as $rel)
        @php
          $relName = $rel->getTranslation('name', $lang, false) ?: $rel->getTranslation('name', 'fr', false);
          $relDesc = $rel->getTranslation('short_description', $lang, false) ?: $rel->getTranslation('short_description', 'fr', false);
          $detailRoute = $lang === 'en' ? 'products.detail.en' : 'products.detail';
        @endphp
        <a href="{{ route($detailRoute, ['lang' => $lang, 'categorySlug' => $category->getSlugForLang($lang), 'productSlug' => $rel->getSlugForLang($lang)]) }}"
           class="product-card">
          <div class="product-card-img">
            @if($rel->main_image_url)
              <img src="{{ $rel->main_image_url }}" alt="{{ $relName }}" loading="lazy">
            @else
              <div class="img-placeholder">{{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}</div>
            @endif
          </div>
          <div class="product-card-body">
            <h3 class="product-card-title">{{ $relName }}</h3>
            @if($relDesc)
              <p class="product-card-desc">{{ Str::limit(strip_tags($relDesc), 80) }}</p>
            @endif
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Lightbox overlay --}}
@if(count($allImages))
<div class="lightbox-overlay" id="lightbox" role="dialog" aria-modal="true" aria-label="{{ $lang === 'fr' ? 'Vue agrandie' : 'Enlarged view' }}">
  <button class="lightbox-close" id="lightbox-close" aria-label="{{ $lang === 'fr' ? 'Fermer' : 'Close' }}">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
  </button>
  @if(count($allImages) > 1)
    <button class="lightbox-prev" id="lightbox-prev" aria-label="Previous">‹</button>
    <button class="lightbox-next" id="lightbox-next" aria-label="Next">›</button>
  @endif
  <img class="lightbox-img" id="lightbox-img" src="" alt="{{ $prodName }}">
  <div class="lightbox-counter" id="lightbox-counter"></div>
</div>

@push('scripts')
<script>
(function () {
  var images  = @json($allImages);
  var current = 0;
  var overlay = document.getElementById('lightbox');
  var img     = document.getElementById('lightbox-img');
  var counter = document.getElementById('lightbox-counter');
  var prevBtn = document.getElementById('lightbox-prev');
  var nextBtn = document.getElementById('lightbox-next');

  function show(index) {
    current = ((index % images.length) + images.length) % images.length;
    img.src = images[current];
    if (counter) counter.textContent = (current + 1) + ' / ' + images.length;
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  // Trigger elements
  document.querySelectorAll('[data-lightbox]').forEach(function (el) {
    el.style.cursor = 'zoom-in';
    el.addEventListener('click', function () { show(parseInt(el.dataset.lightbox, 10)); });
  });

  document.getElementById('lightbox-close').addEventListener('click', close);
  overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
  if (prevBtn) prevBtn.addEventListener('click', function (e) { e.stopPropagation(); show(current - 1); });
  if (nextBtn) nextBtn.addEventListener('click', function (e) { e.stopPropagation(); show(current + 1); });

  document.addEventListener('keydown', function (e) {
    if (!overlay.classList.contains('open')) return;
    if (e.key === 'Escape')      close();
    if (e.key === 'ArrowLeft'  && prevBtn) show(current - 1);
    if (e.key === 'ArrowRight' && nextBtn) show(current + 1);
  });
})();
</script>
@endpush
@endif

@endsection
