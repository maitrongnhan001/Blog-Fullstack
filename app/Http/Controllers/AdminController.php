<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class AdminController extends Controller
{
    //
    public function addTag (Request $request) {
        $create = Tag::create([
            'tagName' => $request->tagName
        ]);

        return $create;
    }

    public function getTags () {
        $data = Tag::orderBy('id', 'desc')->get();
        return $data;
    }
}
