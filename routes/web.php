<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminCheck;
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

Route::get('/', [AdminController::class, 'index']);

Route::get('/logout', [AdminController::class, 'logout']);

Route::any('/{slug}', [AdminController::class, 'index']);

Route::group(['prefix' =>'app', 'middleware' => [AdminCheck::class]] ,function () {
    Route::get('/get_tags', [AdminController::class, 'getTags']);

    Route::post('/create_tag', [AdminController::class, 'addTag']);

    Route::post('/edit_tag', [AdminController::class, 'editTag']);

    Route::post('/delete_tag', [AdminController::class, 'deleteTag']);

    Route::post('/upload', [AdminController::class, 'upload']);

    Route::post('/delete_image', [AdminController::class, 'deleteImage']);

    Route::get('/get_categories', [AdminController::class, 'getCategories']);

    Route::post('/create_category', [AdminController::class, 'addCategory']);

    Route::post('/edit_category', [AdminController::class, 'editCategory']);

    Route::post('/delete_category', [AdminController::class, 'deleteCategory']);

    Route::get('/get_users', [AdminController::class, 'getUsers']);

    Route::post('/create_user', [AdminController::class, 'createUser']);

    Route::post('/edit_user', [AdminController::class, 'editUser']);

    Route::post('/delete_user', [AdminController::class, 'deleteUser']);

    Route::post('/admin_login', [AdminController::class, 'adminLogin']);

    Route::get('/get_roles', [AdminController::class, 'getRoles']);

    Route::post('/create_role', [AdminController::class, 'createRole']);

    Route::post('/edit_role', [AdminController::class, 'editRole']);
});
