@props(['cat', 'lang', 'num'])
@php
  $name       = $cat->getTranslation('name', $lang, false) ?: $cat->getTranslation('name', 'fr', false);
  $desc       = $cat->getTranslation('description', $lang, false) ?: $cat->getTranslation('description', 'fr', false);
  $paramKey   = $lang === 'fr' ? 'categorie' : 'category';
  $paramVal   = $cat->getSlugForLang($lang);
  $contactUrl = route('contact', $lang) . '?' . $paramKey . '=' . urlencode($paramVal);
@endphp
<a href="{{ $contactUrl }}" class="cat-card">
  @if($cat->image)
    <img src="{{ Storage::url($cat->image) }}"
         alt=""
         loading="lazy"
         class="cat-card-bg-img">
    <div class="cat-card-overlay"></div>
  @endif
  <span class="cat-card-num">{{ $num }}</span>
  <div class="cat-card-body">
    <h3 class="cat-card-title">{{ $name }}</h3>
    @if($desc)
      <p class="cat-card-desc">{{ $desc }}</p>
    @endif
  </div>
  <span class="cat-card-cta">
    {{ $lang === 'fr' ? 'Demander un devis' : 'Request a quote' }}
    <span class="arrow">→</span>
  </span>
</a>
