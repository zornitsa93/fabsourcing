@extends('layouts.admin')

@section('page-title', 'Настройки на страници')

@section('content')
  <div class="a-page-header">
    <h1>Настройки на страници</h1>
    @if(auth()->guard('admin')->user()->isMaster)
      <a href="{{ route('page-settings.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Добави настройка</a>
    @endif
  </div>

  @if(session('success'))
    <div class="a-alert a-alert-success">{{ session('success') }}</div>
  @endif

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th>Страница</th>
          <th>Поле</th>
          <th>Код</th>
          <th>Тип</th>
          <th style="width:140px">Опции</th>
        </tr>
      </thead>
      <tbody>
        @forelse($settings as $setting)
          <tr>
            <td>{{ $setting->page ? $setting->page->getValueByFirstLanguage($setting->page->title) : '—' }}</td>
            <td>{{ $setting->field_name }}</td>
            <td><code style="font-size:12px;color:#6b7891">{{ $setting->code }}</code></td>
            <td>{{ $setting->settingType->name }}</td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('page-settings.edit', $setting->id) }}" class="a-btn a-btn-ghost a-btn-sm">Редактирай</a>
                @if(auth()->guard('admin')->user()->isMaster)
                  <form action="{{ route('page-settings.destroy', $setting->id) }}" method="POST"
                        onsubmit="return confirm('Изтрий тази настройка?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="a-btn a-btn-danger a-btn-sm">Изтрий</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px;">
              Няма настройки на страниците.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
