@extends('layouts.web')

@section('content')
        <p>{{__('Added.')}}</p>


        {{ $page->getAttributeValByLanguage('title',$lang) }}
        {!! $page->getAttributeValByLanguage('content',$lang) !!}
@endsection
 