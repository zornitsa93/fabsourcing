@extends('layouts.admin')

@section('page-title', 'Услуги')

@section('content')
  <div class="a-page-header">
    <h1>Услуги</h1>
    <a href="{{ route('services-admin.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Добави услуга</a>
  </div>

  @if(session('success'))
    <div class="a-alert a-alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
  @endif

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th style="width:32px"></th>
          <th style="width:60px">Снимка</th>
          <th>Заглавие</th>
          <th>Кратко описание (FR)</th>
          <th style="width:80px; text-align:center">Ширина</th>
          <th style="width:90px; text-align:center">Статус</th>
          <th style="width:130px">Опции</th>
        </tr>
      </thead>
      <tbody id="sortable-services">
        @forelse($services as $service)
          <tr data-id="{{ $service->id }}">
            <td style="cursor:grab; text-align:center; color:#8a96ad; font-size:16px" class="drag-handle">⠿</td>
            <td>
              @if($service->thumb_url)
                <img src="{{ $service->thumb_url }}" alt=""
                     style="width:52px; height:52px; object-fit:cover; border-radius:4px; border:1px solid rgba(15,30,61,0.1)">
              @else
                <div style="width:52px; height:52px; background:#f4f5f7; border-radius:4px; border:1px solid rgba(15,30,61,0.1); display:flex; align-items:center; justify-content:center; color:#c0c8d8; font-size:20px">⬜</div>
              @endif
            </td>
            <td>
              <div style="font-weight:500; color:#1a2235">{{ $service->getTranslation('title','fr',false) ?: '—' }}</div>
              @if($service->getTranslation('title','en',false))
                <div style="font-size:12px; color:#8a96ad">{{ $service->getTranslation('title','en',false) }}</div>
              @endif
              @if($service->slug)
                <div style="font-size:11px; color:#b0bac9; margin-top:2px">{{ $service->slug }}</div>
              @endif
            </td>
            <td style="font-size:13px; color:#6b7891; max-width:260px">
              {{ Str::limit($service->getTranslation('description','fr',false) ?: '—', 90) }}
            </td>
            <td style="text-align:center; font-size:13px; color:#8a96ad">{{ $service->col_span }}/12</td>
            <td style="text-align:center">
              @if($service->published)
                <span class="a-badge a-badge-published">Публикувана</span>
              @else
                <span class="a-badge a-badge-draft">Чернова</span>
              @endif
            </td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('services-admin.edit', $service) }}" class="a-btn a-btn-ghost a-btn-sm">Редактирай</a>
                <form action="{{ route('services-admin.destroy', $service) }}" method="POST"
                      onsubmit="return confirm('Изтрий тази услуга?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="a-btn a-btn-danger a-btn-sm">Изтрий</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px;">
              Няма добавени услуги.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <p style="font-size:12px; color:#8a96ad; margin-top:12px">
    Плъзнете редовете за промяна на реда. Промените се запазват автоматично.
  </p>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
(function () {
  var tbody = document.getElementById('sortable-services');
  if (!tbody) return;
  Sortable.create(tbody, {
    handle: '.drag-handle',
    animation: 150,
    onEnd: function () {
      var order = Array.from(tbody.querySelectorAll('tr[data-id]')).map(function (tr) {
        return parseInt(tr.dataset.id, 10);
      });
      fetch('{{ route('services-admin.reorder') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ order: order }),
      }).catch(function () {});
    },
  });
})();
</script>
@endpush
