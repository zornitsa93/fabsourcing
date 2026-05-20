@extends('layouts.admin')

@section('welcome')
    <h1 class="title has-text-centered is-family-primary">Управление на езици</h1>
@endsection

@section('content')

    @if (session('success'))
        <div id="success-message" class="notification is-success is-light mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="notification is-danger is-light mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add language form --}}
    <div class="box mb-5">
        <h2 class="subtitle mb-3">Добавяне на нов език</h2>
        <form action="{{ route('languages.store') }}" method="POST">
            @csrf
            <div class="columns is-vcentered">
                <div class="column is-2">
                    <div class="field">
                        <label class="label">Код (slug)</label>
                        <div class="control">
                            <input class="input @error('slug') is-danger @enderror"
                                   type="text" name="slug"
                                   placeholder="en" maxlength="10"
                                   value="{{ old('slug') }}">
                        </div>
                        <p class="help">Напр.: en, de, fr</p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="field">
                        <label class="label">Име на езика</label>
                        <div class="control">
                            <input class="input @error('name') is-danger @enderror"
                                   type="text" name="name"
                                   placeholder="English"
                                   value="{{ old('name') }}">
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="field">
                        <label class="label">&nbsp;</label>
                        <div class="control">
                            <button type="submit" class="button is-primary">+ Добави език</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Languages table --}}
    <table class="table is-fullwidth is-striped is-hoverable">
        <thead>
            <tr>
                <th>Код</th>
                <th>Наименование</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($languages as $language)
                <tr>
                    <td><code>{{ $language->slug }}</code></td>
                    <td>{{ $language->name }}</td>
                    <td>
                        @if ($language->active)
                            <span class="tag is-success">Активен</span>
                        @else
                            <span class="tag is-light">Неактивен</span>
                        @endif
                    </td>
                    <td>
                        {{-- Toggle active --}}
                        <form action="{{ route('languages.toggle', $language) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="button is-small {{ $language->active ? 'is-warning' : 'is-success' }}">
                                {{ $language->active ? 'Деактивирай' : 'Активирай' }}
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form action="{{ route('languages.destroy', $language) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('Сигурни ли сте? Езикът и всички негови преводи ще бъдат изтрити!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button is-small is-danger">Изтрий</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="has-text-centered">Няма добавени езици.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection
