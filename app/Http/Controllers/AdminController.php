<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Tag;
use Facade\FlareClient\Http\Response;
use GrahamCampbell\ResultType\Result;

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

    public function deleteImage (Request $request) {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }

    public function deleteFileFromServer ($fileName) {
        $filePath = public_path().'/upload/'.$fileName;
        echo $filePath;
        if (file_exists($filePath)) {
            @unlink($filePath);
        } else {
            $filePath = public_path().$fileName;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        return;
    }

    public function getCategories () {
        $data = Category::orderBy('id', 'desc')->get();
        return $data;
    }

    public function addCategory (Request $request) {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        $create = Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);

        return $create;
    }

    public function editCategory (Request $request) {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        $edit = Category::where('id',$request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);

        return $edit;
    }
}
