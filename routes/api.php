<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\SoftwareController;

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
    Route::resource('category', CategoryController::class);
    Route::get('fetchAdvicePosts', [AdviceController::class, 'fetchAdvicePosts']);
    Route::get('fetchSoftwarePosts', [SoftwareController::class, 'fetchSoftwarePosts']);
});
