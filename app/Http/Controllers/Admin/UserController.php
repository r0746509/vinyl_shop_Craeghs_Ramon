<?php

namespace App\Http\Controllers\Admin;

use App\User;
use ErrorException;
use Facades\App\Helpers\Json;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index(Request $request)
    {
        function NewSort($title, $name, $order)
        {
            return (object)['name' => $title, 'value' => $name . ',' . $order];
        }
        function InterpetSort($value)
        {
            try {
                $split = explode(",", $value);
                return [$split[0], $split[1]];
            } catch (ErrorException $e) { return null; }
        }

        $name = "%" . $request->input('name') . "%" ?? '%';
        $sort = InterpetSort($request->input('sort'));

        //return $request->input('sort');
        $column = $sort[0] ?? "name";
        $order = $sort[1] ?? "asc";

        $sorts = [
            NewSort('Name (A ⇒ Z)', 'name', 'asc'),
            NewSort('Name (Z ⇒ A)', 'name', 'desc'),
            NewSort('Email (A ⇒ Z)', 'email', 'asc'),
            NewSort('Email (Z ⇒ A)', 'email', 'desc'),
            NewSort('Not active', 'active', 'asc'),
            NewSort('Admin', 'admin', 'desc'),
        ];


        // Display a listing of the resource
        $users = User::orderBy($column, $order)
            ->where('name', 'like', $name)
            ->orWhere('email', 'like', $name)
            ->paginate(10)
            // Dankzij appends vergeet hij de query niet bij het switchen van paginas!
            ->appends(['name' => $request->input('name'), 'sort' => $request->input('sort')]);;

        $users_name = '%' . $request->input('name') . '%';

        $result = compact('users', 'sorts');
        \Facades\App\Helpers\Json::dump($result);
        return view('admin.users.index', $result);

    }

    public function create()
    {
        return redirect("admin/users");
    }


    public function store(Request $request)
    {
        //
    }


    public function show(User $user)
    {
        return redirect('admin/users');

    }

    public function edit(User $user)
    {
        if (auth()->id() == $user->id) {
            return abort(403, 'You cannot edit your own profile.');
        }
        $result = compact('user');
        \Facades\App\Helpers\Json::dump($result);
        return view('admin.users.edit', $result);
    }


    public function update(Request $request, User $user)
    {
        if (auth()->id() == $user->id) {
            session()->flash('danger', 'In order not to exclude yourself from (the admin section of) the application, '.
                'you cannot modify your own profile');
            return abort(403, 'You cannot edit your own profile.');
        };

        $this->validate($request,[
            'name' => 'required|:users,name,' ,
            'email' => 'required|unique:users,email,' . $user->id
        ]);
        $user->email = $request->email;
        $user->name = $request->name;
        $user->active = $request->active;
        $user->admin = $request->admin;
        $user->save();
        session()->flash('success', "The user  <b>{$user->name}</b> has been updated");
        return redirect('admin/users');

    }


    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            session()->flash('danger', 'In order not to exclude yourself from (the admin section of) the application, '.
                'you cannot delete your own profile');
            return abort(403, 'You cannot edit your own profile.');
        };

        // Remove the specified resource from storage
        $user->delete();
        session()->flash('success', 'The user <b>' . $user->name . '</b> has been deleted');
        return response()->json([
            'type' => 'success',
            'text' => "The user <b>$user->name</b> has been deleted"
        ]);
    }

}
