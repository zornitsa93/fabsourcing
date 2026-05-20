@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'À propos de Fab Sourcing — Sous-traitance industrielle'
    : 'About Fab Sourcing — Industrial Subcontracting')

@section('description', $lang === 'fr'
    ? 'Fab Sourcing, expert en sourcing industriel en Bulgarie et Roumanie. Nous accompagnons PME, groupes internationaux et bureaux d\'études dans l\'externalisation de leur production.'
    : 'Fab Sourcing, industrial sourcing expert in Bulgaria and Romania. We support SMEs, international groups and engineering offices in outsourcing their production.')

@push('seo')
<x-seo
  :title="$lang === 'fr' ? 'À propos — Fab Sourcing' : 'About — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Fab Sourcing est spécialisé dans l\'accompagnement des entreprises françaises souhaitant externaliser leur production en Bulgarie ou en Roumanie.'
    : 'Fab Sourcing specialises in helping French companies outsource their production to Bulgaria or Romania.'"
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
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'À propos' : 'About' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Expert en sourcing industriel<br><em>Europe de l'Est</em>
          @else
            Eastern Europe<br><em>industrial sourcing expert</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Nous sommes spécialisés dans l\'accompagnement des entreprises françaises souhaitant externaliser leur production en Bulgarie ou en Roumanie.'
            : 'We specialise in helping French companies outsource their production to Bulgaria or Romania.' }}
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
            Three priorities,<br><em>one focus</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Chaque décision opérationnelle est guidée par ces trois missions : sécuriser, réduire, garantir.'
            : 'Every operational decision is guided by these three missions: secure, reduce, guarantee.' }}
        </p>
      </div>
    </div>

    <div class="values-grid reveal">
      @php
      $missions = $lang === 'fr' ? [
        ['num' => '01', 'title' => 'Sécuriser la chaîne',  'desc' => 'Sélectionner les ateliers adaptés, valider les process et accompagner chaque étape pour sécuriser vos projets industriels.'],
        ['num' => '02', 'title' => 'Réduire les coûts',    'desc' => 'Bénéficier des conditions compétitives offertes par l\'Europe de l\'Est, sans compromis sur la qualité.'],
        ['num' => '03', 'title' => 'Garantir la qualité',  'desc' => 'Inspection, suivi de production et conformité aux normes européennes à chaque étape.'],
      ] : [
        ['num' => '01', 'title' => 'Secure the chain',     'desc' => 'Selecting the right workshops, validating processes and supporting every step to secure your industrial projects.'],
        ['num' => '02', 'title' => 'Reduce costs',         'desc' => 'Benefit from the competitive conditions offered by Eastern Europe, with no compromise on quality.'],
        ['num' => '03', 'title' => 'Guarantee quality',    'desc' => 'Inspection, production monitoring and compliance with European standards at every step.'],
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
          'desc'  => 'Petites et moyennes entreprises industrielles qui souhaitent externaliser tout ou partie de leur production.',
        ],
        [
          'title' => 'Groupes internationaux',
          'desc'  => 'Sites industriels de grands groupes cherchant à diversifier leur sourcing ou à sécuriser leur approvisionnement.',
        ],
        [
          'title' => "Bureaux d'études",
          'desc'  => 'Bureaux d\'études et ingénieristes qui ont besoin d\'un partenaire de fabrication fiable pour leurs projets.',
        ],
      ] : [
        [
          'title' => 'Industrial SMEs',
          'desc'  => 'Small and medium-sized industrial companies looking to outsource all or part of their production.',
        ],
        [
          'title' => 'International groups',
          'desc'  => 'Industrial sites of large groups looking to diversify their sourcing or secure their supply.',
        ],
        [
          'title' => 'Engineering offices',
          'desc'  => 'Engineering offices and consultancies that need a reliable manufacturing partner for their projects.',
        ],
      ];
      @endphp
      @foreach($clients as $c)
        <div class="client-card">
          <h3 class="client-card-title">{{ $c['title'] }}</h3>
          <p class="client-card-desc">{{ $c['desc'] }}</p>
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
        <img src="{{ asset('images/thierry.jpeg') }}"
             alt="Thierry Sudol"
             loading="lazy"
             style="width:100%; height:100%; object-fit:cover">
      </div>
      <div class="team-card-body">
        <div class="eyebrow no-line" style="margin-bottom:8px">Fab Sourcing</div>
        <h2 class="h-3" style="margin-bottom:4px">Thierry Sudol</h2>
        <p style="font-family:var(--font-mono, monospace); font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:#6b7891; margin-bottom:24px">
          {{ $lang === 'fr' ? 'Responsable commercial & marketing' : 'Sales & Marketing Manager' }}
        </p>
        <p class="body" style="margin-bottom:32px">
          {{ $lang === 'fr'
            ? 'Thierry Sudol est votre interlocuteur unique chez Fab Sourcing. Il vous accompagne de la demande de devis jusqu\'à la livraison, en français, avec un suivi personnalisé de votre projet.'
            : 'Thierry Sudol is your single point of contact at Fab Sourcing. He supports you from the initial quote request through to delivery, in French, with personalised follow-up of your project.' }}
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
