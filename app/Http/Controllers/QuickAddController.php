<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Serialization;

class QuickAddController extends Controller
{
    public function storeAuthor(Request $request)
    {
        $request->validate(['name' => 'required|unique:authors,name']);
        $author = Author::create(['name' => $request->name]);
        return response()->json($author);
    }

    public function storeSerialization(Request $request)
    {
        $request->validate(['name' => 'required|unique:serializations,name']);
        $serial = Serialization::create(['name' => $request->name]);
        return response()->json($serial);
    }
}
