@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Services de sous-traitance industrielle — Fab Sourcing'
    : 'Industrial Subcontracting Services — Fab Sourcing')

@section('description', $lang === 'fr'
    ? "Sourcing industriel, sous-traitance, industrialisation, gestion logistique et contrôle qualité — Fab Sourcing coordonne votre production en Bulgarie et en Roumanie."
    : 'Industrial sourcing, subcontracting, industrialization, logistics management and quality control — Fab Sourcing coordinates your production in Bulgaria and Romania.')

@push('seo')
<x-seo
  :title="$lang === 'fr' ? 'Services de sous-traitance industrielle — Fab Sourcing' : 'Industrial Subcontracting Services — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Sourcing industriel, sous-traitance, industrialisation, gestion logistique et contrôle qualité — Fab Sourcing coordonne votre production en Bulgarie et en Roumanie.'
    : 'Industrial sourcing, subcontracting, industrialization, logistics management and quality control — Fab Sourcing coordinates your production in Bulgaria and Romania.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en']"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    @if($page?->hero_image)
      <div class="page-hero-grid reveal" style="align-items:center">
        <div>
          <div class="breadcrumb">
            <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
            <span>/</span>
            <span>{{ $lang === 'fr' ? 'Services' : 'Services' }}</span>
          </div>
          <h1 class="h-1">
            @if($lang === 'fr')
              Nos services de<br><em>sous-traitance</em>
            @else
              Our <em>services</em>
            @endif
          </h1>
          <p class="lede" style="margin-top:24px">
            {{ $page->getTranslation('hero_lede', $lang, false)
              ?: ($lang === 'fr'
                ? "De la pièce unitaire à la grande série, Fab Sourcing coordonne l'ensemble de la chaîne de fabrication depuis un réseau d'usines partenaires en Bulgarie et en Roumanie."
                : 'From unit production to large series, Fab Sourcing coordinates the entire manufacturing chain through a network of partner workshops in Bulgaria and Romania.') }}
          </p>
        </div>
        <div>
          <img
            src="{{ Storage::url($page->hero_image) }}"
            loading="eager"
            fetchpriority="high"
            width="800" height="500"
            alt="{{ $lang === 'fr' ? 'Coordination de production industrielle en Europe de l\'Est' : 'Industrial production coordination in Eastern Europe' }}"
            class="services-hero-img">
        </div>
      </div>
    @else
      <div class="reveal" style="max-width:700px">
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'Services' : 'Services' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Nos services de<br><em>sous-traitance</em>
          @else
            Our <em>services</em>
          @endif
        </h1>
        <p class="lede" style="margin-top:24px">
          {{ $page?->getTranslation('hero_lede', $lang, false)
            ?: ($lang === 'fr'
              ? "De la pièce unitaire à la grande série, Fab Sourcing coordonne l'ensemble de la chaîne de fabrication depuis un réseau d'usines partenaires en Bulgarie et en Roumanie."
              : 'From unit production to large series, Fab Sourcing coordinates the entire manufacturing chain through a network of partner workshops in Bulgaria and Romania.') }}
        </p>
      </div>
    @endif
  </div>
</div>

{{-- Services bento grid --}}
<section class="section">
  <div class="container">

    <div class="section-intro reveal" style="margin-bottom:48px">
      <div class="eyebrow">{{ $lang === 'fr' ? 'Ce que nous proposons' : 'What we offer' }}</div>
      <h2 class="h-2" style="margin-top:16px">
        @if($lang === 'fr')
          Cinq services, <em>une coordination totale</em>
        @else
          Five services, <em>total coordination</em>
        @endif
      </h2>
    </div>

    @if($services->isNotEmpty())
      @php
      $serviceIcons = [
        'sourcing-industriel' =>
          '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607Z" />',
        'sous-traitance' =>
          '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />',
        'industrialisation' =>
          '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0015 0m-15 0a7.5 7.5 0 1115 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077l1.41-.513m14.095-5.13l1.41-.513M5.106 17.785l1.15-.964m11.49-9.642l1.149-.964M7.501 19.795l.75-1.3m7.5-12.99l.75-1.3m-6.063 16.658l.26-1.477m2.605-14.772l.26-1.477m0 17.726l-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205L12 12m6.894 5.785l-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864l-1.41-.513M4.954 9.435l-1.41-.514M12 12v3.795" />',
        'gestion-logistique' =>
          '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />',
        'controle-qualite' =>
          '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />',
      ];
      @endphp

      <div class="services-grid reveal">
        @foreach($services as $service)
          @php
            $title    = $service->getTranslation('title', $lang, false) ?: $service->getTranslation('title', 'fr', false);
            $desc     = $service->getTranslation('description', $lang, false) ?: $service->getTranslation('description', 'fr', false);
            $iconPath = $serviceIcons[$service->slug] ?? '';
          @endphp
          <div class="service col-{{ $service->col_span }}{{ $service->featured ? ' featured' : '' }}">
            @if($iconPath)
              <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">{!! $iconPath !!}</svg>
            @endif
            <span class="service-num">{{ $service->number }}</span>
            <h2 class="service-title">{{ $title }}</h2>
            <p class="service-desc">{{ $desc }}</p>
          </div>
        @endforeach
      </div>
    @else
      <p class="lede">{{ $lang === 'fr' ? 'Services à venir.' : 'Services coming soon.' }}</p>
    @endif

  </div>
</section>

{{-- Why choose us strip --}}
<section class="section-tight" style="border-top:1px solid rgba(15,30,61,0.08); border-bottom:1px solid rgba(15,30,61,0.08)">
  <div class="container">
    <div class="values-grid reveal">
      @php
      $values = $lang === 'fr' ? [
        [
          'num'   => '01',
          'title' => 'Qualité contrôlée',
          'desc'  => 'Inspection, suivi de production et conformité aux normes européennes assurés tout au long du processus.',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />',
        ],
        [
          'num'   => '02',
          'title' => 'Respect des délais',
          'desc'  => 'Transport optimisé vers la France en 3 à 4 jours par route et suivi rigoureux du planning de production.',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />',
        ],
        [
          'num'   => '03',
          'title' => 'Interlocuteur unique francophone',
          'desc'  => 'Un seul point de contact en français pour gérer la technique, la qualité et la logistique de votre projet.',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        ],
      ] : [
        [
          'num'   => '01',
          'title' => 'Quality controlled',
          'desc'  => 'Inspection, production monitoring and compliance with European standards throughout the process.',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />',
        ],
        [
          'num'   => '02',
          'title' => 'On-time delivery',
          'desc'  => 'Optimised transport to France in 3 to 4 days by road, with rigorous production schedule monitoring.',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />',
        ],
        [
          'num'   => '03',
          'title' => 'Single French-speaking contact',
          'desc'  => 'One single point of contact in French to manage the technical, quality and logistics aspects of your project.',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
        ],
      ];
      @endphp
      @foreach($values as $v)
        <div class="value">
          <svg class="value-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">{!! $v['icon'] !!}</svg>
          <h3 class="value-title">{{ $v['title'] }}</h3>
          <p class="value-desc">{{ $v['desc'] }}</p>
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
        <div class="eyebrow">{{ $lang === 'fr' ? 'Discutons de votre projet' : "Let's discuss your project" }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Demandez votre <em>devis gratuit</em>
          @else
            Request your <em>free quote</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Envoyez-nous vos plans, nous revenons avec une analyse technique et un prix sous 48 heures.'
            : 'Send us your drawings, we get back with a technical analysis and price within 48 hours.' }}
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
