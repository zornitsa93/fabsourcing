@extends('layouts.admin')

@section('welcome')
    <h1 class="title has-text-centered is-family-primary">{{ __('Добавяне на нов тип') }}</h1>
@endsection

@section('content')
    <div class="pt-2 pb-4">
        <a class="button" href="/admin/setting-types">Назад</a>
    </div>
    @if ($errors->any())
        <div class="section pr-0 pl-0 pt-2 pb-2 help is-danger">
            <strong class="">Whoops!</strong> There were some problems with your input.
        </div>
    @endif


    <section class="section pl-0 pr-0 pt-2">
        <form action="{{ route('setting-types.store') }}" method="POST">
            @csrf
            <label class="label pt-4">Име</label>
            <section class="section p-0">
                 <input class="input @error('name') is-danger @enderror" type="text" name="name" value="{{ old('name') }}">
            </section>


            <div class="field is-grouped pt-2">
                <div class="control">
                    <button class="button is-light">Запази</button>
                </div>
            </div>
        </form>
    </section>
@endsection
