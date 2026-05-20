@extends('layouts.admin')

@section('welcome')
    <h1 class="title has-text-centered is-family-primary">{{ __('Редактиране на Администратор') }}</h1>
@endsection

@section('content')
    <div class="pt-2 pb-4">
        <a class="button" href="/admin/admins">Назад</a>
    </div>
    @if ($errors->any())
        <div class="section pr-0 pl-0 pt-2 pb-2 help is-danger">
            <strong class="">Whoops!</strong> There were some problems with your input.
            {{--            <ul>--}}
            {{--                @foreach ($errors->all() as $error)--}}
            {{--                    <li class="help is-danger">{{ $error }}</li>--}}
            {{--                @endforeach--}}
            {{--            </ul>--}}
        </div>
    @endif
    <section class="section pl-0 pr-0 pt-2">
        <form action="{{ route('admins.update',$admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="field">
                <label class="label">Име</label>
                <div class="control">
                    <input class="input @error('name') is-danger @enderror" type="text" name="name" required value="{{ $admin->name }}">
                    @error('name')
                    <p class="help is-danger">{{ $errors->first('name') }}</p>
                    @enderror
                </div>
            </div>

            <div class="field">
                <label class="label">Email</label>
                <div class="control">
                    <input class="input  @error('email') is-danger @enderror" type="email" name="email" required  value="{{ $admin->email }}">
                </div>
                @error('email')
                <p class="help is-danger">{{ $errors->first('email') }}</p>
                @enderror
            </div>

            <div class="field">
                <label class="label">Парола</label>
                <div class="control">
                    <input class="input  @error('password') is-danger @enderror" type="password" name="password" autocomplete="new-password">
                </div>
                @error('password')
                <p class="help is-danger">{{ $errors->first('password') }}</p>
                @enderror
            </div>

            <div class="field is-grouped pt-2">
                <div class="control">
                    <button class="button is-light">Запази</button>
                </div>
            </div>
        </form>
    </section>
@endsection
