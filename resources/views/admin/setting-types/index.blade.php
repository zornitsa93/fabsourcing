@extends('layouts.admin')

@section('welcome')
    <h1 class="title has-text-centered is-family-primary">{{ __('Типове настройки') }}</h1>
@endsection

@section('content')
    @if(session()->has('success'))
        <div class="has-text-success pt-2 pb-2 has-text-weight-bold	">
            {{ session()->get('success') }}
        </div>
    @endif

    @if(auth()->guard('admin')->user()->isMaster)
    <div class="pt-2 pb-4">
        <a href="{{ route('setting-types.create') }}" class="button">Добави нов тип</a>
    </div>
    @endif

    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Име</th>
            <th style="width:150px">Опции</th>
        </tr>
        </thead>
        <tbody>
        @foreach($types as $type)
            <tr>
                <td>{{$type->name}}</td>
                <td><form action="{{ route('setting-types.destroy',$type->id) }}" method="POST">
                        <a class="button" href="{{ route('setting-types.edit',$type->id) }}">
                            <span class="icon is-small">
                                <i class="far fa-edit"></i>
                            </span>
                        </a>
                        @csrf
                        @method('DELETE')

                        @if(auth()->guard('admin')->user()->isMaster)
                        <button type="submit" class="button is-light">
                            <span class="icon is-small">
                                <i class="far fa-trash-alt"></i>
                            </span>
                        </button>
                        @endif
                    </form></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
