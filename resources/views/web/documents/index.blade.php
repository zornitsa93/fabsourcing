@extends('layouts.web')

@section('title', $lang === 'fr' ? 'Documents — Fab Sourcing' : 'Documents — Fab Sourcing')

@section('description', $lang === 'fr'
    ? 'Téléchargez les documents techniques et commerciaux réservés aux membres approuvés Fab Sourcing.'
    : 'Download technical and commercial documents reserved for approved Fab Sourcing members.')

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'Documents' : 'Documents' }}</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Documents &amp; <em>Téléchargements</em>
          @else
            Documents &amp; <em>Downloads</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Bienvenue, ' . auth()->user()->name . '. Retrouvez ici les documents techniques et commerciaux mis à votre disposition.'
            : 'Welcome, ' . auth()->user()->name . '. Find here the technical and commercial documents made available to you.' }}
        </p>
        <form method="POST" action="{{ route('logout', $lang) }}" style="margin-top:16px">
          @csrf
          <button type="submit" class="btn-link" style="background:none;border:none;cursor:pointer;padding:0;font-size:inherit;color:inherit">
            {{ $lang === 'fr' ? 'Se déconnecter' : 'Log out' }}
            <span class="arrow">→</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Documents list --}}
<section class="section">
  <div class="container">
    <div class="blog-main">

      @forelse($documents as $doc)
        @php
          $title       = $doc->getTranslation('title',       $lang, false) ?: $doc->getTranslation('title',       'fr', false);
          $description = $doc->getTranslation('description', $lang, false) ?: $doc->getTranslation('description', 'fr', false);
        @endphp
        <article class="blog-list-item reveal" style="align-items:center">
          <div class="blog-list-body" style="flex:1">
            <h2 class="blog-list-title" style="margin-bottom:8px">{{ $title }}</h2>
            @if($description)
              <p class="blog-list-excerpt">{{ $description }}</p>
            @endif
          </div>
          <div style="flex-shrink:0; padding-left:24px">
            <a href="{{ route('member.documents.download', ['lang' => $lang, 'document' => $doc->id]) }}"
               class="btn btn-accent">
              {{ $lang === 'fr' ? 'Télécharger' : 'Download' }}
              <span class="arrow">↓</span>
            </a>
          </div>
        </article>
      @empty
        <div style="padding:60px 0; text-align:center; color:#6b7891">
          <p>{{ $lang === 'fr' ? 'Aucun document disponible.' : 'No documents available.' }}</p>
        </div>
      @endforelse

    </div>
  </div>
</section>

{{-- CTA --}}
<section class="cta-section">
  <div class="container">
    <div class="cta-inner reveal">
      <div>
        <div class="eyebrow">{{ $lang === 'fr' ? 'Une question ?' : 'A question?' }}</div>
        <h2 class="h-2" style="margin-top:16px">
          @if($lang === 'fr')
            Besoin d'informations <em>complémentaires</em> ?
          @else
            Need <em>further information</em>?
          @endif
        </h2>
      </div>
      <div>
        <a href="{{ route('contact', $lang) }}" class="btn btn-primary">
          {{ $lang === 'fr' ? 'Nous contacter' : 'Contact us' }}
          <span class="arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
