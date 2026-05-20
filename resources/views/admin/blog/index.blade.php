@extends('layouts.admin')

@section('page-title', 'Блог')

@section('content')
  <div class="a-page-header">
    <h1>Блог статии</h1>
    <a href="{{ route('blog-posts.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Нова статия</a>
  </div>

  <form method="GET" action="{{ route('blog-posts.index') }}" class="a-search-bar">
    <input type="text" name="search" value="{{ $search }}" placeholder="Търси по заглавие…">
    <select name="status" style="border:1px solid rgba(15,30,61,0.18); border-radius:5px; padding:7px 12px; font-size:13px; background:#fff; color:#1a2235">
      <option value="">Всички статуси</option>
      <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Публикувани</option>
      <option value="draft"     {{ $status === 'draft'     ? 'selected' : '' }}>Чернови</option>
      <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>Насрочени</option>
    </select>
    <button type="submit" class="a-btn a-btn-ghost a-btn-sm">Търси</button>
    @if($search || $status)
      <a href="{{ route('blog-posts.index') }}" class="a-btn a-btn-ghost a-btn-sm">Изчисти</a>
    @endif
  </form>

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th style="width:60px">Снимка</th>
          <th>Заглавие (FR)</th>
          <th>Автор</th>
          <th>Статус</th>
          <th>Публикувано</th>
          <th>Четене</th>
          <th style="width:130px">Опции</th>
        </tr>
      </thead>
      <tbody>
        @forelse($posts as $post)
          <tr>
            <td>
              @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" alt=""
                     style="width:48px; height:36px; object-fit:cover; border-radius:3px; border:1px solid rgba(15,30,61,0.1)">
              @else
                <div style="width:48px; height:36px; background:#f4f5f7; border-radius:3px; border:1px solid rgba(15,30,61,0.1)"></div>
              @endif
            </td>
            <td>
              <div style="font-weight:500; color:#1a2235">{{ $post->getTranslation('title','fr',false) ?: '—' }}</div>
              <div style="font-size:12px; color:#8a96ad">{{ $post->slug }}</div>
            </td>
            <td style="font-size:13px; color:#6b7891">{{ $post->author_name }}</td>
            <td>
              @php $st = $post->status; @endphp
              @if($st === 'published')
                <span class="a-badge a-badge-published">Публикувана</span>
              @elseif($st === 'scheduled')
                <span class="a-badge" style="background:rgba(33,100,200,0.1); color:#2164c8">Насрочена</span>
              @else
                <span class="a-badge a-badge-draft">Чернова</span>
              @endif
            </td>
            <td style="font-size:12px; color:#8a96ad">
              {{ $post->published_at ? $post->published_at->format('d M Y H:i') : '—' }}
            </td>
            <td style="font-size:12px; color:#8a96ad">
              {{ $post->reading_time_minutes ? $post->reading_time_minutes . ' мин' : '—' }}
            </td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('blog-posts.edit', $post) }}" class="a-btn a-btn-ghost a-btn-sm">Редактирай</a>
                <form action="{{ route('blog-posts.destroy', $post) }}" method="POST"
                      onsubmit="return confirm('Изтрий статията?');">
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
              Няма намерени статии.
              @if($search || $status)
                <a href="{{ route('blog-posts.index') }}" style="color:#2b62d9">Изчисти търсенето</a>
              @endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($posts->hasPages())
      <div class="a-pagination">
        @if($posts->onFirstPage())
          <span class="a-page-disabled">← Предишна</span>
        @else
          <a href="{{ $posts->previousPageUrl() }}">← Предишна</a>
        @endif
        @foreach($posts->getUrlRange(max(1,$posts->currentPage()-2), min($posts->lastPage(),$posts->currentPage()+2)) as $pn => $url)
          @if($pn == $posts->currentPage())
            <span class="a-page-active">{{ $pn }}</span>
          @else
            <a href="{{ $url }}">{{ $pn }}</a>
          @endif
        @endforeach
        @if($posts->hasMorePages())
          <a href="{{ $posts->nextPageUrl() }}">Следваща →</a>
        @else
          <span class="a-page-disabled">Следваща →</span>
        @endif
      </div>
    @endif
  </div>
@endsection
