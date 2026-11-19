{{-- Cookie consent banner + GA4 loader. Shown only when GOOGLE_ANALYTICS_ID is set. --}}
@php $gaId = config('services.ga_id'); @endphp

<div id="cookie-banner" style="display:none; position:fixed; bottom:0; left:0; right:0; z-index:9999; background:#fff; border-top:2px solid #0f1e3d; box-shadow:0 -4px 20px rgba(0,0,0,.12)">
  <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; max-width:1280px; margin:0 auto; padding:16px 24px; flex-wrap:wrap">
    <p id="cookie-text" style="margin:0; font-size:14px; color:#0f1e3d; flex:1; min-width:200px"></p>
    <div style="display:flex; gap:8px; flex-shrink:0">
      <button id="cookie-accept"
        style="background:#0f1e3d; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-size:14px; cursor:pointer; font-family:inherit; font-weight:500">
        <span data-fr="Accepter" data-en="Accept">Accepter</span>
      </button>
      <button id="cookie-decline"
        style="background:transparent; color:#0f1e3d; border:1px solid #0f1e3d; padding:10px 20px; border-radius:6px; font-size:14px; cursor:pointer; font-family:inherit">
        <span data-fr="Refuser" data-en="Decline">Refuser</span>
      </button>
    </div>
  </div>
</div>

<script>
(function () {
  var GA_ID   = @json($gaId);
  var banner  = document.getElementById('cookie-banner');
  var text    = document.getElementById('cookie-text');
  var htmlLang = document.documentElement.lang || 'fr';
  var lang    = htmlLang.indexOf('fr') === 0 ? 'fr' : 'en';

  var messages = {
    fr: 'Nous utilisons Google Analytics pour mesurer l\'audience de ce site. Acceptez-vous l\'utilisation de ces cookies analytiques ?',
    en: 'We use Google Analytics to measure site traffic. Do you accept the use of analytics cookies?'
  };

  text.textContent = messages[lang] || messages.fr;
  Array.prototype.forEach.call(document.querySelectorAll('#cookie-banner [data-fr]'), function (el) {
    el.textContent = el.getAttribute('data-' + lang) || el.textContent;
  });

  // The choice is kept 6 months (as stated in the cookie policy), then asked again.
  var SIX_MONTHS = 183 * 24 * 60 * 60 * 1000;
  function readConsent() {
    try {
      var raw = localStorage.getItem('cookie_consent');
      var at  = parseInt(localStorage.getItem('cookie_consent_at') || '0', 10);
      if (!raw || !at || (Date.now() - at) > SIX_MONTHS) { return null; }
      return raw;
    } catch (e) { return null; }
  }
  function saveConsent(value) {
    try {
      localStorage.setItem('cookie_consent', value);
      localStorage.setItem('cookie_consent_at', String(Date.now()));
    } catch (e) {}
  }
  // Footer link "Gestion des cookies / Cookie settings": clear the choice and ask again.
  window.openCookieSettings = function () {
    try { localStorage.removeItem('cookie_consent'); localStorage.removeItem('cookie_consent_at'); } catch (e) {}
    banner.style.display = 'block';
  };

  function loadGA() {
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    window.gtag = gtag;
    gtag('js', new Date());
    gtag('config', GA_ID);
    var s    = document.createElement('script');
    s.src    = 'https://www.googletagmanager.com/gtag/js?id=' + GA_ID;
    s.async  = true;
    document.head.appendChild(s);
  }

  var consent = readConsent();

  if (consent === 'accepted') {
    loadGA();
  } else if (consent === 'declined') {
    // respect previous decline, do nothing
  } else {
    banner.style.display = 'block';
  }

  document.getElementById('cookie-accept').addEventListener('click', function () {
    saveConsent('accepted');
    banner.style.display = 'none';
    loadGA();
  });

  document.getElementById('cookie-decline').addEventListener('click', function () {
    saveConsent('declined');
    banner.style.display = 'none';
  });
})();
</script>
