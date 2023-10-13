<?php

use Illuminate\Support\Facades\Route;

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



Route::get('/login', [LoginController::class, 'redirectToOkta'])->name('okta.login');
Route::get('/login/callback', [LoginController::class, 'handleOktaCallback'])->name('okta.callback');
Route::post('/logout', [LoginController::class, 'logout'])->name('okta.logout');
Route::get('okta-data', [LoginController::class, 'oktaData']);
// CHECK OKTA AUTHENTICATION
Route::get('auth/check', [LoginController::class, 'checkAuthentication']);

/**
 * PARTNER LOGIN ROUTES
 */
Route::get('partner/login', 'App\Http\Controllers\PartnerAuthController@showLoginForm')->name('partner.login');
Route::post('partner/login', 'App\Http\Controllers\PartnerAuthController@login')->name('partner.login.submit');

Route::get('{any}', function () {
        return view('app');
    })->where('any', '.*');
