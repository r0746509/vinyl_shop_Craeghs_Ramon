@extends('layouts.template')

@section('script_after')
    <script>
        $(function () {
            // Get record id and redirect to the detail page
            $('.card').click(function () {
                var record_id = $(this).data('id');
                $(location).attr('href', `/shop/${record_id}`); //OR $(location).attr('href', '/shop/' + record_id);
            });
            // Replace vinyl.png with real cover
            $('.card img').each(function () {
                $(this).attr('src', $(this).data('src'));
            });
            // Add shadow to card on hover
            $('.card').hover(function () {
                $(this).addClass('shadow');
            }, function () {
                $(this).removeClass('shadow');
            });
            // submit form when leaving text field 'artist'
            $('#artist').blur(function () {
                $('#searchForm').submit();
            });
            // submit form when changing dropdown list 'genre_id'
            $('#genre_id').change(function () {
                $('#searchForm').submit();
            });
        })
    </script>
@endsection
@section('title', 'Shop')

@section('main')
    <h1>Shop</h1>
    <form method="get" action="/shop" id="searchForm">
        <div class="row">
            <div class="col-sm-8 mb-2">
                <input type="text" class="form-control" name="artist" id="artist"
                       value="{{ request()-> artist }}"
                       value="" placeholder="Filter Artist Or Record">
            </div>
            <div class="col-sm-4 mb-2">
                <select class="form-control" name="genre_id" id="genre_id">
                    <option value="%">All genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}"
                            {{ (request()->genre_id ==  $genre->id ? 'selected' : '') }}>{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <hr>

    @if ($records->count() == 0 && request()->genre_id == '%')
        <div class="alert alert-danger alert-dismissible fade show">
            Can't find any artist or album with <b>'{{ request()->artist }}'</b>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @elseif($records->count() == 0)
        <div class="alert alert-danger alert-dismissible fade show">
            Can't find any artist or album with <b>'{{ request()->artist }}'</b> for the genre <b>'
                @foreach($genres as $genre)
                    @if($genre->id == request()->genre_id)
                        {{ explode('(', $genre->name)[0] }}
                    @endif
                @endforeach
                '</b>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    {{ $records->links() }}
    <div class="row">
        @foreach($records as $record)
            <div class="col-sm-6 col-md-4 col-lg-3 mb-3 d-flex align-items-lg-stretch">
                <div class="card" data-id="{{ $record -> id }}">
                    <img class="card-img-top" src="{{ $record->cover }}" alt="{{ $record->artist }} - {{ $record->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $record->artist }}</h5>
                        <p class="card-text">{{ $record->title }}</p>
                        <a href="shop/{{ $record->id }}" class="btn btn-outline-info btn-sm btn-block">Show details</a>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <p>{{ $record->genre->name }}</p>
                        <p>
                            € {{ number_format($record->price,2) }}
                            <span class="ml-3 badge badge-success">{{ $record->stock }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <style>

        .card {
                cursor: pointer;
            }
        .card .btn, form .btn {
                display: none;
            }

    </style>
    {{ $records->links() }}
@endsection
