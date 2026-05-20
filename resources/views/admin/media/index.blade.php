@extends('layouts.admin')

@section('page-title', 'Медия библиотека')

@section('content')
  <div class="a-page-header">
    <h1>Медия библиотека</h1>
  </div>

  {{-- Upload drop zone --}}
  <div class="a-media-dropzone" id="upload-dropzone">
    <div class="a-media-dropzone-inner" id="dropzone-inner">
      <span style="font-size:32px; display:block; margin-bottom:8px; color:#8a96ad">⬆</span>
      <p style="font-size:14px; color:#6b7891; margin-bottom:4px">Провлачете файлове тук</p>
      <p style="font-size:12px; color:#8a96ad">или</p>
      <label class="a-btn a-btn-ghost a-btn-sm" style="margin-top:8px; cursor:pointer">
        Изберете файлове
        <input type="file" id="upload-input" accept="image/*" multiple style="display:none">
      </label>
    </div>
    <div id="upload-progress" style="display:none; padding:12px 16px">
      <div id="upload-bar-wrap" style="background:rgba(15,30,61,0.1); border-radius:4px; overflow:hidden; height:6px">
        <div id="upload-bar" style="height:6px; background:#2b62d9; width:0; transition:width 0.2s"></div>
      </div>
      <p id="upload-status" style="font-size:12px; color:#6b7891; margin-top:6px">Качване…</p>
    </div>
  </div>

  {{-- Search --}}
  <form method="GET" action="{{ route('media.index') }}" class="a-search-bar" style="margin-top:16px">
    <input type="text" name="search" value="{{ $search }}" placeholder="Търси по файлово наименование…">
    <button type="submit" class="a-btn a-btn-ghost a-btn-sm">Търси</button>
    @if($search)
      <a href="{{ route('media.index') }}" class="a-btn a-btn-ghost a-btn-sm">Изчисти</a>
    @endif
  </form>

  {{-- Grid --}}
  <div class="a-media-grid" id="media-grid">
    @forelse($items as $item)
      <div class="a-media-cell" data-id="{{ $item->id }}" onclick="showMediaDetail({{ $item->id }})">
        <div class="a-media-thumb">
          <img src="{{ $item->thumb_url }}" alt="{{ $item->getTranslation('alt_text','fr',false) }}">
        </div>
        <div class="a-media-name">{{ Str::limit($item->original_name, 22) }}</div>
        <div class="a-media-size">{{ $item->size_formatted }}</div>
      </div>
    @empty
      <div style="grid-column:1/-1; text-align:center; padding:60px; color:#8a96ad; font-size:14px">
        Няма качени изображения. Провлачете файлове по-горе.
      </div>
    @endforelse
  </div>

  @if($items->hasPages())
    <div class="a-pagination" style="margin-top:16px">
      @if($items->onFirstPage())
        <span class="a-page-disabled">← Предишна</span>
      @else
        <a href="{{ $items->previousPageUrl() }}">← Предишна</a>
      @endif
      @foreach($items->getUrlRange(max(1,$items->currentPage()-2), min($items->lastPage(),$items->currentPage()+2)) as $pn => $url)
        @if($pn == $items->currentPage())
          <span class="a-page-active">{{ $pn }}</span>
        @else
          <a href="{{ $url }}">{{ $pn }}</a>
        @endif
      @endforeach
      @if($items->hasMorePages())
        <a href="{{ $items->nextPageUrl() }}">Следваща →</a>
      @else
        <span class="a-page-disabled">Следваща →</span>
      @endif
    </div>
  @endif

  {{-- Detail panel (slide-in) --}}
  <div id="media-detail-overlay" style="display:none">
    <div id="media-detail-panel">
      <button class="a-media-detail-close" onclick="closeMediaDetail()">✕</button>
      <div id="media-detail-body">
        <div style="text-align:center; padding:40px; color:#8a96ad">Зареждане…</div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
var CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── Upload ────────────────────────────────────────────────────────────────
(function () {
  var zone  = document.getElementById('upload-dropzone');
  var input = document.getElementById('upload-input');
  var prog  = document.getElementById('upload-progress');
  var bar   = document.getElementById('upload-bar');
  var status = document.getElementById('upload-status');
  var inner  = document.getElementById('dropzone-inner');

  zone.addEventListener('dragover', function (e) { e.preventDefault(); zone.classList.add('drag-over'); });
  zone.addEventListener('dragleave', function () { zone.classList.remove('drag-over'); });
  zone.addEventListener('drop', function (e) {
    e.preventDefault();
    zone.classList.remove('drag-over');
    uploadFiles(e.dataTransfer.files);
  });
  input.addEventListener('change', function () { uploadFiles(this.files); this.value = ''; });

  function uploadFiles(files) {
    if (!files.length) return;
    var fd = new FormData();
    Array.from(files).forEach(function (f) { fd.append('files[]', f); });
    fd.append('_token', CSRF);

    inner.style.display = 'none';
    prog.style.display  = 'block';
    bar.style.width     = '20%';
    status.textContent  = 'Качване на ' + files.length + ' файл(а)…';

    fetch('{{ route('media.upload') }}', { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        bar.style.width    = '100%';
        status.textContent = 'Успешно качени ' + data.uploaded.length + ' файл(а).';
        setTimeout(function () { location.reload(); }, 800);
      })
      .catch(function () {
        status.textContent = 'Грешка при качването.';
        bar.style.background = '#c62828';
      });
  }
})();

// ── Detail panel ──────────────────────────────────────────────────────────
function showMediaDetail(id) {
  var overlay = document.getElementById('media-detail-overlay');
  var body    = document.getElementById('media-detail-body');
  overlay.style.display = 'flex';
  body.innerHTML = '<div style="text-align:center; padding:40px; color:#8a96ad">Зареждане…</div>';

  fetch('{{ url('admin/media') }}/' + id, {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
  })
  .then(function (r) { return r.json(); })
  .then(function (d) {
    var usagesHtml = '';
    if (d.usages && d.usages.length) {
      usagesHtml = '<div class="a-media-uses"><p style="font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#8a96ad;margin-bottom:6px">Използва се в</p>';
      d.usages.forEach(function (u) {
        usagesHtml += '<a href="' + u.edit + '" class="a-media-use-item">' + u.type + ': ' + u.label + '</a>';
      });
      usagesHtml += '</div>';
    }

    body.innerHTML = '<img src="' + d.url + '" style="width:100%; border-radius:5px; margin-bottom:12px">'
      + '<p class="a-media-meta">' + d.original_name + '</p>'
      + '<p class="a-media-meta">' + (d.width && d.height ? d.width + '×' + d.height + 'px · ' : '') + d.size_formatted + '</p>'
      + '<div style="margin:14px 0">'
      + '<label style="font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#8a96ad">Alt text (FR)</label>'
      + '<input type="text" id="alt-fr" value="' + (d.alt_fr||'') + '" style="width:100%;margin-top:4px;border:1px solid rgba(15,30,61,0.18);border-radius:5px;padding:7px 12px;font-size:13px">'
      + '<label style="font-size:11px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#8a96ad;margin-top:10px;display:block">Alt text (EN)</label>'
      + '<input type="text" id="alt-en" value="' + (d.alt_en||'') + '" style="width:100%;margin-top:4px;border:1px solid rgba(15,30,61,0.18);border-radius:5px;padding:7px 12px;font-size:13px">'
      + '</div>'
      + '<div style="display:flex;gap:8px;margin-bottom:12px">'
      + '<button class="a-btn a-btn-primary a-btn-sm" onclick="saveAlt(' + d.id + ')">Запази alt</button>'
      + '<button class="a-btn a-btn-danger a-btn-sm" onclick="deleteMedia(' + d.id + ', ' + (d.usages && d.usages.length ? 'true' : 'false') + ')">Изтрий</button>'
      + '</div>'
      + usagesHtml;
  });
}

function closeMediaDetail() {
  document.getElementById('media-detail-overlay').style.display = 'none';
}

function saveAlt(id) {
  fetch('{{ url('admin/media') }}/' + id, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ alt_fr: document.getElementById('alt-fr').value, alt_en: document.getElementById('alt-en').value })
  }).then(function () {
    var btn = event.target;
    var orig = btn.textContent;
    btn.textContent = '✓ Записано';
    setTimeout(function () { btn.textContent = orig; }, 1500);
  });
}

function deleteMedia(id, inUse) {
  var msg = inUse
    ? 'Внимание: изображението се използва. Все пак да го изтрия?'
    : 'Изтрий изображението?';
  if (!confirm(msg)) return;

  fetch('{{ url('admin/media') }}/' + id, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': CSRF }
  })
  .then(function (r) { return r.json(); })
  .then(function (d) {
    if (d.ok) {
      closeMediaDetail();
      var cell = document.querySelector('[data-id="' + id + '"]');
      if (cell) cell.remove();
    } else {
      alert(d.error || 'Грешка при изтриването.');
    }
  });
}
</script>
@endpush
