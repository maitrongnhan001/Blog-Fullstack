<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Facade\FlareClient\Http\Response;

class AdminController extends Controller
{

    public function getTags () {
        $data = Tag::orderBy('id', 'desc')->get();
        return $data;
    }

    //
    public function addTag (Request $request) {
        $this->validate($request, [
            'tagName' => 'required'
        ]);

        $create = Tag::create([
            'tagName' => $request->tagName
        ]);

        return $create;
    }

    public function editTag (Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'tagName' => 'required'
        ]);

        $edit = Tag::where('id',$request->id)->update([
            'tagName' => $request->tagName
        ]);

        return $edit;
    }

    public function deleteTag (Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $edit = Tag::where('id',$request->id)->delete();

        return $edit;
    }

    public function upload (Request $request) {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);

        $imageName = time().'.'.$request->file->extension();
        $request->file->move(public_path('upload'), $imageName);
        return $imageName; 
    }
}
