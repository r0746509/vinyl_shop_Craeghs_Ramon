@extends('layouts.template')

@section('title', 'Edit user')

@section('main')
    <h1>Edit user: {{ $user->name }}</h1>
    <form action="/admin/users/{{ $user->id }}" method="post">
        @method('put')
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Name"
                   minlength="3"
                   required
                   value="{{ old('name', $user->name) }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Name"
                   required minlength="36" maxlength="36"
                   value="{{ old('email', $user->email) }}">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <input type="hidden" name="active" value="0" />
            <input type="checkbox" name="active" value="1"
                   @if($user->active or old('active'))
                   checked
                @endif/> Active &nbsp
            <input type="hidden" name="admin" value="0" />
            <input type="checkbox" name="admin" value="1"
                   @if($user->admin or old('admin'))
                   checked
                @endif/> Admin &nbsp
        </div>
        <button type="submit" class="btn btn-success">Save user</button>
    </form>
@endsection
