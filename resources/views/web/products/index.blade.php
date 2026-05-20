@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Catalogue produits — Fab Sourcing'
    : 'Product catalogue — Fab Sourcing')

@section('description', $lang === 'fr'
    ? 'Structures métalliques, escaliers, garde-corps, bardages et plus encore. Fabrication sur mesure aux normes européennes en Bulgarie et en Roumanie.'
    : 'Steel structures, stairs, railings, cladding and more. Custom fabrication to European standards in Bulgaria and Romania.')

@push('seo')
<x-seo
  :title="$lang === 'fr' ? 'Notre Catalogue — Fab Sourcing' : 'Our Catalogue — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Catalogue de produits métalliques fabriqués en Bulgarie et en Roumanie : structures, escaliers, garde-corps, menuiseries, bardages et plus.'
    : 'Catalogue of metalwork products manufactured in Bulgaria and Romania: structures, stairs, railings, joinery, cladding and more.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en']"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@section('content')

{{-- Page hero — single column, let the card grid be the visual impact --}}
<div class="page-hero">
  <div class="container">
    <div class="reveal" style="max-width:700px">
      <div class="breadcrumb">
        <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
        <span>/</span>
        <span>{{ $lang === 'fr' ? 'Produits' : 'Products' }}</span>
      </div>
      <h1 class="h-1">
        @if($lang === 'fr')
          Notre <em>catalogue</em>
        @else
          Our <em>catalogue</em>
        @endif
      </h1>
      <p class="lede" style="margin-top:24px">
        {{ $lang === 'fr'
          ? 'Neuf familles de produits métalliques fabriqués sur mesure en Bulgarie et en Roumanie, selon vos plans et les normes européennes en vigueur.'
          : 'Nine families of custom metal products manufactured in Bulgaria and Romania, according to your specifications and applicable European standards.' }}
      </p>
    </div>
  </div>
</div>

{{-- Category grid --}}
<section class="section">
  <div class="container">

    @if($categories->isEmpty())
      <p class="lede">{{ $lang === 'fr' ? 'Catégories à venir.' : 'Categories coming soon.' }}</p>
    @else
      <div class="cat-grid reveal">
        @foreach($categories as $i => $cat)
          @php $num = str_pad($i + 1, 2, '0', STR_PAD_LEFT); @endphp
          <x-cat-card :cat="$cat" :lang="$lang" :num="$num" />
        @endforeach
      </div>
    @endif

  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Votre projet' : 'Your project' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Vous ne trouvez pas ce que vous cherchez ?
          @else
            Can't find what you're looking for?
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Envoyez-nous vos plans. Nous fabriquons sur mesure selon toute spécification technique.'
            : 'Send us your drawings. We fabricate to any technical specification.' }}
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
