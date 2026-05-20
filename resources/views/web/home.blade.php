@extends('layouts.web')

@php
  $metaTitle   = $page?->getTranslation('meta_title', $lang, false)
               ?: ($lang === 'fr' ? 'Fab Sourcing — Sous-traitance industrielle en Bulgarie et en Roumanie' : 'Fab Sourcing — Industrial Outsourcing in Bulgaria and Romania');
  $metaDesc    = $page?->getTranslation('meta_description', $lang, false)
               ?: ($lang === 'fr' ? "Externalisez votre production métallurgique en Europe de l'Est. Qualité aux normes européennes, jusqu'à 30–50 % d'économies, délais maîtrisés." : 'Outsource your metalwork production to Eastern Europe. European-standard quality, up to 30–50% savings, controlled lead times.');
  $heroHeading = $page?->getTranslation('hero_heading', $lang, false)
               ?: ($lang === 'fr' ? "Fabrication\nmétallique\neuropéenne,\ncoûts réduits" : "European\nmetalwork,\nreduced\ncosts");
  $heroLede    = $page?->getTranslation('hero_lede', $lang, false)
               ?: ($lang === 'fr' ? "Fab Sourcing vous connecte à des ateliers partenaires en Europe de l'Est : métallerie, structures acier, chaudronnerie. Mêmes normes que la France, jusqu'à 30–50 % d'économies." : "Fab Sourcing connects you with partner workshops in Eastern Europe: metalwork, steel structures, fabrication. Same standards as Western Europe, up to 30–50% savings.");
  $heroLines   = array_filter(explode("\n", $heroHeading));
@endphp

@section('title', $metaTitle)
@section('description', $metaDesc)

@push('seo')
<x-seo
  :title="$metaTitle"
  :description="$metaDesc"
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
  "@type": "Organization",
  "name": "Fab Sourcing",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "FR"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "sales",
    "email": "contact@fab-sourcing.fr"
  }
}
</script>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════
     1. HERO — split editorial layout
══════════════════════════════════════════════════════ --}}
<section class="hero-a">
  <div class="container">
    <div class="hero-a-grid reveal">

      {{-- Left: copy --}}
      <div>
        <div class="eyebrow">
          {{ $lang === 'fr' ? 'Externalisation industrielle en Europe de l’Est' : 'Industrial outsourcing in Eastern Europe' }}
        </div>

        <h1 class="hero-a-headline" style="margin-top:24px">
          @foreach($heroLines as $line)
            <span class="line">{{ $line }}</span>
          @endforeach
        </h1>

        <div class="hero-a-meta">
          <p class="lede">{{ $heroLede }}</p>
          <div style="display:flex; gap:12px; flex-wrap:wrap">
            <a href="{{ route('contact', $lang) }}" class="btn btn-primary">
              {{ $lang === 'fr' ? 'Obtenir un devis' : 'Get a quote' }}
              <span class="arrow">→</span>
            </a>
            <a href="{{ route('services', $lang) }}" class="btn btn-ghost">
              {{ $lang === 'fr' ? 'Nos services' : 'Our services' }}
            </a>
          </div>
        </div>
      </div>

      {{-- Right: image --}}
      <div class="hero-a-image">
        @if($page?->hero_image)
          <img src="{{ Storage::url($page->hero_image) }}"
               alt="{{ $lang === 'fr' ? 'Atelier Fab Sourcing' : 'Fab Sourcing workshop' }}"
               loading="eager"
               style="width:100%; height:100%; object-fit:cover; border-radius:inherit">
        @else
          <div class="img-placeholder">
            {{ $lang === 'fr' ? 'Photo atelier · à venir' : 'Workshop photo · coming soon' }}
          </div>
        @endif
        {{-- <div class="hero-img-tag">
          <span class="dot"></span>
          Sofia, Bulgarie
        </div> --}}
      </div>

    </div>
  </div>
</section>

{{-- Stat ribbon --}}
<div class="stat-ribbon reveal">
  <div class="container">
    <div class="stat-grid">
      <div class="stat">
        <div class="stat-value">30–50<sup>%</sup></div>
        <span class="stat-label">{{ $lang === 'fr' ? 'd\'économies réalisées' : 'savings achieved' }}</span>
      </div>
      <div class="stat">
        <div class="stat-value">{{ $lang === 'fr' ? '3–4 j' : '3–4 d' }}</div>
        <span class="stat-label">{{ $lang === 'fr' ? 'livraison en France' : 'delivery to France' }}</span>
      </div>
      <div class="stat">
        <div class="stat-value">{{ $lang === 'fr' ? 'UE' : 'EU' }}</div>
        <span class="stat-label">{{ $lang === 'fr' ? 'Cadre réglementaire' : 'Regulatory framework' }}</span>
      </div>
      <div class="stat">
        <div class="stat-value">Francophone</div>
        <span class="stat-label">{{ $lang === 'fr' ? 'Interlocuteur unique' : 'Single point of contact' }}</span>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════════════════
     2. SERVICES PREVIEW
══════════════════════════════════════════════════════ --}}
<section class="section">
  <div class="container">

    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Ce que nous faisons' : 'What we do' }}</div>
        <h2 class="h-1" style="margin-top:16px">
          @if($lang === 'fr')
            Nos <em>services</em>
          @else
            Our <em>services</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <p class="lede">
          {{ $page?->getTranslation('services_lede', $lang, false)
            ?: ($lang === 'fr'
              ? 'De la pièce unitaire à la série, de la découpe au traitement de surface, nous couvrons l\'ensemble de la chaîne de fabrication métallique.'
              : 'From one-off parts to series production, from cutting to surface treatment, we cover the entire metalwork manufacturing chain.') }}
        </p>
        <a href="{{ route('services', $lang) }}" class="btn-link" style="margin-top:20px; display:inline-flex; align-items:center; gap:8px">
          {{ $lang === 'fr' ? 'Voir tous les services' : 'View all services' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>

    @if($services->isNotEmpty())
      <div class="services-grid reveal">
        @foreach($services as $service)
          <div class="service col-{{ $service->col_span }}{{ $service->featured ? ' featured' : '' }}">
            <span class="service-num">{{ $service->number }}</span>
            <h3 class="service-title">{{ $service->getTranslation('title', $lang, false) ?: $service->getTranslation('title', 'fr', false) }}</h3>
            <p class="service-desc">{{ $service->getTranslation('description', $lang, false) ?: $service->getTranslation('description', 'fr', false) }}</p>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</section>

{{-- ═══════════════════════════════════════════════════
     3. FEATURED PRODUCT CATEGORIES
══════════════════════════════════════════════════════ --}}
@if($featuredCategories->isNotEmpty())
<section class="section-tight" style="border-top:1px solid rgba(15,30,61,0.08)">
  <div class="container">

    <div class="section-head reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Notre catalogue' : 'Our catalogue' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Nos <em>familles</em> de produits
          @else
            Our product <em>families</em>
          @endif
        </h2>
      </div>
      <div class="section-head-right">
        <a href="{{ route('products', $lang) }}" class="btn-link" style="display:inline-flex; align-items:center; gap:8px">
          {{ $lang === 'fr' ? 'Voir le catalogue complet' : 'View full catalogue' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>

    <div class="cat-grid reveal">
      @foreach($featuredCategories as $i => $cat)
        @php $catNum = str_pad($i + 1, 2, '0', STR_PAD_LEFT); @endphp
        <x-cat-card :cat="$cat" :lang="$lang" :num="$catNum" />
      @endforeach
    </div>

  </div>
</section>
@endif

{{-- Featured products section hidden --}}

{{-- ═══════════════════════════════════════════════════
     4. WHY EASTERN EUROPE — light teaser
══════════════════════════════════════════════════════ --}}
@php
$teaserPoints = $lang === 'fr' ? [
  ['label' => 'Coûts réduits',          'sub' => 'Production compétitive'],
  ['label' => "Main-d'œuvre qualifiée", 'sub' => 'Ingénieurs et techniciens'],
  ['label' => 'Base industrielle solide','sub' => 'Capacités modernes et flexibles'],
  ['label' => 'Proximité culturelle',   'sub' => 'Faible décalage horaire'],
] : [
  ['label' => 'Lower costs',            'sub' => 'Competitive production'],
  ['label' => 'Skilled workforce',      'sub' => 'Engineers and technicians'],
  ['label' => 'Strong industrial base', 'sub' => 'Modern and flexible capacity'],
  ['label' => 'Cultural proximity',     'sub' => 'Minimal time difference'],
];
@endphp
<section class="section" style="background:#f4f6f9">
  <div class="container">
    <div class="why-teaser-grid reveal">

      <div class="why-teaser-copy">
        <div class="eyebrow">{{ $lang === 'fr' ? "Pourquoi l'Europe de l'Est" : 'Why Eastern Europe' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Qualité européenne,<br><em>coûts maîtrisés</em>
          @else
            European quality,<br><em>controlled costs</em>
          @endif
        </h2>
        <p class="body" style="margin-top:16px; color:#4a5568">
          {{ $lang === 'fr'
            ? "Ateliers partenaires en Bulgarie et Roumanie — normes UE, livraison en 3 à 4 jours, coûts de main-d'œuvre 40–60 % inférieurs."
            : 'Partner workshops in Bulgaria and Romania — EU standards, 3–4 day delivery, labour costs 40–60% lower.' }}
        </p>
        <div style="margin-top:28px">
          <a href="{{ route('why', $lang) }}" class="btn btn-primary">
            {{ $lang === 'fr' ? 'Découvrir les atouts' : 'Discover the advantages' }}
            <span class="arrow">→</span>
          </a>
        </div>
      </div>

      <div class="why-teaser-points">
        @foreach($teaserPoints as $pt)
          <div class="why-teaser-point">
            <svg class="why-teaser-icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M4 10.5l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div>
              <div class="why-teaser-label">{{ $pt['label'] }}</div>
              <div style="font-size:12px; color:#718096; margin-top:2px; font-family: var(--font-mono, monospace); letter-spacing:0.04em">{{ $pt['sub'] }}</div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════════════
     5. FINAL CTA
══════════════════════════════════════════════════════ --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Commençons' : "Let's start" }}</div>
        <h2 class="h-1" style="margin-top:16px">
          @if($lang === 'fr')
            Prêt à réduire<br>vos <em>coûts de fabrication ?</em>
          @else
            Ready to reduce<br>your <em>manufacturing costs?</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:24px">
          {{ $lang === 'fr'
            ? 'Envoyez-nous vos plans et spécifications. Nous vous revenons avec une analyse technique et une estimation de prix sous 48 heures.'
            : 'Send us your drawings and specifications. We get back to you with a technical analysis and price estimate within 48 hours.' }}
        </p>
      </div>
      <div style="display:flex; flex-direction:column; gap:16px; align-items:flex-start">
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary" style="font-size:16px; padding:18px 28px">
          {{ $lang === 'fr' ? 'Demander un devis gratuit' : 'Request a free quote' }}
          <span class="arrow">→</span>
        </a>
        <a href="tel:+33782085117" class="btn-link">+33 (0)7 82 08 51 17</a>
      </div>
    </div>
  </div>
</section>

@endsection
