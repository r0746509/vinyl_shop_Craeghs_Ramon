@extends('layouts.template')

@section('title', 'Shop')

@section('main')
    <h1>Shop</h1>
    <div class="row">
        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
            <div class="card">
                <img class="card-img-top" src="/assets/vinyl.png" alt="">
                <div class="card-body">
                    <h5 class="card-title">Artist</h5>
                    <p class="card-text">Record title</p>
                    <a href="#!" class="btn btn-outline-info btn-sm btn-block">Show details</a>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <p>genre</p>
                    <p>
                        € price
                        <span class="ml-3 badge badge-success">stock</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
