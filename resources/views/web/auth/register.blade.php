@extends('layouts.web')

@section('title', $lang === 'fr' ? 'Créer un compte — Fab Sourcing' : 'Create an account — Fab Sourcing')

@section('description', $lang === 'fr'
    ? 'Créez votre compte Fab Sourcing pour accéder aux documents et ressources réservés aux membres.'
    : 'Create your Fab Sourcing account to access member-only documents and resources.')

@section('content')

{{-- Page hero --}}
<div class="page-hero">
  <div class="container">
    <div class="page-hero-grid reveal">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('home', $lang) }}">{{ $lang === 'fr' ? 'Accueil' : 'Home' }}</a>
          <span>/</span>
          <span>{{ $lang === 'fr' ? 'Créer un compte' : 'Create an account' }}</span>
        </div>
        <h1 class="h-1">
          {{ $lang === 'fr' ? 'Créer un compte' : 'Create an account' }}
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Remplissez le formulaire ci-dessous. Votre compte sera examiné par notre équipe avant activation.'
            : 'Fill in the form below. Your account will be reviewed by our team before activation.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Registration form --}}
<section class="section">
  <div class="container">
    <div class="contact-grid reveal">

      <div class="contact-form-col">

        <form method="POST" action="{{ route('register.store', $lang) }}" class="form" novalidate>
          @csrf

          <div class="form-row">
            <div class="field">
              <label for="name">{{ $lang === 'fr' ? 'Nom complet *' : 'Full name *' }}</label>
              <input type="text" id="name" name="name"
                     value="{{ old('name') }}"
                     autocomplete="name" required>
              @error('name')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>
            <div class="field">
              <label for="company">{{ $lang === 'fr' ? 'Entreprise' : 'Company' }}</label>
              <input type="text" id="company" name="company"
                     value="{{ old('company') }}"
                     autocomplete="organization">
              @error('company')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-row">
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
              <label for="phone">{{ $lang === 'fr' ? 'Téléphone' : 'Phone' }}</label>
              <input type="tel" id="phone" name="phone"
                     value="{{ old('phone') }}"
                     autocomplete="tel">
              @error('phone')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-row">
            <div class="field">
              <label for="password">{{ $lang === 'fr' ? 'Mot de passe *' : 'Password *' }}</label>
              <input type="password" id="password" name="password"
                     autocomplete="new-password" required>
              @error('password')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>
            <div class="field">
              <label for="password_confirmation">{{ $lang === 'fr' ? 'Confirmer le mot de passe *' : 'Confirm password *' }}</label>
              <input type="password" id="password_confirmation" name="password_confirmation"
                     autocomplete="new-password" required>
              @error('password_confirmation')
                <span class="field-error">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="field" style="margin-top:8px">
            <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer; font-weight:400">
              <input type="checkbox" id="gdpr" name="gdpr" value="1"
                     {{ old('gdpr') ? 'checked' : '' }}
                     style="margin-top:3px; flex-shrink:0">
              <span>
                @if($lang === 'fr')
                  J'accepte que mes données personnelles soient traitées conformément à la
                  <a href="{{ route('legal.privacy', $lang) }}" style="color:inherit; text-decoration:underline">politique de confidentialité</a>
                  de Fab Sourcing. *
                @else
                  I agree that my personal data will be processed in accordance with Fab Sourcing's
                  <a href="{{ route('legal.privacy.en', $lang) }}" style="color:inherit; text-decoration:underline">privacy policy</a>. *
                @endif
              </span>
            </label>
            @error('gdpr')
              <span class="field-error">{{ $message }}</span>
            @enderror
          </div>

          <div style="margin-top:24px">
            <button type="submit" class="btn btn-primary" style="font-size:16px; padding:16px 28px">
              {{ $lang === 'fr' ? 'Créer mon compte' : 'Create my account' }}
              <span class="arrow">→</span>
            </button>
          </div>

        </form>

        <p class="body-sm" style="margin-top:24px">
          {{ $lang === 'fr' ? 'Déjà un compte ?' : 'Already have an account?' }}
          <a href="{{ route('login', $lang) }}" style="color:inherit; text-decoration:underline">
            {{ $lang === 'fr' ? 'Connectez-vous' : 'Log in' }}
          </a>
        </p>

      </div>

    </div>
  </div>
</section>

@endsection
