@extends('layouts.template')

@section('title', 'Users')

@section('main')
    <h1>Users</h1>
    @include('shared.alert')
    <div class="table">

        <form method="get" action="/admin/users" id="searchForm">
            <div class="row">
                <div class="col-sm-8 mb-2">
                    <input type="text" class="form-control" name="name" id="name"
                           value="{{ request()->name }}" placeholder="Filter Name Or Email">
                </div>
                <div class="col-sm-4 mb-2">
                    <select class="form-control" name="sort" id="sort">
                        @foreach($sorts as $sort)
                            <option value="{{ $sort->value }}"
                                {{ request() -> sort == $sort->value ? 'selected' : ''}}
                            >{{ $sort->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>user</th>
                <th>Email</th>
                <th>Active</th>
                <th>Admin</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    @if ( $user->active == 1)
                        <td style="color: #2fa360"><i class="fas fa-check fa-2x"></i></td>
                    @else
                        <td></td>
                    @endif
                    @if ( $user->admin == 1)
                        <td style="color: #2fa360"><i class="fas fa-check fa-2x "></i></td>
                    @else
                        <td></td>
                    @endif

                    <td data-id="{{$user->id}}" data-name="{{$user->name}}">
                        <div class="btn-group btn-group-sm  ">
                            <a href="/admin/users/{{$user->id}}/edit" class="btn btn-outline-success btn-edit @if(auth()->id() == $user->id) disabled @endif"
                               data-toggle="tooltip" title="Edit {{$user->name}}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#!" class="btn btn-outline-danger btn-delete @if(auth()->id() == $user->id) disabled @endif"
                               data-toggle="tooltip" title="Delete {{$user->name}}">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}

    @if ($users->count() == 0)
        <div class="alert alert-danger alert-dismissible fade show">
            No user containing <b>{{request() -> name}}</b> in their name or email has been found.
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif
@endsection

@section('script_after')
    <script>
        $(function () {
            $('#name').blur(function () {
                $('#searchForm').submit();
            });
            $('#sort').change(function () {
                $('#searchForm').submit();
            });

            $('tbody').on('click', '.btn-delete', function () {
                let id = $(this).closest('td').data('id');
                let name = $(this).closest('td').data('name');
                let text = `<p>Delete the user <b>${name}</b>?</p>`;
                let type = 'warning';
                let btnText = 'Delete user';
                let btnClass = 'btn-success';
                let modal = new Noty({
                    timeout: false,
                    layout: 'center',
                    modal: true,
                    type: type,
                    text: text,
                    buttons: [
                        Noty.button(btnText, `btn ${btnClass}`, function () {
                            deleteUser(id);
                            modal.close();
                        }),
                        Noty.button('Cancel', 'btn btn-secondary ml-2', function () {
                            modal.close();
                        })
                    ]
                }).show();
            });
        });

        // Delete a user
        function deleteUser(id) {
            // Delete the user from the database
            let pars = {
                '_token': '{{ csrf_token() }}',
                '_method': 'delete'
            };
            $.post(`/admin/users/${id}`, pars, 'json')
                .done(function (data) {
                    console.log('data', data);
                    location.replace("/admin/users");
                })
                .fail(function (e) {
                    console.log('error', e);
                    location.replace("/admin/users");
                });
        }
    </script>
@endsection
