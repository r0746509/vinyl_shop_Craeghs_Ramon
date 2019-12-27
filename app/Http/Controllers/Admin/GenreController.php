<?php

namespace App\Http\Controllers\Admin;

use App\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{

    public function index()
    {
        $genres = Genre::orderBy('name')
            ->withCount('records')
            ->get();
        $result = compact('genres');
        \Facades\App\Helpers\Json::dump($result);
        return view('admin.genres.index');
    }


    public function create()
    {
        return redirect('admin/genres');
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:genres,name'
        ]);

        $genre = new Genre();
        $genre->name = $request->name;
        $genre->save();
        return response()->json([
            'type' => 'success',
            'text' => "The genre <b>$genre->name</b> has been added"
        ]);
    }


    public function show(Genre $genre)
    {
        return redirect('admin/genres');
    }


    public function edit(Genre $genre)
    {
        return redirect('admin/genres');
    }


    public function update(Request $request, Genre $genre)
    {
        $this->validate($request,[
            'name' => 'required|min:3|unique:genres,name,' . $genre->id
        ]);

        $genre->name = $request->name;
        $genre->save();
        return response()->json([
            'type' => 'success',
            'text' => "The genre <b>$genre->name</b> has been updated"
        ]);
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json([
            'type' => 'success',
            'text' => "The genre <b>$genre->name</b> has been deleted"
        ]);
    }

    public function qryGenres()
    {
        $genres = Genre::orderBy('name')
            ->withCount('records')
            ->get();
        return $genres;
    }
}
