<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Record;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $genre_id = $request->input('genre_id') ?? '%'; //OR $genre_id = $request->genre_id ?? '%';
        $artist_title = '%' . $request->input('artist') . '%'; //OR $artist_title = '%' . $request->artist . '%';
        $records = Record::with('genre')->orderBy('artist', 'asc')
            ->where(function ($query) use ($artist_title, $genre_id) {
                $query->where('artist', 'like', $artist_title)
                    ->where('genre_id', 'like', $genre_id);
            })
            ->paginate(12)
            ->appends(['artist'=> $request->input('artist'), 'genre_id' => $request->input('genre_id')]);

        foreach ($records as $record) {
            if(!$record->cover) {
                $record->cover = 'https://coverartarchive.org/release/' . $record->title_mbid . '/front-250.jpg';
            }
        }               // get all records
        $result = compact('records');           // compact('records') is the same as ['records' => $records]
        $genres = Genre::orderBy('name', 'asc')
            ->has('records')        // only genres that have one or more records
            ->withCount('records')  // add a new property 'records_count' to the Genre models/objects
            ->get()
            ->transform(function ($item, $key) {
            // Set first letter of name to uppercase and add the counter
            $item->name = ucfirst($item->name) . ' (' . $item->records_count . ')';
            // Remove all fields that you don't use inside the view
            unset($item->created_at, $item->updated_at, $item->records_count);
            return $item;
        });
        $result = compact('genres', 'records');     // $result = ['genres' => $genres, 'records' => $records]
        \Facades\App\Helpers\Json::dump($result);                    // open http://vinyl_shop.test/shop?json
        return view('shop.index', $result);
    }

    public function show($id)
    {
        $record = Record::with('genre')->findOrFail($id);
        $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-250.jpg";
        $record->title = $record->artist . ' - ' . $record->title;
        $record->artistUrl = 'https://musicbrainz.org/ws/2/artist/' . $record->artist_mbid . '?inc=url-rels&fmt=json';
        $record->recordUrl = 'https://musicbrainz.org/ws/2/release/' . $record->title_mbid . '?inc=recordings+url-rels&fmt=json';
        $record->btnClass = $record->stock > 0 ? 'btn-outline-success' : 'btn-outline-danger';
        $record->genreName = $record->genre->name;
        unset($record->genre_id, $record->artist, $record->created_at, $record->updated_at, $record->artist_mbid, $record->title_mbid, $record->genre);
        $result = compact('record');
        \Facades\App\Helpers\Json::dump($result);
        return view('shop.show', $result);  // Pass $result to the vie
    }

    public function alt()
    {
        $genres = Genre::orderBy('name')
            ->has('records')
            ->with('records')
            ->get();
        $result = compact('genres');
        \Facades\App\Helpers\Json::dump($result);

        return view('shop.alt', $result);
    }

}

