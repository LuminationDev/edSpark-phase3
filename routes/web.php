<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AutomaticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\LoginController;



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

// Route::get('/test', function (Request $request) {
//     // $authenticatedUserId = session('authenticated_user_id');
//     dd(Cookie::get('authenticated_user_id'));

//     // $authenticatedUserId = $request->cookie('authenticated_user_id');
//     // return response()->json(['authenticated_user_id' => $authenticatedUserId]);
// })->middleware('web');

// Route::get('/admin', function() {
//     dd(Auth::check());
// })->middleware('web');

// Route::get('/admin/autologin',[AutomaticController::class, 'initialLogin']); //backup : Desparate Fix


Route::get('/login', [LoginController::class, 'redirectToOkta'])->name('okta.login');
Route::get('/login/callback', [LoginController::class, 'handleOktaCallback'])->name('okta.callback');
Route::post('/logout', [LoginController::class, 'logout'])->name('okta.logout');
Route::get('okta-data', [LoginController::class, 'oktaData']);

// Route::get('/login/callback', [LoginController::class, 'handleOktaCallback'])->name('okta.callback');
// Route::get('/admin', [LoginController::class, 'bypassAuthentication'])->name('admin.bypass');

Route::get('{any}', function () {
        return view('app');
    })->where('any', '.*');
