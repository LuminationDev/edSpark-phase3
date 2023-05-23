<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');


//Route::get('/admin', function () {
//    return 'Hello World';
//});

//Route::get('/admin', function () {
//    // Authenticate the user automatically without requiring Laravel Filament authentication
//    if (Auth::loginUsingId(7, true)) {
//        return redirect()->route('dashboard');
//    }
////    return view('admin')->name('login');
////    return view('dashboard');
//})->middleware('web');

//Route::get('/admin', 'AdminController@index')->middleware('web');

//Route::group(['middleware' => 'okta.auth'], function () {
//    Route::get('/admin', function () {
//        // Your Filament dashboard route logic here
//        // This route will only be accessible if the user is authenticated through Okta
//        return 'Authenticated';
//    })->middleware('web');
//});
