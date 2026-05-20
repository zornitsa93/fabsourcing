@extends('layouts.web')

@section('title', $lang === 'fr'
    ? "Pourquoi l'Europe de l'Est — Fab Sourcing"
    : 'Why Eastern Europe — Fab Sourcing')

@section('description', $lang === 'fr'
    ? "Réduisez vos coûts de fabrication de 30 à 50 % en externalisant en Europe de l'Est. Main-d'œuvre qualifiée, logistique rapide, proximité culturelle."
    : 'Reduce your manufacturing costs by 30–50% by outsourcing to Eastern Europe. Skilled labour, fast logistics, cultural proximity.')

@push('seo')
<x-seo
  :title="$lang === 'fr'
    ? 'Pourquoi l\'Europe de l\'Est — Fab Sourcing'
    : 'Why Eastern Europe — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Bulgarie et Roumanie : qualité aux normes européennes, jusqu\'à 30–50 % d\'économies. Découvrez les avantages de la sous-traitance en Europe de l\'Est.'
    : 'Bulgaria and Romania: European-standard quality, up to 30–50% savings. Discover the advantages of Eastern European industrial subcontracting.'"
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
          <span>{{ $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            L'Europe de l'Est,<br>votre <em>avantage compétitif</em>
          @else
            Eastern Europe,<br>your <em>competitive edge</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? "Fab Sourcing travaille avec des ateliers partenaires en Bulgarie et Roumanie. Voici pourquoi de plus en plus d'industriels français font le même choix."
            : 'Fab Sourcing works with partner workshops in Bulgaria and Romania. Here is why more and more French manufacturers are making the same choice.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- 5 Advantages --}}
<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? '5 raisons clés' : '5 key reasons' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Les atouts de <em>l'Europe de l'Est</em>
          @else
            The advantages of <em>Eastern Europe</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Des avantages structurels durables — pas une simple aubaine conjoncturelle — qui expliquent l\'attrait croissant de cette région pour la sous-traitance industrielle.'
            : 'Durable structural advantages — not a short-lived opportunity — that explain the growing appeal of this region for industrial subcontracting.' }}
        </p>
      </div>
    </div>

    @php
    $advantages = $lang === 'fr' ? [
      [
        'num'   => '01',
        'title' => 'Réduction des coûts de 30 à 50 %',
        'desc'  => 'Les coûts de main-d\'œuvre qualifiée en métallurgie sont 40 à 60 % inférieurs à ceux de la France ou de l\'Allemagne. Cette différence se traduit directement sur le prix de revient, sans compromis sur la qualité.',
        'stat'  => '30–50 %',
        'statlabel' => 'd\'économies',
      ],
      [
        'num'   => '02',
        'title' => "Main-d'œuvre qualifiée",
        'desc'  => 'Les pays d\'Europe de l\'Est disposent d\'une longue tradition industrielle. Les soudeurs, chaudronniers et techniciens sont formés dans des filières solides et respectent les exigences européennes en vigueur.',
        'stat'  => 'Normes',
        'statlabel' => 'européennes',
      ],
      [
        'num'   => '03',
        'title' => 'Cadre UE',
        'desc'  => 'La Bulgarie et la Roumanie sont membres de l\'UE : pas de droits de douane, pas de blocages logistiques. La livraison vers la France s\'effectue en 3 à 4 jours par route.',
        'stat'  => '3–4 j',
        'statlabel' => 'livraison France',
      ],
      [
        'num'   => '04',
        'title' => 'Proximité culturelle & géographique',
        'desc'  => 'Le fuseau horaire est identique ou proche (+1 h). Les interlocuteurs parlent souvent français ou anglais. La culture professionnelle est proche de la nôtre, ce qui facilite la communication.',
        'stat'  => '+1 h',
        'statlabel' => 'décalage horaire max',
      ],
      [
        'num'   => '05',
        'title' => 'Base industrielle solide',
        'desc'  => 'Ces pays hébergent des ateliers modernes équipés de machines CNC, robots de soudage, lignes de traitement de surface. L\'investissement industriel européen y est ancré durablement.',
        'stat'  => 'UE',
        'statlabel' => 'intégration économique',
      ],
    ] : [
      [
        'num'   => '01',
        'title' => 'Cost reduction of 30–50%',
        'desc'  => 'Labour costs 40–60% lower than in France or Germany. This difference translates directly into lower unit costs without compromising on quality.',
        'stat'  => '30–50%',
        'statlabel' => 'savings',
      ],
      [
        'num'   => '02',
        'title' => 'Skilled workforce',
        'desc'  => 'Eastern European countries have a long industrial tradition. Welders, fabricators and technicians are trained in solid curricula and comply with applicable European standards.',
        'stat'  => 'Standards',
        'statlabel' => 'European',
      ],
      [
        'num'   => '03',
        'title' => 'EU framework',
        'desc'  => 'Bulgaria and Romania are EU members: no customs duties, no logistical blocks. Delivery to France takes 3–4 days by road.',
        'stat'  => '3–4 d',
        'statlabel' => 'delivery to FR',
      ],
      [
        'num'   => '04',
        'title' => 'Cultural & geographic proximity',
        'desc'  => 'The time zone is identical or close (+1 h). Counterparts often speak French or English. The professional culture is close to ours, facilitating communication.',
        'stat'  => '+1 h',
        'statlabel' => 'max time difference',
      ],
      [
        'num'   => '05',
        'title' => 'Solid industrial base',
        'desc'  => 'These countries host modern workshops equipped with CNC machines, welding robots, and surface treatment lines. European industrial investment is deeply embedded in these economies.',
        'stat'  => 'EU',
        'statlabel' => 'economic integration',
      ],
    ];
    @endphp

    <div class="why-stat-callout reveal">
      <div class="why-stat-callout-figure">30–50%</div>
      <div class="why-stat-callout-body">
        <div class="why-stat-callout-label">
          {{ $lang === 'fr' ? "D'économies vs Europe de l'Ouest" : 'Savings vs Western Europe' }}
        </div>
        <div class="why-stat-callout-note">
          {{ $lang === 'fr'
            ? "Coûts de main-d'œuvre 40–60 % inférieurs à la France ou l'Allemagne"
            : 'Labour costs 40–60% lower than in France or Germany' }}
        </div>
      </div>
    </div>

    <div class="advantage-grid reveal">
      @foreach($advantages as $adv)
        <div class="advantage-card">
          <div class="advantage-header">
            <span class="advantage-num">{{ $adv['num'] }}</span>
          </div>
          <h3 class="advantage-title">{{ $adv['title'] }}</h3>
          <p class="advantage-desc">{{ $adv['desc'] }}</p>
        </div>
      @endforeach
    </div>

  </div>
</section>

{{-- Comparison table --}}
<section class="section-tight" style="background:#f4f6f9">
  <div class="container">
    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Comparatif' : 'Comparison' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Europe de l'Est <em>vs</em> Asie
          @else
            Eastern Europe <em>vs</em> Asia
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="body">
          {{ $lang === 'fr'
            ? 'Pour les industriels qui hésitent entre les deux zones, voici une comparaison objective sur les critères qui comptent.'
            : 'For manufacturers hesitating between the two zones, here is an objective comparison on the criteria that matter.' }}
        </p>
      </div>
    </div>

    @php
    $rows = $lang === 'fr' ? [
      ['criterion' => 'Délai de livraison',  'east' => '3–4 jours',     'asia' => '4–6 semaines'],
      ['criterion' => 'Coût de transport',   'east' => 'Faible (UE)',    'asia' => 'Élevé (maritime)'],
      ['criterion' => 'Droits de douane',    'east' => 'Aucun (UE)',     'asia' => '0–10 %'],
      ['criterion' => 'Qualité / normes',    'east' => 'Normes UE',      'asia' => 'Variable'],
      ['criterion' => 'Communication',       'east' => 'Francophone',    'asia' => 'Difficile'],
      ['criterion' => 'Suivi sur site',      'east' => 'Zone UE',         'asia' => 'Long courrier'],
    ] : [
      ['criterion' => 'Delivery time',       'east' => '3–4 days',       'asia' => '4–6 weeks'],
      ['criterion' => 'Transport cost',      'east' => 'Low (EU)',        'asia' => 'High (maritime)'],
      ['criterion' => 'Customs duties',      'east' => 'None (EU)',       'asia' => '0–10 %'],
      ['criterion' => 'Quality / standards', 'east' => 'EU standards',    'asia' => 'Variable'],
      ['criterion' => 'Communication',       'east' => 'French-speaking contact', 'asia' => 'Difficult'],
      ['criterion' => 'On-site monitoring',  'east' => 'EU zone',         'asia' => 'Long-haul'],
    ];
    @endphp

    <div class="comparison-wrap reveal">
      <table class="comparison-table">
        <thead>
          <tr>
            <th>{{ $lang === 'fr' ? 'Critère' : 'Criterion' }}</th>
            <th class="comparison-east">
              {{ $lang === 'fr' ? "Europe de l'Est" : 'Eastern Europe' }}
              <span class="comparison-badge">Fab Sourcing</span>
            </th>
            <th class="comparison-asia">{{ $lang === 'fr' ? 'Asie' : 'Asia' }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $row)
            <tr>
              <td class="comparison-criterion">{{ $row['criterion'] }}</td>
              <td class="comparison-east comparison-val-east"
                  data-label="{{ $lang === 'fr' ? \"Europe de l'Est\" : 'Eastern Europe' }}">
                <span class="comparison-check">✓</span>
                {{ $row['east'] }}
              </td>
              <td class="comparison-asia comparison-val-asia"
                  data-label="{{ $lang === 'fr' ? 'Asie' : 'Asia' }}">{{ $row['asia'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Prêt à franchir le pas ?' : 'Ready to take the step?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Obtenez votre <em>premier devis</em>
          @else
            Get your <em>first quote</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Envoyez vos plans ou décrivez votre besoin. Réponse sous 48 heures avec une analyse technique et un prix.'
            : 'Send your drawings or describe your need. Reply within 48 hours with a technical analysis and a price.' }}
        </p>
      </div>
      <div>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          {{ $lang === 'fr' ? 'Demander un devis' : 'Request a quote' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
