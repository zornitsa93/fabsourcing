@extends('layouts.web')
@section('title', $lang === 'fr' ? 'Produits — Fab Sourcing' : 'Products — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">{{ $lang === 'fr' ? 'Produits' : 'Products' }}</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
