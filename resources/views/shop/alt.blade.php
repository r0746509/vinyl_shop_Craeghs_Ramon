@extends('layouts.template')

@section('title', 'Shop')

@section('main')

    <h1>Shop alt</h1>
    <hr>
    @foreach($genres as $genre)
        <h1>{{$genre->name}}</h1>
        <ul>
            @foreach($genre->records as $record)
                <li><a href="/shop/{{$record->id}}">{{$record->artist}} - {{$record->title}}</a> | Price: €{{ number_format($record->price,2) }} | Stock: {{$record->stock}}</li>
            @endforeach
        </ul>
        <hr>
    @endforeach
    <style>
        h1 {
            text-transform: capitalize;
        }
    </style>
@endsection
