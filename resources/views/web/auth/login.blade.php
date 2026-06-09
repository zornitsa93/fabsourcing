@extends('layouts.web')

@section('title', $lang === 'fr' ? 'Connexion — Fab Sourcing' : 'Login — Fab Sourcing')

@section('description', $lang === 'fr'
    ? 'Connectez-vous à votre espace membre Fab Sourcing pour accéder aux documents.'
    : 'Log in to your Fab Sourcing member area to access documents.')

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'Connexion' : 'Login' }}</span>
        </div>
        <h1 class="h-1">
          {{ $lang === 'fr' ? 'Connexion' : 'Login' }}
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Accédez à votre espace membre pour consulter les documents mis à votre disposition.'
            : 'Access your member area to view the documents available to you.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Login form --}}
<section class="section">
  <div class="container">
    <div class="contact-grid reveal">

      <div class="contact-form-col">

        @if(session('registered'))
          <div class="form-success" style="margin-bottom:40px">
            <div class="eyebrow no-line" style="margin-bottom:12px">
              {{ $lang === 'fr' ? 'Compte créé ✓' : 'Account created ✓' }}
            </div>
            <p class="body">
              {{ $lang === 'fr'
                ? 'Compte créé — en attente de validation par notre équipe.'
                : 'Account created — pending approval by our team.' }}
            </p>
          </div>
        @endif

        <form method="POST" action="{{ route('login.post', $lang) }}" class="form" novalidate>
          @csrf

          <div class="field">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   autocomplete="email" required>
            @error('email')
              <span class="field-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="field">
            <label for="password">{{ $lang === 'fr' ? 'Mot de passe *' : 'Password *' }}</label>
            <input type="password" id="password" name="password"
                   autocomplete="current-password" required>
            @error('password')
              <span class="field-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="field" style="margin-top:8px">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-weight:400">
              <input type="checkbox" id="remember" name="remember" value="1"
                     {{ old('remember') ? 'checked' : '' }}>
              <span>{{ $lang === 'fr' ? 'Se souvenir de moi' : 'Remember me' }}</span>
            </label>
          </div>

          <div style="margin-top:24px">
            <button type="submit" class="btn btn-primary" style="font-size:16px; padding:16px 28px">
              {{ $lang === 'fr' ? 'Se connecter' : 'Log in' }}
              <span class="arrow">→</span>
            </button>
          </div>

        </form>

        <p class="body-sm" style="margin-top:24px">
          {{ $lang === 'fr' ? 'Pas encore de compte ?' : 'No account yet?' }}
          <a href="{{ route('register', $lang) }}" style="color:inherit; text-decoration:underline">
            {{ $lang === 'fr' ? 'Inscrivez-vous' : 'Register' }}
          </a>
        </p>

      </div>

    </div>
  </div>
</section>

@endsection
