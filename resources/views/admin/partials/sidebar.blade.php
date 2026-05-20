{{-- Admin sidebar navigation --}}
@php
  $current = Route::currentRouteName();
  function aNavActive(string $current, string ...$names): string {
    foreach ($names as $name) {
      if (str_starts_with($current, $name)) return 'a-nav-active';
    }
    return '';
  }
@endphp

<aside class="a-sidebar">
  <div class="a-sidebar-logo">
    <img src="{{ asset('images/logo-omara-white.svg') }}" alt="3omara" style="height:36px; width:auto;" />
  </div>

  <p class="a-sidebar-label">Общи</p>

  <nav class="a-nav">
    <ul>
      <li><a href="{{ route('pages.index') }}"       class="{{ aNavActive($current, 'pages.') }}">Страници</a></li>
      <li><a href="{{ route('products.index') }}"           class="{{ aNavActive($current, 'products.') }}">Продукти</a></li>
      <li><a href="{{ route('product-categories.index') }}" class="{{ aNavActive($current, 'product-categories.') }}" style="padding-left:32px; font-size:13px; color:rgba(255,255,255,0.55)">└ Категории</a></li>
      <li><a href="{{ route('blog-posts.index') }}" class="{{ aNavActive($current, 'blog-posts.') }}">Блог</a></li>
      <li><a href="{{ route('services-admin.index') }}" class="{{ aNavActive($current, 'services-admin.') }}">Услуги</a></li>
      <li><a href="{{ route('contact-submissions.index') }}" class="{{ aNavActive($current, 'contact-submissions.') }}">Контакт форми</a></li>
    </ul>
  </nav>

  <p class="a-sidebar-label">Настройки</p>

  <nav class="a-nav">
    <ul>
      <li><a href="{{ route('settings.index') }}" class="{{ aNavActive($current, 'settings.') }}">Настройки</a></li>
      <li><a href="{{ route('languages.index') }}" class="{{ aNavActive($current, 'languages.') }}">Езици</a></li>
      <li><a href="{{ route('media.index') }}" class="{{ aNavActive($current, 'media.') }}">Медия библиотека</a></li>
    </ul>
  </nav>
</aside>
