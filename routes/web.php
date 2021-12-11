<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/{slug}', function () {
    return view('welcome');
});

Route::get('app/get_tags', [AdminController::class, 'getTags']);

Route::post('app/create_tag', [AdminController::class, 'addTag']);

Route::post('app/edit_tag', [AdminController::class, 'editTag']);

Route::post('app/delete_tag', [AdminController::class, 'deleteTag']);

Route::post('app/upload', [AdminController::class, 'upload']);

Route::post('app/delete_image', [AdminController::class, 'deleteImage']);