<?php

namespace App\Http\Controllers;

use App\Record;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $records = Record::get();               // get all records
        $result = compact('records');           // compact('records') is the same as ['records' => $records]
        \Facades\App\Helpers\Json::dump($result);                    // open http://vinyl_shop.test/shop?json
        return view('shop.index', $result);
    }

    public function show($id)
    {
        return view('shop.show', ['id' => $id]);  // Send $id to the view
    }

}
