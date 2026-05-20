@extends('layouts.admin')

@section('page-title', 'Редактирай настройка')

@section('content')
  <div class="a-page-header">
    <h1>Редактирай настройка</h1>
    <a href="{{ route('page-settings.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('page-settings.update', $pageSetting->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="a-form-card">
      <p class="a-section-title">Настройка</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Страница <span style="color:#c62828">*</span></label>
          <select name="page_id" required>
            <option value="">— Изберете страница —</option>
            @foreach($pages as $page)
              <option value="{{ $page->id }}" {{ $pageSetting->page_id == $page->id ? 'selected' : '' }}>
                {{ $page->getValueByFirstLanguage($page->title) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="a-field">
          <label>Тип на полето <span style="color:#c62828">*</span></label>
          <select name="setting_type_id" required>
            <option value="">— Изберете тип —</option>
            @foreach($types as $type)
              <option value="{{ $type->id }}" {{ $pageSetting->setting_type_id == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="a-field-row">
        <div class="a-field">
          <label>Ime на полето</label>
          <input type="text" name="field_name" value="{{ $pageSetting->field_name }}" placeholder="напр. Заглавие на банера" />
        </div>
        <div class="a-field">
          <label>Код</label>
          <input type="text" name="code" value="{{ $pageSetting->code }}" placeholder="напр. banner_title" />
        </div>
      </div>
    </div>

    @php $typeName = $pageSetting->settingType->name ?? ''; @endphp

    @if($typeName === 'file' || $typeName === 'string-no-lang')
      <div class="a-form-card">
        <p class="a-section-title">Съдържание</p>
        <div class="a-field">
          <label>Стойност</label>
          <input type="text" name="content" value="{{ $pageSetting->content }}" />
        </div>
      </div>
    @elseif($typeName === 'string' || $typeName === 'textarea')
      <div class="a-form-card">
        <p class="a-section-title">Съдържание</p>

        <div class="a-lang-group">
          <div class="a-lang-bar">
            <button type="button" class="a-lang-tab active" data-lang="fr">FR</button>
            <button type="button" class="a-lang-tab" data-lang="en">EN</button>
          </div>

          @foreach($languages as $language)
            <div class="a-field a-lang-field {{ $loop->first ? 'active' : '' }}" data-lang="{{ $language->slug }}">
              <label>{{ $language->name }}</label>
              @if($typeName === 'textarea')
                <textarea name="content[{{ $language->slug }}]" rows="5">{{ $pageSetting->content ? $pageSetting->getValueByLanguage($pageSetting->content, $language->slug) : '' }}</textarea>
              @else
                <input type="text" name="content[{{ $language->slug }}]"
                       value="{{ $pageSetting->content ? $pageSetting->getValueByLanguage($pageSetting->content, $language->slug) : '' }}" />
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <div class="a-form-footer">
      <button type="submit" class="a-btn a-btn-primary">Запази</button>
      <a href="{{ route('page-settings.index') }}" class="a-btn a-btn-ghost">Отказ</a>
    </div>
  </form>
@endsection
