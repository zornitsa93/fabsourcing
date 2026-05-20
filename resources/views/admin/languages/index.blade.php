@extends('layouts.admin')

@section('page-title', 'Езици')

@section('content')

  <div class="a-page-header">
    <h1>Езици</h1>
  </div>

  @if(session('success'))
    <div class="a-alert a-alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="a-alert a-alert-error" style="margin-bottom:16px">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  {{-- Languages table --}}
  <div class="a-card" style="margin-bottom:32px">
    <table class="a-table">
      <thead>
        <tr>
          <th>Код</th>
          <th>Наименование</th>
          <th style="text-align:center">Статус</th>
          <th style="width:160px">Действия</th>
        </tr>
      </thead>
      <tbody>
        @forelse($languages as $language)
          <tr>
            <td><code style="font-family:monospace; font-size:13px">{{ $language->slug }}</code></td>
            <td style="font-weight:500">{{ $language->name }}</td>
            <td style="text-align:center">
              @if($language->active)
                <span class="a-badge a-badge-published">Активен</span>
              @else
                <span class="a-badge a-badge-draft">Неактивен</span>
              @endif
            </td>
            <td>
              <div class="a-table-actions">
                <form action="{{ route('languages.toggle', $language) }}" method="POST">
                  @csrf
                  <button type="submit" class="a-btn a-btn-ghost a-btn-sm">
                    {{ $language->active ? 'Деактивирай' : 'Активирай' }}
                  </button>
                </form>
                <form action="{{ route('languages.destroy', $language) }}" method="POST"
                      onsubmit="return confirm('Сигурни ли сте? Езикът ще бъде изтрит!')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="a-btn a-btn-danger a-btn-sm">Изтрий</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px">
              Няма добавени езици.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Add language form --}}
  <div class="a-form-card">
    <p class="a-section-title">Добавяне на нов език</p>
    <form action="{{ route('languages.store') }}" method="POST">
      @csrf
      <div style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap">
        <div class="a-field" style="margin-bottom:0; width:120px">
          <label>Код (slug)</label>
          <input type="text" name="slug" placeholder="en" maxlength="10" value="{{ old('slug') }}">
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field" style="margin-bottom:0; width:220px">
          <label>Наименование</label>
          <input type="text" name="name" placeholder="English" value="{{ old('name') }}">
          @error('name')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div>
          <button type="submit" class="a-btn a-btn-primary">+ Добави</button>
        </div>
      </div>
    </form>
  </div>

@endsection
