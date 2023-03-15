<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('api')->group(function() {
    // Content Management
    Route::get('fetchAdvicePosts', [AdviceController::class, 'fetchAdvicePosts']);
    Route::get('fetchSoftwarePosts', [SoftwareController::class, 'fetchSoftwarePosts']);

    // User Management
    Route::get('fetchUser/{id}', [UserController::class, 'fetchUser']);
    Route::post('createUser', [UserController::class, 'createUser']);

    // Product Management
    Route::get('fetchAllBrands', [ProductController::class, 'fetchAllBrands']);
    Route::get('fetchAllCategories', [ProductController::class, 'fetchAllCategories']);
    Route::get('fetchAllProducts', [ProductController::class, 'fetchAllProducts']);
});
