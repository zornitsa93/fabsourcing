@extends('layouts.admin')

@section('page-title', 'Добави настройка на страница')

@section('content')
  <div class="a-page-header">
    <h1>Добави настройка</h1>
    <a href="{{ route('page-settings.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('page-settings.store') }}" method="POST">
    @csrf

    <div class="a-form-card">
      <p class="a-section-title">Настройка</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Страница <span style="color:#c62828">*</span></label>
          <select name="page_id" required>
            <option value="">— Изберете страница —</option>
            @foreach($pages as $page)
              <option value="{{ $page->id }}"
                {{ old('page_id', $pageId ?? null) == $page->id ? 'selected' : '' }}>
                {{ $page->getValueByFirstLanguage($page->title) }}
              </option>
            @endforeach
          </select>
          @error('page_id')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>

        <div class="a-field">
          <label>Тип на полето <span style="color:#c62828">*</span></label>
          <select name="setting_type_id" required>
            <option value="">— Изберете тип —</option>
            @foreach($types as $type)
              <option value="{{ $type->id }}" {{ old('setting_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
          @error('setting_type_id')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="a-field-row">
        <div class="a-field">
          <label>Ime на полето</label>
          <input type="text" name="field_name" value="{{ old('field_name') }}" placeholder="напр. Заглавие на банера" />
          @error('field_name')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Код <span style="color:#c62828">*</span></label>
          <input type="text" name="code" value="{{ old('code') }}" placeholder="напр. banner_title" />
          @error('code')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" class="a-btn a-btn-primary">Запази</button>
      <a href="{{ route('page-settings.index') }}" class="a-btn a-btn-ghost">Отказ</a>
    </div>
  </form>
@endsection
