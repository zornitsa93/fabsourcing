@extends('layouts.admin')

@section('welcome')
    <h1 class="title has-text-centered is-family-primary">{{ __('Администратори') }}</h1>
@endsection

@section('content')
    @if(session()->has('success'))
    <div id="success-message" class="notification is-success has-text-centered">
        {{ session()->get('success') }}
        @php(session()->forget('success'))
    </div>
    @endif

    <div class="pt-2 pb-4">
        <a href="{{ route('admins.create') }}" class="button">Добавяне на нов</a>
    </div>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Име</th>
            <th>Email</th>
            <th>Опции</th>
        </tr>
        </thead>
        <tbody>
        @foreach($admins as $admin)
            <tr>
                <td>{{$admin->name}}</td>
                <td>{{$admin->email}}</td>
                <td>
                    @if(auth()->guard('admin')->user()->isMaster)
                    <form action="{{ route('admins.destroy',$admin->id) }}" method="POST">
                        <a class="button" href="{{ route('admins.edit',$admin->id) }}">
                            <span class="icon is-small">
                                <i class="far fa-edit"></i>
                            </span>
                        </a>
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="button is-light">
                            <span class="icon is-small">
                                <i class="far fa-trash-alt"></i>
                            </span>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
