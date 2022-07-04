@extends('layouts.app')

@section('content')
    <div class="generator">
        <div class="container">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наклейки</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr>
                        <th scope="row">{{$item->id}}</th>
                        <td><a href="{{route('client.home', $item->id)}}">{{$item->name}}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection
