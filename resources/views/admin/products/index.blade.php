@extends('layouts.admin')

@section('page-title', 'Продукти')

@section('content')
  <div class="a-page-header">
    <h1>Продукти</h1>
    <div style="display:flex; gap:8px; align-items:center">
      <a href="{{ route('product-categories.index') }}" class="a-btn a-btn-ghost a-btn-sm">Категории</a>
      <a href="{{ route('products.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Добави нов</a>
    </div>
  </div>

  <form method="GET" action="{{ route('products.index') }}" class="a-search-bar">
    <input type="text" name="search" value="{{ $search }}" placeholder="Търси по наименование…">
    <select name="category_id" style="border:1px solid rgba(15,30,61,0.18); border-radius:5px; padding:7px 12px; font-size:13px; background:#fff; color:#1a2235">
      <option value="">Всички категории</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
          {{ $cat->getTranslation('name', 'fr', false) }}
        </option>
      @endforeach
    </select>
    <button type="submit" class="a-btn a-btn-ghost a-btn-sm">Търси</button>
    @if($search || $categoryId)
      <a href="{{ route('products.index') }}" class="a-btn a-btn-ghost a-btn-sm">Изчисти</a>
    @endif
  </form>

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th style="width:32px"></th>
          <th style="width:72px">Снимка</th>
          <th>Наименование (FR)</th>
          <th>Категория</th>
          <th>Статус</th>
          <th>Ред</th>
          <th style="width:130px">Опции</th>
        </tr>
      </thead>
      <tbody id="sortable-products">
        @forelse($products as $product)
          <tr data-id="{{ $product->id }}">
            <td style="cursor:grab; text-align:center; color:#8a96ad; font-size:16px" class="drag-handle">⠿</td>
            <td>
              @if($product->main_image)
                @php
                  $base = pathinfo($product->main_image, PATHINFO_FILENAME);
                  $dir  = pathinfo($product->main_image, PATHINFO_DIRNAME);
                  $thumb = $dir . '/' . $base . '_thumb.' . pathinfo($product->main_image, PATHINFO_EXTENSION);
                  $thumbUrl = Storage::disk('public')->exists($thumb) ? Storage::url($thumb) : Storage::url($product->main_image);
                @endphp
                <img src="{{ $thumbUrl }}" alt="" style="width:52px; height:52px; object-fit:cover; border-radius:4px; border:1px solid rgba(15,30,61,0.1)">
              @else
                <div style="width:52px; height:52px; background:#f4f5f7; border-radius:4px; border:1px solid rgba(15,30,61,0.1); display:flex; align-items:center; justify-content:center; color:#c0c8d8; font-size:20px">⬜</div>
              @endif
            </td>
            <td>
              <div style="font-weight:500; color:#1a2235">{{ $product->getTranslation('name','fr',false) ?: '—' }}</div>
              <div style="font-size:12px; color:#8a96ad">{{ $product->slug }}</div>
            </td>
            <td style="font-size:13px; color:#6b7891">
              {{ $product->category?->getTranslation('name','fr',false) ?: '—' }}
            </td>
            <td>
              @if($product->published)
                <span class="a-badge a-badge-published">Публикуван</span>
              @else
                <span class="a-badge a-badge-draft">Чернова</span>
              @endif
            </td>
            <td style="font-size:13px; color:#8a96ad">{{ $product->sort_order }}</td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('products.edit', $product) }}" class="a-btn a-btn-ghost a-btn-sm">Редактирай</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST"
                      onsubmit="return confirm('Изтрий продукта?');">
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
              Няма намерени продукти.
              @if($search || $categoryId)
                <a href="{{ route('products.index') }}" style="color:#2b62d9">Изчисти търсенето</a>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($products->hasPages())
      <div class="a-pagination">
        @if($products->onFirstPage())
          <span class="a-page-disabled">← Предишна</span>
        @else
          <a href="{{ $products->previousPageUrl() }}">← Предишна</a>
        @endif
        @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $pageNum => $url)
          @if($pageNum == $products->currentPage())
            <span class="a-page-active">{{ $pageNum }}</span>
          @else
            <a href="{{ $url }}">{{ $pageNum }}</a>
          @endif
        @endforeach
        @if($products->hasMorePages())
          <a href="{{ $products->nextPageUrl() }}">Следваща →</a>
        @else
          <span class="a-page-disabled">Следваща →</span>
        @endif
      </div>
    @endif
  </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
(function () {
  var tbody = document.getElementById('sortable-products');
  if (!tbody) return;
  Sortable.create(tbody, {
    handle: '.drag-handle',
    animation: 150,
    onEnd: function () {
      var order = Array.from(tbody.querySelectorAll('tr[data-id]')).map(function (tr) {
        return parseInt(tr.dataset.id, 10);
      });
      fetch('{{ route('products.reorder') }}', {
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
