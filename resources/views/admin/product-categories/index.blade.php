@extends('layouts.admin')

@section('page-title', 'Продуктови категории')

@section('content')
  <div class="a-page-header">
    <h1>Продуктови категории</h1>
    <a href="{{ route('product-categories.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Добави нова</a>
  </div>

  @if(session('success'))
    <div class="a-alert a-alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
  @endif
  @if(session('warning'))
    <div class="a-alert" style="margin-bottom:16px; background:#fff8e1; color:#7c5e00; border:1px solid #ffe082; border-radius:6px; padding:12px 16px; font-size:13px">
      ⚠ {{ session('warning') }}
    </div>
  @endif
  @if(session('error'))
    <div class="a-alert a-alert-error" style="margin-bottom:16px">{{ session('error') }}</div>
  @endif

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th style="width:40px; text-align:center">Ред</th>
          <th style="width:60px">Снимка</th>
          <th>Наименование</th>
          <th>Кратко описание (FR)</th>
          <th style="width:70px; text-align:center">★ HP</th>
          <th style="width:90px; text-align:center">Статус</th>
          <th style="width:70px; text-align:center">Продукти</th>
          <th style="width:130px">Опции</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
          <tr>
            <td style="color:#8a96ad; font-size:13px; text-align:center">{{ $cat->sort_order }}</td>
            <td>
              @if($cat->image)
                <img src="{{ $cat->thumb_url }}" alt=""
                     style="width:48px; height:48px; object-fit:cover; border-radius:4px; border:1px solid rgba(15,30,61,0.1)">
              @else
                <div style="width:48px; height:48px; background:#f4f5f7; border-radius:4px; border:1px solid rgba(15,30,61,0.1); display:flex; align-items:center; justify-content:center; color:#c0c8d8; font-size:18px">⬜</div>
              @endif
            </td>
            <td>
              <div style="font-weight:500; color:#1a2235">{{ $cat->getTranslation('name', 'fr', false) ?: '—' }}</div>
              @if($cat->getTranslation('name', 'en', false))
                <div style="font-size:12px; color:#8a96ad">{{ $cat->getTranslation('name', 'en', false) }}</div>
              @endif
              <div style="font-size:11px; color:#b0bac9; margin-top:2px">{{ $cat->slug }}</div>
            </td>
            <td style="font-size:13px; color:#6b7891; max-width:240px">
              {{ Str::limit($cat->getTranslation('description', 'fr', false) ?: '—', 80) }}
            </td>
            <td style="text-align:center">
              @if($cat->featured)
                <span title="Ред: {{ $cat->featured_order }}" style="font-size:18px; line-height:1">★</span>
                @if($cat->featured_order)
                  <div style="font-size:10px; color:#8a96ad; margin-top:2px">{{ $cat->featured_order }}</div>
                @endif
              @else
                <span style="font-size:18px; color:#d8dce8; line-height:1">☆</span>
              @endif
            </td>
            <td style="text-align:center">
              @if($cat->published)
                <span class="a-badge a-badge-published">Публикувана</span>
              @else
                <span class="a-badge a-badge-draft">Чернова</span>
              @endif
            </td>
            <td style="text-align:center; font-size:13px; color:#8a96ad">{{ $cat->products()->count() }}</td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('product-categories.edit', $cat) }}" class="a-btn a-btn-ghost a-btn-sm">Редактирай</a>
                <form action="{{ route('product-categories.destroy', $cat) }}" method="POST"
                      onsubmit="return confirm('Изтрий категория „{{ addslashes($cat->getTranslation('name', 'fr', false)) }}"?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="a-btn a-btn-danger a-btn-sm">Изтрий</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px;">
              Няма намерени категории.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <p style="font-size:12px; color:#8a96ad; margin-top:12px">
    ★ = показва се на началната страница. Началната страница показва само първите 5 по „Ред HP".
  </p>
@endsection
