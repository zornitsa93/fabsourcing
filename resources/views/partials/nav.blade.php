{{-- Navigation — sticky glassmorphism nav --}}
<nav class="nav">
  <div class="nav-inner">

    {{-- Brand logo --}}
    <a href="{{ route('home', $lang) }}" class="brand">
      <img class="brand-logo" src="{{ asset('images/logo-fab-full.png') }}" alt="Fab Sourcing" />
    </a>

    {{-- Desktop nav links --}}
    <div class="nav-links">
      @php
        $currentRoute = Route::currentRouteName();
        $navLinks = [
          ['route' => 'home',     'route_en' => null,         'label' => $lang === 'fr' ? 'Accueil'        : 'Home'],
          ['route' => 'services', 'route_en' => null,         'label' => $lang === 'fr' ? 'Services'       : 'Services'],
          ['route' => 'products', 'route_en' => 'products.en','label' => $lang === 'fr' ? 'Produits'       : 'Products'],
          ['route' => 'why',      'route_en' => 'why.en',     'label' => $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU'],
          ['route' => 'method',   'route_en' => 'method.en',  'label' => $lang === 'fr' ? 'Méthode'        : 'Method'],
          ['route' => 'about',    'route_en' => 'about.en',   'label' => $lang === 'fr' ? 'À propos'       : 'About'],
          ['route' => 'contact',  'route_en' => null,         'label' => 'Contact'],
        ];
      @endphp

      @foreach($navLinks as $link)
        @php
          $routeName = ($lang === 'en' && $link['route_en']) ? $link['route_en'] : $link['route'];
          $href      = route($routeName, $lang);
          $isActive  = $currentRoute === $link['route'] || $currentRoute === $link['route_en'];
        @endphp
        <a href="{{ $href }}" class="nav-link {{ $isActive ? 'active' : '' }}">
          {{ $link['label'] }}
        </a>
      @endforeach
    </div>

    {{-- Right: phone + language toggle + CTA --}}
    <div class="nav-right">
      <div class="nav-person">
        <img src="{{ asset('images/thierry.jpeg') }}" alt="Thierry Sudol" class="nav-person-avatar">
        <a href="tel:+33782085117" class="nav-phone">+33 (0)7 82 08 51 17</a>
      </div>

      @if(count($languages) > 1)
      <div class="lang-toggle">
        @foreach($languages as $language)
          @php
            $switchUrl = $langSwitcherUrls[$language->slug]
              ?? route('home', $language->slug);
          @endphp
          <a href="{{ $switchUrl }}" class="{{ $language->slug === $lang ? 'active' : '' }}">
            {{ strtoupper($language->slug) }}
          </a>
        @endforeach
      </div>
      @endif

      <a href="{{ route('contact', $lang) }}" class="btn btn-primary">
        {{ $lang === 'fr' ? 'Devis gratuit' : 'Free quote' }}
        <span class="arrow">→</span>
      </a>

      {{-- Mobile hamburger --}}
      <button class="mobile-menu-btn" id="nav-toggle" aria-label="Menu" aria-expanded="false">
        <svg class="ham-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
          <rect class="ham-line ham-line-1" x="2" y="4" width="16" height="2" rx="1" fill="currentColor"/>
          <rect class="ham-line ham-line-2" x="2" y="9" width="16" height="2" rx="1" fill="currentColor"/>
          <rect class="ham-line ham-line-3" x="2" y="14" width="16" height="2" rx="1" fill="currentColor"/>
        </svg>
      </button>
    </div>

  </div>
</nav>

{{-- Mobile drawer overlay --}}
<div class="nav-drawer-overlay" id="nav-overlay" aria-hidden="true"></div>

{{-- Mobile drawer --}}
<div class="nav-mobile-drawer" id="nav-drawer" role="dialog" aria-label="Navigation" aria-modal="true">
  <div class="nav-drawer-header">
    <img src="{{ asset('images/logo-fab-full.png') }}" alt="Fab Sourcing" style="height:32px; width:auto" />
    <button class="nav-drawer-close" id="nav-close" aria-label="{{ $lang === 'fr' ? 'Fermer' : 'Close' }}">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  <nav class="nav-drawer-links">
    @foreach($navLinks as $link)
      @php
        $routeName = ($lang === 'en' && $link['route_en']) ? $link['route_en'] : $link['route'];
        $href      = route($routeName, $lang);
        $isActive  = $currentRoute === $link['route'] || $currentRoute === $link['route_en'];
      @endphp
      <a href="{{ $href }}" class="nav-drawer-link {{ $isActive ? 'active' : '' }}">
        {{ $link['label'] }}
      </a>
    @endforeach
  </nav>

  <div class="nav-drawer-footer">
    <a href="tel:+33782085117" class="nav-drawer-phone">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
      </svg>
      +33 (0)7 82 08 51 17
    </a>

    @if(count($languages) > 1)
    <div class="lang-toggle" style="margin-top:16px">
      @foreach($languages as $language)
        @php
          $switchUrl = $langSwitcherUrls[$language->slug] ?? route('home', $language->slug);
        @endphp
        <a href="{{ $switchUrl }}" class="{{ $language->slug === $lang ? 'active' : '' }}">
          {{ strtoupper($language->slug) }}
        </a>
      @endforeach
    </div>
    @endif
  </div>
</div>

<script>
(function () {
  var toggle  = document.getElementById('nav-toggle');
  var close   = document.getElementById('nav-close');
  var overlay = document.getElementById('nav-overlay');
  var drawer  = document.getElementById('nav-drawer');

  function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  toggle.addEventListener('click', openDrawer);
  close.addEventListener('click', closeDrawer);
  overlay.addEventListener('click', closeDrawer);

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDrawer();
  });
})();
</script>
