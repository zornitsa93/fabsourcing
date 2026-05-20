@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Méthode Fab Sourcing — Sous-traitance industrielle'
    : 'Fab Sourcing Method — Industrial Subcontracting')

@section('description', $lang === 'fr'
    ? 'Notre processus en 7 étapes : analyse du besoin, étude technique, sélection fournisseur, prototype, production, contrôle qualité, livraison.'
    : 'Our 7-step process: needs analysis, technical study, supplier selection, prototype, production, quality control, delivery.')

@push('seo')
<x-seo
  :title="$lang === 'fr'
    ? 'Notre Méthodologie — Fab Sourcing'
    : 'Our Methodology — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Découvrez notre processus en 7 étapes pour une externalisation industrielle réussie en Bulgarie et en Roumanie. De l\'analyse au suivi qualité.'
    : 'Discover our 7-step process for successful industrial outsourcing in Bulgaria and Romania. From analysis to quality monitoring.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en'] ?? null"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
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
    @php
    $stepIcons = [
      '01' => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
      '02' => '<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
      '03' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9"/>',
      '04' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
      '05' => '<path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
      '06' => '<path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
      '07' => '<path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0121 11.414V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>',
    ];
    @endphp
    <div class="method-timeline reveal">
      @forelse($steps as $step)
        @php
          $title = $step->getTranslation('title',       $lang, false) ?: $step->getTranslation('title',       'fr', false);
          $desc  = $step->getTranslation('description', $lang, false) ?: $step->getTranslation('description', 'fr', false);
          $icon  = $stepIcons[$step->number] ?? '';
        @endphp
        <div class="method-step">
          <div class="method-step-badge">{{ $step->number }}</div>
          <div class="method-step-content">
            <div class="method-step-header">
              @if($icon)
                <svg class="method-step-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg>
              @endif
              <h2 class="method-step-title">{{ $title }}</h2>
            </div>
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
        'desc'  => 'Communication ouverte tout au long du projet : vous savez où en est votre commande et avez accès aux informations qui comptent.',
      ],
      [
        'num'   => '02',
        'title' => 'Interlocuteur unique francophone',
        'desc'  => 'Un seul interlocuteur en français pour gérer la technique, la qualité et la logistique de votre projet, de A à Z.',
      ],
      [
        'num'   => '03',
        'title' => 'Respect des délais',
        'desc'  => 'Les délais convenus sont suivis rigoureusement. Notre équipe intervient dès qu\'une dérive est détectée pour protéger votre planning.',
      ],
    ] : [
      [
        'num'   => '01',
        'title' => 'Total transparency',
        'desc'  => 'Open communication throughout the project: you know where your order stands and have access to the information that matters.',
      ],
      [
        'num'   => '02',
        'title' => 'Single French-speaking contact',
        'desc'  => 'A single contact in French to manage the technical, quality and logistics aspects of your project, from start to finish.',
      ],
      [
        'num'   => '03',
        'title' => 'On-time delivery',
        'desc'  => 'Agreed deadlines are rigorously monitored. Our team acts as soon as any deviation is detected to protect your schedule.',
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
