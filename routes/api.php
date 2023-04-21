<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommunityController;

use App\Http\Controllers\SchoolInfoController;
use App\Http\Controllers\SchoolController;

use App\Http\Controllers\TechController;

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
    Route::get('fetchEventPosts', [EventController::class, 'fetchEventPosts']);
    Route::get('fetchCommunityPosts', [CommunityController::class, 'fetchCommunityPosts']);

    // User Management
    Route::get('fetchUser/{id}', [UserController::class, 'fetchUser']);
    Route::get('fetchUserByEmail/{email}', [UserController::class, 'fetchUserByEmail']);
    Route::post('createUser', [UserController::class, 'createUser']);
    Route::post('updateUser', [UserController::class, 'updateUser']);
    Route::get('fetchAllSites', [SiteController::class, 'fetchAllSites']);
    Route::get('fetchSiteById/{id}', [SiteController::class, 'fetchSiteById']);
    Route::post('getUserMetadata',[UserController::class, 'getUserMetadata'] );

    // Product Management
    Route::get('fetchAllBrands', [ProductController::class, 'fetchAllBrands']);
    Route::get('fetchAllCategories', [ProductController::class, 'fetchAllCategories']);
    Route::get('fetchAllProducts', [ProductController::class, 'fetchAllProducts']);

    // School Info Management
    Route::get('fetchSchoolInfoById/{id}', [SchoolInfoController::class, 'fetchSchoolInfoById']);
    Route::get('fetchSchoolByFullName/{name}',[SchoolInfoController::class, 'fetchSchoolByFullName']);
    Route::get('getAllInfoOfOneSchool/{name}',[SchoolInfoController::class, 'getAllInfoOfOneSchoolByFullName']);
    Route::post('setSchoolInfoByName/{name}',[SchoolInfoController::class, 'setSchoolInfoByName']);

    Route::post('createSchool', [SchoolController::class, 'createSchool']);
    Route::post('updateSchool', [SchoolController::class, 'updateSchool']);
    Route::get('fetchAllSchools', [SchoolController::class, 'fetchAllSchools']);
    Route::get('fetchFeaturedSchools', [SchoolController::class, 'fetchFeaturedSchools']);

    Route::get('fetchAllTechs', [TechController::class, 'fetchAllTechs']);
});
