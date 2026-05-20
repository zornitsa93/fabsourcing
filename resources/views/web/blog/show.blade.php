{{-- resources/views/web/blog/show.blade.php --}}
@extends('layouts.web')

@php
  $title    = $post->getTranslation('title',            $lang, false) ?: $post->getTranslation('title',            'fr', false);
  $body     = $post->getTranslation('body',             $lang, false) ?: $post->getTranslation('body',             'fr', false);
  $tags     = $post->getTranslation('tags',             $lang, false) ?: $post->getTranslation('tags',             'fr', false);
  $metaT    = $post->getTranslation('meta_title',       $lang, false) ?: $title;
  $metaD    = $post->getTranslation('meta_description', $lang, false)
           ?: ($post->getTranslation('excerpt', $lang, false) ? Str::limit(strip_tags($post->getTranslation('excerpt', $lang, false)), 160) : '');
  $pageUrl  = request()->url();
@endphp

@section('title', $metaT . ' — Fab Sourcing')
@section('description', $metaD)

@push('seo')
<x-seo
  :title="$metaT . ' — Fab Sourcing'"
  :description="$metaD"
  :canonical="$pageUrl"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr'] ?? ''"
  :hreflang-en="$langSwitcherUrls['en'] ?? ''"
  og-type="article"
  :og-image="$post->featured_image_url ?? asset('images/og-default.jpg')"
/>
@endpush

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ addslashes($title) }}",
  "datePublished": "{{ $post->published_at?->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author_name }}"
  },
  @if($post->featured_image_url)"image": "{{ $post->featured_image_url }}",@endif
  "url": "{{ $pageUrl }}",
  "publisher": {
    "@type": "Organization",
    "name": "Fab Sourcing",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
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
      "name": "Blog",
      "item": "{{ route('blog', $lang) }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ addslashes(Str::limit($title, 60)) }}",
      "item": "{{ $pageUrl }}"
    }
  ]
}
</script>
@endpush

@section('content')

{{-- Featured image hero --}}
@if($post->featured_image_url)
  <div class="article-hero-img">
    <img src="{{ $post->featured_image_url }}" alt="{{ $title }}" loading="eager">
  </div>
@endif

{{-- Article content --}}
<div class="section">
  <div class="container">
    <div class="article-layout">

      {{-- Main column --}}
      <article class="article-main">

        {{-- Breadcrumb --}}
        <div class="breadcrumb" style="margin-bottom:32px">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <a href="{{ route('blog', $lang) }}">Blog</a>
          <span>/</span>
          <span>{{ Str::limit($title, 40) }}</span>
        </div>

        {{-- Tags --}}
        @if(is_array($tags) && count($tags))
          <div class="blog-card-tags" style="margin-bottom:20px">
            @foreach($tags as $t)
              <a href="{{ route('blog', $lang) }}?tag={{ urlencode($t) }}" class="blog-card-tag">{{ $t }}</a>
            @endforeach
          </div>
        @endif

        {{-- Title --}}
        <h1 class="h-1" style="margin-bottom:24px">{{ $title }}</h1>

        {{-- Meta bar --}}
        <div class="article-meta">
          <span class="article-meta-author">{{ $post->author_name }}</span>
          <span class="article-meta-sep">·</span>
          <span>{{ $post->published_at?->translatedFormat('d F Y') }}</span>
          @if($post->reading_time_minutes)
            <span class="article-meta-sep">·</span>
            <span>{{ $post->reading_time_minutes }} min {{ $lang === 'fr' ? 'de lecture' : 'read' }}</span>
          @endif
        </div>

        {{-- Body --}}
        <div class="article-body">
          {!! \App\Services\TextLinker::linkify($body ?? '', $lang) !!}
        </div>

      </article>

    </div>
  </div>
</div>

{{-- Related articles --}}
@if($related->count())
  <section class="section-tight" style="border-top:1px solid rgba(15,30,61,0.08); background:#f4f6f9">
    <div class="container">
      <div class="eyebrow" style="margin-bottom:32px">
        {{ $lang === 'fr' ? 'Articles similaires' : 'Related articles' }}
      </div>
      <div class="blog-grid reveal">
        @foreach($related as $rel)
          @php
            $relTitle   = $rel->getTranslation('title',   $lang, false) ?: $rel->getTranslation('title',   'fr', false);
            $relExcerpt = $rel->getTranslation('excerpt',  $lang, false) ?: $rel->getTranslation('excerpt',  'fr', false);
            $relTags    = $rel->getTranslation('tags',     $lang, false) ?: $rel->getTranslation('tags',     'fr', false);
          @endphp
          <article class="blog-card">
            <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $rel->slug]) }}" class="blog-card-img-link">
              <div class="blog-card-img">
                @if($rel->featured_image_url)
                  <img src="{{ $rel->featured_image_url }}" alt="{{ $relTitle }}" loading="lazy">
                @else
                  <div class="img-placeholder">{{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}</div>
                @endif
              </div>
            </a>
            <div class="blog-card-body">
              @if(is_array($relTags) && count($relTags))
                <div class="blog-card-tags">
                  @foreach(array_slice($relTags, 0, 2) as $t)
                    <span class="blog-card-tag">{{ $t }}</span>
                  @endforeach
                </div>
              @endif
              <h3 class="blog-card-title">
                <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $rel->slug]) }}">{{ $relTitle }}</a>
              </h3>
              @if($relExcerpt)
                <p class="blog-card-excerpt">{{ Str::limit(strip_tags($relExcerpt), 100) }}</p>
              @endif
              <div class="blog-card-meta">
                <span>{{ $rel->published_at?->translatedFormat('d M Y') }}</span>
                @if($rel->reading_time_minutes)
                  <span>·</span>
                  <span>{{ $rel->reading_time_minutes }} min</span>
                @endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Vous avez un projet ?' : 'Got a project?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Parlons-<em>en</em>
          @else
            Let's <em>talk</em>
          @endif
        </h2>
        <p class="lede" style="margin-top:20px">
          {{ $lang === 'fr'
            ? 'Décrivez votre besoin, Thierry vous répond personnellement sous 48 heures.'
            : 'Describe your need, Thierry replies personally within 48 hours.' }}
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
