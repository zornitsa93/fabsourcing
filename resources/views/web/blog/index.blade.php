{{-- resources/views/web/blog/index.blade.php --}}
@extends('layouts.web')

@php
  $metaTitle = $blogPage?->getTranslation('meta_title', $lang, false)
             ?: ($lang === 'fr' ? 'Blog industriel — Fab Sourcing' : 'Industrial Blog — Fab Sourcing');
  $metaDesc  = $blogPage?->getTranslation('meta_description', $lang, false)
             ?: ($lang === 'fr' ? 'Conseils et actualités sur la sous-traitance industrielle en Europe de l\'Est.' : 'Advice and news on industrial subcontracting in Eastern Europe.');
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

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>Blog</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Articles & <em>ressources</em>
          @else
            Articles & <em>resources</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Conseils techniques, retours d\'expérience et actualités sur la sous-traitance industrielle en Europe de l\'Est.'
            : 'Technical advice, experience feedback and news on industrial subcontracting in Eastern Europe.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Posts — full width, no sidebar --}}
<section class="section">
  <div class="container">
    <div class="blog-main">

      @if($search || $tag)
        <div class="blog-filter-active">
          @if($search)
            <span>{{ $lang === 'fr' ? 'Recherche :' : 'Search:' }} <strong>{{ $search }}</strong></span>
          @endif
          @if($tag)
            <span>{{ $lang === 'fr' ? 'Tag :' : 'Tag:' }} <strong>{{ $tag }}</strong></span>
          @endif
          <a href="{{ route('blog', $lang) }}" class="blog-filter-clear">
            {{ $lang === 'fr' ? '✕ Effacer' : '✕ Clear' }}
          </a>
        </div>
      @endif

      @forelse($posts as $post)
        @php
          $title   = $post->getTranslation('title',   $lang, false) ?: $post->getTranslation('title',   'fr', false);
          $excerpt = $post->getTranslation('excerpt',  $lang, false) ?: $post->getTranslation('excerpt',  'fr', false);
          $tags    = $post->getTranslation('tags',     $lang, false) ?: $post->getTranslation('tags',     'fr', false);
        @endphp
        <article class="blog-list-item reveal">
          @if($post->featured_image_url)
            <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $post->slug]) }}" class="blog-list-img">
              <img src="{{ $post->featured_image_url }}" alt="{{ $title }}" loading="lazy">
            </a>
          @else
            <div class="blog-list-img">
              <div class="img-placeholder">{{ $lang === 'fr' ? 'Image à venir' : 'Image coming soon' }}</div>
            </div>
          @endif
          <div class="blog-list-body">
            @if(is_array($tags) && count($tags))
              <div class="blog-card-tags" style="margin-bottom:10px">
                @foreach(array_slice($tags, 0, 3) as $t)
                  <a href="{{ route('blog', $lang) }}?tag={{ urlencode($t) }}" class="blog-card-tag">{{ $t }}</a>
                @endforeach
              </div>
            @endif
            <h2 class="blog-list-title">
              <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $post->slug]) }}">{{ $title }}</a>
            </h2>
            @if($excerpt)
              <p class="blog-list-excerpt">{{ Str::limit(strip_tags($excerpt), 160) }}</p>
            @endif
            <div class="blog-card-meta" style="margin-top:16px">
              <span>{{ $post->author_name }}</span>
              <span>·</span>
              <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
              @if($post->reading_time_minutes)
                <span>·</span>
                <span>{{ $post->reading_time_minutes }} min</span>
              @endif
            </div>
            <a href="{{ route('blog.show', ['lang' => $lang, 'slug' => $post->slug]) }}"
               class="btn-link" style="display:inline-flex; align-items:center; gap:8px; margin-top:16px">
              {{ $lang === 'fr' ? 'Lire l\'article' : 'Read article' }}
              <span class="arrow">→</span>
            </a>
          </div>
        </article>
      @empty
        <div style="padding:60px 0; text-align:center; color:#6b7891">
          <p>{{ $lang === 'fr' ? 'Aucun article trouvé.' : 'No articles found.' }}</p>
        </div>
      @endforelse

      {{-- Pagination --}}
      @if($posts->hasPages())
        <div class="blog-pagination">
          {{ $posts->links('vendor.pagination.simple-web') }}
        </div>
      @endif

    </div>
  </div>
</section>

@endsection
