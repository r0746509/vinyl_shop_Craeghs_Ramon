@extends('layouts.template')

@section('title', $record->title)

@section('main')
    <head>
        <link rel="stylesheet" href="/css/app.css" />
    </head>
    <h1>{{ $record->title }}</h1>
    <div class="row">
        <div class="col-sm-4 text-center">
            <img class="img-thumbnail" id="cover" src="/assets/vinyl.png" data-src="{{ $record->cover }}"
                 alt="{{ $record->title }}">
            <p>
                <a href="#!" class="btn {{ $record->btnClass }} btn-sm btn-block mt-3
                 {{ $record->stock == 0 ? 'disabled' : '' }}">
                    <i class="fas fa-cart-plus mr-3"></i>Add to cart
                </a>
            </p>
            <p class="text-left">Genre: {{ $record->genreName }}<br>
                Stock: {{ $record->stock }}<br>
                Price: € {{ number_format($record->price,2) }}</p>
        </div>
        <div class="col-sm-8">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Track</th>
                    <th scope="col">Length</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script_after')
    <script>
        $(function () {
            // Replace vinyl.png with real cover
            $('#cover').attr('src', $('#cover').data('src'));
            // Get tracks from MusicBrainz API
        });
        // Get tracks from MusicBrainz API
        $.getJSON('{{ $record->recordUrl }}')
            .done(function (data) {
                console.log(data);
                // loop over each track
                $.each(data.media[0].tracks, function (key, value) {
                    // Construct a table row
                    let row = `<tr>
                        <td>${value.position}</td>
                        <td>${value.title}</td>
                        <td>${to_mm_ss(value.recording.length)}</td>
                    </tr>`;
                    // Append the row to the tbody tag
                    $('tbody').append(row);
                });
            })
            .fail(function (error) {
                console.log("error", error);
            })
            function to_mm_ss(duration) {
                let seconds = parseInt((duration / 1000) % 60);
                let minutes = parseInt(duration / (1000 * 60));
                minutes = (minutes < 10) ? '0' + minutes : minutes;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                duration = minutes + ':' + seconds;
                return duration;
            }
    </script>
@endsection

