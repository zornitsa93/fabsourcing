<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — 3omara</title>

  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
  @stack('head')
</head>
<body>
<div class="a-shell">

  @include('admin.partials.sidebar')

  <div class="a-main">
    <header class="a-topbar">
      <span class="a-topbar-title">@yield('page-title', 'Dashboard')</span>
      <div class="a-topbar-user">
        Здравей, {{ auth()->guard('admin')->user()->name }}
        <span class="sep">|</span>
        <a href="{{ route('adminLogout') }}">Изход</a>
      </div>
    </header>

    <main class="a-content">
      @if(session('success'))
        <div class="a-alert a-alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="a-alert a-alert-error">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="a-alert a-alert-error">
          Моля, коригирайте маркираните полета.
        </div>
      @endif

      @yield('content')
    </main>
  </div>

</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>

{{-- Auto-dismiss alerts --}}
<script>
(function () {
  document.querySelectorAll('.a-alert').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity 0.4s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 420);
    }, 4000);
  });
})();
</script>

{{-- Lang-tab switcher --}}
<script>
(function () {
  document.querySelectorAll('.a-lang-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var lang = btn.dataset.lang;
      var group = btn.closest('.a-lang-group') || document;
      group.querySelectorAll('.a-lang-tab').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      group.querySelectorAll('.a-lang-field').forEach(function (f) {
        f.classList.toggle('active', f.dataset.lang === lang);
      });
    });
  });
})();
</script>

{{-- ── Media picker modal ──────────────────────────────────── --}}
<div id="media-picker-overlay" style="display:none">
  <div class="a-picker-modal">
    <div class="a-picker-header">
      <span style="font-weight:600; font-size:15px">Избери изображение</span>
      <input type="text" id="picker-search" placeholder="Търси…"
             style="border:1px solid rgba(15,30,61,0.18); border-radius:5px; padding:6px 12px; font-size:13px; background:#fff; color:#1a2235; flex:1; max-width:220px">
      <button type="button" onclick="closeMediaPicker()" style="background:none; border:none; cursor:pointer; font-size:20px; color:#8a96ad; padding:0 4px">✕</button>
    </div>
    <div id="picker-grid" class="a-picker-grid">
      <div style="padding:40px; text-align:center; color:#8a96ad">Зареждане…</div>
    </div>
    <div class="a-picker-footer">
      <span id="picker-selected-name" style="font-size:13px; color:#8a96ad; flex:1"></span>
      <button type="button" class="a-btn a-btn-ghost a-btn-sm" onclick="closeMediaPicker()">Отказ</button>
      <button type="button" class="a-btn a-btn-primary a-btn-sm" id="picker-confirm-btn" onclick="confirmPickerSelection()" disabled>Избери</button>
    </div>
  </div>
</div>

<script>
// ── Media picker ─────────────────────────────────────────────────────────────
var _pickerCallback   = null;
var _pickerSelected   = null;
var _pickerSearchTimer = null;

function openMediaPicker(callback) {
  _pickerCallback = callback;
  _pickerSelected = null;
  document.getElementById('picker-confirm-btn').disabled = true;
  document.getElementById('picker-selected-name').textContent = '';
  document.getElementById('picker-search').value = '';
  document.getElementById('media-picker-overlay').style.display = 'flex';
  loadPickerImages('');
}

function closeMediaPicker() {
  document.getElementById('media-picker-overlay').style.display = 'none';
  _pickerCallback = null;
  _pickerSelected = null;
}

function confirmPickerSelection() {
  if (_pickerSelected && _pickerCallback) {
    _pickerCallback(_pickerSelected);
    closeMediaPicker();
  }
}

document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('picker-search').addEventListener('input', function () {
    clearTimeout(_pickerSearchTimer);
    var val = this.value;
    _pickerSearchTimer = setTimeout(function () { loadPickerImages(val); }, 300);
  });

  // Close on backdrop click
  document.getElementById('media-picker-overlay').addEventListener('click', function (e) {
    if (e.target === this) closeMediaPicker();
  });
});

function loadPickerImages(search) {
  var grid = document.getElementById('picker-grid');
  grid.innerHTML = '<div style="padding:40px; text-align:center; color:#8a96ad">Зареждане…</div>';

  fetch('{{ route('media.picker-data') }}?search=' + encodeURIComponent(search), {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
  })
  .then(function (r) { return r.json(); })
  .then(function (items) {
    if (!items.length) {
      grid.innerHTML = '<div style="padding:40px; text-align:center; color:#8a96ad">Няма изображения.</div>';
      return;
    }
    grid.innerHTML = '';
    items.forEach(function (img) {
      var cell = document.createElement('div');
      cell.className = 'a-picker-cell';
      cell.dataset.id = img.id;
      cell.innerHTML = '<img src="' + img.thumb_url + '" alt="' + (img.alt_fr || '') + '">'
        + '<div class="a-picker-cell-name">' + img.name + '</div>';
      cell.addEventListener('click', function () {
        document.querySelectorAll('.a-picker-cell').forEach(function (c) { c.classList.remove('selected'); });
        cell.classList.add('selected');
        _pickerSelected = img;
        document.getElementById('picker-selected-name').textContent = img.name;
        document.getElementById('picker-confirm-btn').disabled = false;
      });
      cell.addEventListener('dblclick', function () { confirmPickerSelection(); });
      grid.appendChild(cell);
    });
  });
}
</script>

@stack('scripts')
</body>
</html>
