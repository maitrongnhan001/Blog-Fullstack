<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use GrahamCampbell\ResultType\Result;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index (Request $request) {
        if (!Auth::check()) {
            if ($request->path() != 'login') {
                return redirect('/login');
            }

            return view('welcome');
        }

        $user = Auth::user();
        if ($user->userType == 'User') {
            return redirect('/login');
        }

        if ($request->path() == 'login') {
            return redirect('/');
        }

        return view('welcome');
    }

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

    public function deleteCategory (Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        $delete = Category::where('id',$request->id)->delete();
        $this->deleteFileFromServer($request->iconImage);

        return $delete;
    }

    public function getUsers () {
        $data = User::where('role_id', '!=', 0)->get([
            'id', 'fullName', 'email', 'role_id', 'created_at'
        ]);
        return $data;
    }

    public function createUser (Request $request) {
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required'
        ]);

        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id
        ]);
        return $user;
    }

    public function editUser (Request $request) {
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,".$request->id,
            'password' => 'min:6',
            'role_id' => 'required'
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id
        ];

        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        $edit = User::where('id', $request->id)->update($data);

        return $edit;
    }

    public function deleteUser (Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $delete = User::where('id', $request->id)->delete();
        return $delete;
    }

    public function adminLogin (Request $request) {
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role->isAdmin == 0) {
                Auth::logout();

                return response()->json([
                    'msg' => 'Incorrect login details'
                ], 401);
            }
            
            return response()->json([
                'msg' => 'You were login in',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect login details'
            ], 401);
        }
    }

    public function logout () {
        Auth::logout();
        return redirect('/login');
    }

    public function getRoles () {
        $data = Role::orderBy('id', 'desc')->get();
        return $data;
    }

    public function createRole (Request $request) {
        $this->validate($request, [
            'roleName' => 'required'
        ]);

        $role = Role::create([
            'roleName' => $request->roleName
        ]);

        return $role;
    }

    public function editRole (Request $request) {
        $this->validate($request, [
            'roleName' => 'required'
        ]);

        $edit = Role::where('id', $request->id)->update([
            'roleName' => $request->roleName
        ]);

        return $edit;
    }

    public function deleteRole (Request $request) {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $delete = Role::where('id', $request->id)->delete();

        return $delete;
    }
}
