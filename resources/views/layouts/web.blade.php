<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Fab Sourcing — Outsourcing industriel en Bulgarie & Roumanie')</title>
  <meta name="description" content="@yield('description', 'Externalisez votre production industrielle en Bulgarie et Roumanie. Qualité européenne, coûts réduits, délais courts.')">

  @stack('seo')

  {{-- Preconnect for Google Fonts (performance) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  {{-- Main stylesheet (compiled from resources/sass/web.scss) --}}
  <link href="{{ mix('css/web.css') }}" rel="stylesheet">

  @stack('head')
</head>
<body>

  @include('partials.nav')

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

  {{-- Web JS bundle --}}
  <script src="{{ mix('js/web.js') }}"></script>

  {{-- Scroll reveal (tiny inline script — no jQuery needed) --}}
  <script>
    (function () {
      var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
      document.querySelectorAll('.reveal').forEach(function (el) { obs.observe(el); });
    })();
  </script>

  @stack('scripts')

  @if(config('services.ga_id'))
    @include('partials.cookie-consent')
  @endif
</body>
</html>
