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

// Route::get('/admin/autologin',[AutomaticController::class, 'initialLogin']); //backup : Desparate Fix


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

Route::get('testConfig', function () {

    var_dump(config('services.okta'));
    echo '<br>';
    var_dump(config('services.okta.base_url'));
    echo '<br>';
    var_dump(config('services.okta.client_id'));
    echo '<br>';

});
Route::get('{any}', function () {
        return view('app');
    })->where('any', '.*');
