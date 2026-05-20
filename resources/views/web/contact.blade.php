@extends('layouts.web')

@section('title', $lang === 'fr'
    ? 'Contact — Fab Sourcing'
    : 'Contact — Fab Sourcing')

@section('description', $lang === 'fr'
    ? 'Demandez un devis gratuit ou posez vos questions à Thierry Sudol, fondateur de Fab Sourcing. Réponse sous 48 heures.'
    : 'Request a free quote or ask your questions to Thierry Sudol, founder of Fab Sourcing. Reply within 48 hours.')

@push('seo')
<x-seo
  :title="$lang === 'fr' ? 'Contact — Fab Sourcing' : 'Contact — Fab Sourcing'"
  :description="$lang === 'fr'
    ? 'Contactez Fab Sourcing pour externaliser votre production métallurgique en Bulgarie et en Roumanie. Réponse personnelle sous 48h.'
    : 'Contact Fab Sourcing to outsource your metalwork production to Bulgaria and Romania. Personal reply within 48 hours.'"
  :canonical="request()->url()"
  :lang="$lang"
  :hreflang-fr="$langSwitcherUrls['fr']"
  :hreflang-en="$langSwitcherUrls['en'] ?? null"
  og-type="website"
  :og-image="asset('images/og-default.jpg')"
/>
@endpush

@push('scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Fab Sourcing",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "FR"
  },
  "areaServed": ["BG", "RO"],
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "sales",
    "email": "contact@fab-sourcing.fr"
  }
}
</script>
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
          <span>Contact</span>
        </div>
        <h1 class="h-1">
          @if($lang === 'fr')
            Parlons de votre <em>projet</em>
          @else
            Let's talk about<br>your <em>project</em>
          @endif
        </h1>
      </div>
      <div>
        <p class="lede">
          {{ $lang === 'fr'
            ? 'Envoyez-nous vos plans ou décrivez votre besoin. Thierry Sudol vous répond personnellement sous 48 heures avec une analyse technique et une première estimation.'
            : 'Send us your drawings or describe your need. Thierry Sudol personally replies within 48 hours with a technical analysis and first estimate.' }}
        </p>
      </div>
    </div>
  </div>
</div>

{{-- Contact split layout --}}
<section class="section">
  <div class="container">
    <div class="contact-grid reveal">

      {{-- Left: form --}}
      <div class="contact-form-col">

        @if(session('contact_sent'))
          <div class="form-success" style="margin-bottom:40px">
            <div class="eyebrow no-line" style="margin-bottom:12px">
              {{ $lang === 'fr' ? 'Message envoyé ✓' : 'Message sent ✓' }}
            </div>
            <p class="body">
              {{ $lang === 'fr'
                ? 'Merci pour votre message. Nous vous répondrons dans les 48 heures.'
                : 'Thank you for your message. We will reply within 48 hours.' }}
            </p>
          </div>
        @endif

        <form action="{{ route('contact.send', $lang) }}" method="POST" class="form" novalidate>
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

          <div class="field">
            <label for="message">{{ $lang === 'fr' ? 'Votre message *' : 'Your message *' }}</label>
            @php
              $messagePrefill = $categoryPrefill
                ? ($lang === 'fr'
                    ? "Bonjour, je souhaite obtenir un devis pour : {$categoryPrefill}."
                    : "Hello, I would like a quote for: {$categoryPrefill}.")
                : '';
            @endphp
            <textarea id="message" name="message" rows="6"
                      required>{{ old('message', $messagePrefill) }}</textarea>
            @error('message')
              <span class="field-error">{{ $message }}</span>
            @enderror
          </div>

          <div style="margin-top:8px">
            <button type="submit" class="btn btn-primary" style="font-size:16px; padding:16px 28px">
              {{ $lang === 'fr' ? 'Envoyer le message' : 'Send message' }}
              <span class="arrow">→</span>
            </button>
          </div>

          <p class="body-sm" style="margin-top:16px">
            {{ $lang === 'fr'
              ? 'En soumettant ce formulaire, vous acceptez que vos données soient utilisées pour traiter votre demande.'
              : 'By submitting this form, you agree that your data will be used to process your request.' }}
            <a href="{{ $lang === 'fr' ? route('legal.privacy') : route('legal.privacy.en') }}" style="color:inherit; text-decoration:underline">
              {{ $lang === 'fr' ? 'Politique de confidentialité' : 'Privacy policy' }}
            </a>
          </p>
        </form>
      </div>

      {{-- Right: contact info --}}
      <div class="contact-info-col">
        <div class="contact-info-card">
          <div class="eyebrow no-line" style="margin-bottom:24px">
            {{ $lang === 'fr' ? 'Contact direct' : 'Direct contact' }}
          </div>

          <div class="contact-person">
            <img src="{{ asset('images/thierry.jpeg') }}"
                 alt="Thierry Sudol"
                 loading="lazy"
                 class="contact-person-avatar">
            <div class="contact-person-name">Thierry Sudol</div>
            <div class="contact-person-role">{{ $lang === 'fr' ? 'Responsable commercial & marketing' : 'Sales & Marketing Manager' }}</div>
          </div>

          <div class="contact-details">
            <a href="tel:+33782085117" class="contact-detail">
              <span class="contact-detail-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                </svg>
              </span>
              +33 (0)7 82 08 51 17
            </a>
            <a href="mailto:tsudol.fabtec@yahoo.com" class="contact-detail">
              <span class="contact-detail-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                </svg>
              </span>
              tsudol.fabtec@yahoo.com
            </a>
            <div class="contact-detail">
              <span class="contact-detail-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
              </span>
              1, route Neuve<br>24150 St-Capraise-de-Lalinde<br>France
            </div>
          </div>

          <div class="contact-response-time">
            <span class="contact-response-dot"></span>
            {{ $lang === 'fr' ? 'Réponse sous 48 heures' : 'Reply within 48 hours' }}
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
