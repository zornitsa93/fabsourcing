@extends('layouts.admin')

@section('page-title', 'Страници')

@section('content')
  <div class="a-page-header">
    <h1>Страници</h1>
    <a href="{{ route('pages.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Добави нова</a>
  </div>

  <form method="GET" action="{{ route('pages.index') }}" class="a-search-bar">
    <input type="text" name="search" value="{{ $search }}"
           placeholder="Търси по slug или заглавие…">
    <button type="submit" class="a-btn a-btn-ghost a-btn-sm">Търси</button>
    @if($search)
      <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost a-btn-sm">Изчисти</a>
    @endif
  </form>

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th>Slug</th>
          <th>Заглавие (FR)</th>
          <th>Статус</th>
          <th>Ред</th>
          <th>Обновена</th>
          <th style="width:130px">Опции</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pages as $page)
          <tr>
            <td>
              <code style="font-family: monospace; font-size:12px; color:#6b7891">
                {{ $page->slug }}
              </code>
            </td>
            <td>{{ $page->getTranslation('title', 'fr', false) ?: '—' }}</td>
            <td>
              @if($page->published)
                <span class="a-badge a-badge-published">Публикувана</span>
              @else
                <span class="a-badge a-badge-draft">Чернова</span>
              @endif
            </td>
            <td>{{ $page->priority }}</td>
            <td style="font-size:12px; color:#8a96ad">
              {{ $page->updated_at->format('d M Y') }}
            </td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('pages.edit', $page) }}" class="a-btn a-btn-ghost a-btn-sm">Редактирай</a>
                <form action="{{ route('pages.destroy', $page) }}" method="POST"
                      onsubmit="return confirm('Изтрий тази страница?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="a-btn a-btn-danger a-btn-sm">Изтрий</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px;">
              Няма намерени страници.
              @if($search) <a href="{{ route('pages.index') }}" style="color:#2b62d9">Изчисти търсенето</a>@endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($pages->hasPages())
      <div class="a-pagination">
        @if($pages->onFirstPage())
          <span class="a-page-disabled">← Предишна</span>
        @else
          <a href="{{ $pages->previousPageUrl() }}">← Предишна</a>
        @endif

        @foreach($pages->getUrlRange(max(1, $pages->currentPage()-2), min($pages->lastPage(), $pages->currentPage()+2)) as $pageNum => $url)
          @if($pageNum == $pages->currentPage())
            <span class="a-page-active">{{ $pageNum }}</span>
          @else
            <a href="{{ $url }}">{{ $pageNum }}</a>
          @endif
        @endforeach

        @if($pages->hasMorePages())
          <a href="{{ $pages->nextPageUrl() }}">Следваща →</a>
        @else
          <span class="a-page-disabled">Следваща →</span>
        @endif
      </div>
    @endif
  </div>
@endsection
