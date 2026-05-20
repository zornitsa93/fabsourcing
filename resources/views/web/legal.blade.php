@extends('layouts.web')

@php
  $pageTitle = $page->getTranslation('title',            $lang, false) ?: $page->getTranslation('title', 'fr', false);
  $content   = $page->getTranslation('content',          $lang, false) ?: $page->getTranslation('content', 'fr', false);
  $metaTitle = $page->getTranslation('meta_title',       $lang, false) ?: $pageTitle;
  $metaDesc  = $page->getTranslation('meta_description', $lang, false) ?: '';
@endphp

@section('title', $metaTitle . ' — Fab Sourcing')
@section('description', $metaDesc)

@push('seo')
<x-seo
  :title="($page?->getTranslation('meta_title', $lang, false) ?: ($lang === 'fr' ? 'Mentions légales — Fab Sourcing' : 'Legal Notice — Fab Sourcing'))"
  :description="$lang === 'fr' ? 'Mentions légales de Fab Sourcing.' : 'Legal notice for Fab Sourcing.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en']"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@section('content')

<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $pageTitle }}</span>
        </div>
        <h1 class="h-1">{{ $pageTitle }}</h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Informations légales et réglementaires concernant l\'utilisation de ce site.'
            : 'Legal and regulatory information regarding the use of this site.' }}
        </p>
      </div>
    </div>
  </div>
</div>

<section class="section">
  <div class="container">
    @if($content)
      <div class="legal-body article-layout">
        <div class="article-body">
          {!! $content !!}
        </div>
      </div>
    @else
      <div class="legal-placeholder">
        <p class="lede" style="color:#6b7891">
          {{ $lang === 'fr'
            ? 'Ce contenu sera ajouté prochainement. Vous pouvez l\'éditer depuis l\'administration.'
            : 'This content will be added shortly. You can edit it from the administration.' }}
        </p>
        <a href="{{ route('contact', $lang) }}" class="btn btn-ghost" style="margin-top:24px">
          {{ $lang === 'fr' ? 'Nous contacter' : 'Contact us' }}
        </a>
      </div>
    @endif
  </div>
</section>

@endsection
