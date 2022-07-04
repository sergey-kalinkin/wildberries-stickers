@extends('layouts.app')

@section('content')
    <div id="generator">
        <Generator
            :spreadsheet="{{$spreadsheet ?? '[]'}}"
            :fields="{{$required_fields ?? '{}'}}"
            :keys="{{$keys ?? '[]'}}"
            :excluded-fields="{{$excluded ?? '[]'}}"
            :id="{{$id ?? 0}}"
        >

        </Generator>
    </div>

    @include('app.elements.faq')
@endsection
