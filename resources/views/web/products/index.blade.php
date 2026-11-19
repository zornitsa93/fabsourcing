@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Fabrication métallique sur mesure en Bulgarie'
    : 'Custom metal fabrication in Bulgaria – Fab Sourcing')

@section('description', $lang === 'fr'
    ? 'Escaliers, garde-corps, structures, cuves, racks, skids, portails : fabrication sur plan en Bulgarie, galvanisée ou peinte, livrée en France.'
    : 'Stairs, railings, steel structures, tanks, racks, skids, gates: made to your drawings in Bulgaria, galvanised or painted, delivered across Europe.')

@push('seo')
<x-seo
  :title="$lang === 'fr' ? 'Fabrication métallique sur mesure en Bulgarie' : 'Custom metal fabrication in Bulgaria – Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Escaliers, garde-corps, structures, cuves, racks, skids, portails : fabrication sur plan en Bulgarie, galvanisée ou peinte, livrée en France.'
    : 'Stairs, railings, steel structures, tanks, racks, skids, gates: made to your drawings in Bulgaria, galvanised or painted, delivered across Europe.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en'] ?? null"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@push('head')
<x-breadcrumb-ld :items="[[$lang === 'fr' ? 'Accueil' : 'Home', route('home', $lang)], [$lang === 'fr' ? 'Produits' : 'Products', null]]" />
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
          Nos fabrications<br><em>métalliques sur mesure</em>
        @else
          Custom metal<br><em>fabrication</em>
        @endif
      </h1>
      <p class="lede" style="margin-top:24px">
        {{ $lang === 'fr'
          ? 'Familles de produits métalliques fabriqués sur mesure en Bulgarie, selon vos plans et les normes européennes en vigueur.'
          : 'Families of metal products made to measure in Bulgaria, to your drawings and to current European standards.' }}
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
            Can't find what you are looking for?
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Envoyez-nous vos plans. Nous fabriquons sur mesure selon toute spécification technique.'
            : 'Send us your drawings. We manufacture to measure, to any technical specification.' }}
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
