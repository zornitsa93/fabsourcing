{{-- Footer — dark navy, 4-col grid --}}
<footer class="footer">
  <div class="container">
    <div class="footer-grid">

      {{-- Brand column --}}
      <div>
        <img class="footer-logo" src="{{ asset('images/logo-fab-full-light.png') }}" alt="Fab Sourcing" />
        <p class="footer-tag">
          {{ $lang === 'fr'
            ? 'Outsourcing industriel en Bulgarie & Roumanie. Métallerie, structures acier, fabrication sur mesure.'
            : 'Industrial outsourcing in Bulgaria & Romania. Metalwork, steel structures, custom fabrication.' }}
        </p>
      </div>

      {{-- Sitemap --}}
      <div class="footer-col">
        <h5>{{ $lang === 'fr' ? 'Plan du site' : 'Sitemap' }}</h5>
        <ul>
          <li><a href="{{ route('home',     $lang) }}">{{ $lang === 'fr' ? 'Accueil'        : 'Home' }}</a></li>
          <li><a href="{{ route('services', $lang) }}">{{ $lang === 'fr' ? 'Services'       : 'Services' }}</a></li>
          <li><a href="{{ route('products', $lang) }}">{{ $lang === 'fr' ? 'Produits'       : 'Products' }}</a></li>
          <li><a href="{{ route('why',      $lang) }}">{{ $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU' }}</a></li>
          <li><a href="{{ route('method',   $lang) }}">{{ $lang === 'fr' ? 'Méthode'        : 'Method' }}</a></li>
          <li><a href="{{ route('about',    $lang) }}">{{ $lang === 'fr' ? 'À propos'       : 'About' }}</a></li>
          <li><a href="{{ route('contact',  $lang) }}">Contact</a></li>
        </ul>
      </div>

      {{-- Contact --}}
      <div class="footer-col">
        <h5>Contact</h5>
        <div class="footer-person">
          <img class="footer-person-avatar"
               src="{{ asset('images/thierry.jpeg') }}"
               alt="Thierry Sudol"
               loading="lazy">
          <div>
            <span class="footer-person-name">Thierry Sudol</span>
            <span class="footer-person-role">{{ $lang === 'fr' ? 'Responsable commercial & marketing' : 'Sales & Marketing Manager' }}</span>
          </div>
        </div>
        <ul>
          <li><a href="tel:+33782085117">+33 (0)7 82 08 51 17</a></li>
          <li><a href="mailto:tsudol.fabtec@yahoo.com">tsudol.fabtec@yahoo.com</a></li>
        </ul>
      </div>

      {{-- Office --}}
      <div class="footer-col">
        <h5>{{ $lang === 'fr' ? 'Bureau' : 'Office' }}</h5>
        <ul>
          <li><span>1, route Neuve</span></li>
          <li><span>24150 St-Capraise-de-Lalinde</span></li>
          <li><span>France</span></li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <span>© {{ now()->year }} Fab Sourcing — {{ $lang === 'fr' ? 'Tous droits réservés' : 'All rights reserved' }}</span>
      <div style="display:flex; gap:20px; align-items:center">
        <a href="{{ route($lang === 'en' ? 'legal.mentions.en' : 'legal.mentions', $lang) }}"
           style="color:rgba(255,255,255,0.5); font-family:inherit; font-size:inherit; letter-spacing:inherit; text-decoration:none; transition:color 0.15s"
           onmouseover="this.style.color='rgba(255,255,255,0.85)'"
           onmouseout="this.style.color='rgba(255,255,255,0.5)'">
          {{ $lang === 'fr' ? 'Mentions légales' : 'Legal notice' }}
        </a>
        <a href="{{ route($lang === 'en' ? 'legal.privacy.en' : 'legal.privacy', $lang) }}"
           style="color:rgba(255,255,255,0.5); font-family:inherit; font-size:inherit; letter-spacing:inherit; text-decoration:none; transition:color 0.15s"
           onmouseover="this.style.color='rgba(255,255,255,0.85)'"
           onmouseout="this.style.color='rgba(255,255,255,0.5)'">
          {{ $lang === 'fr' ? 'Confidentialité' : 'Privacy' }}
        </a>
        <span>fab-sourcing.fr</span>
      </div>
    </div>
  </div>
</footer>
