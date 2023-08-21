
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TechController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LikeBookmarkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OktaAuthController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoginController;

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
    Route::get('fetchAdvicePostById/{id}', [AdviceController::class, 'fetchAdvicePostById']);
    Route::get('fetchAdvicePostByType/{type}', [AdviceController::class, 'fetchAdvicePostByType']);
    Route::get('fetchSoftwarePosts', [SoftwareController::class, 'fetchSoftwarePosts']);
    Route::post('fetchRelatedSoftware', [SoftwareController::class, 'fetchRelatedSoftware']);
    Route::get('fetchSoftwarePostById/{id}', [SoftwareController::class, 'fetchSoftwarePostById']);
    Route::get('fetchEventPosts', [EventController::class, 'fetchEventPosts']);
    Route::get('fetchEventPostById/{id}', [EventController::class, 'fetchEventPostById']);
    Route::get('fetchCommunityPosts', [CommunityController::class, 'fetchCommunityPosts']);

    // User Management
    Route::get('fetchUser/{id}', [UserController::class, 'fetchUser']);
    Route::get('fetchUserByEmail/{email}', [UserController::class, 'fetchUserByEmail']);
    Route::post('createUser', [UserController::class, 'createUser']);
    Route::post('updateFirstTimeVisitUser', [UserController::class, 'updateFirstTimeVisitUser']);
    Route::post('updateUser', [UserController::class, 'updateUser']);
    Route::post('checkEmail', [UserController::class, 'checkEmail']);
    Route::get('fetchAllSites', [SiteController::class, 'fetchAllSites']);
    Route::get('fetchSiteById/{id}', [SiteController::class, 'fetchSiteById']);
    Route::get('fetchSiteByCode/{siteCode}', [SiteController::class, 'fetchSiteByCode']);
    Route::post('getUserMetadata',[UserController::class, 'getUserMetadata'] );

    Route::get('fetchRoleByCode/{roleCode}', [RoleController::class, 'fetchRoleByCode']);
    Route::get('fetchAllRoles', [RoleController::class, 'fetchAllRoles']);

    // Product Management
    Route::get('fetchAllBrands', [ProductController::class, 'fetchAllBrands']);
    Route::get('fetchAllCategories', [ProductController::class, 'fetchAllCategories']);
    Route::get('fetchAllProducts', [ProductController::class, 'fetchAllProducts']);
    Route::get('fetchProductById/{id}', [ProductController::class, 'fetchProductById']);
    Route::get('fetchProductByBrand/{brand}', [ProductController::class, 'fetchProductByBrand']);

    // School APIs
    Route::get('fetchAllSchools', [SchoolController::class, 'fetchAllSchools']);
    Route::get('fetchFeaturedSchools', [SchoolController::class, 'fetchFeaturedSchools']);
    Route::get('fetchSchoolByName/{schoolName}', [SchoolController::class, 'fetchSchoolByName']);
    Route::get('fetchStaffFromSite/{site_id}',[SchoolController::class, 'fetchAllStaffFromSite']);
    Route::post('createSchool', [SchoolController::class, 'createSchool']);
    Route::post('updateSchool', [SchoolController::class, 'updateSchool']);
    Route::post('checkUserCanEdit',[SchoolController::class, 'checkUserCanEdit']);
    Route::post('nominateUserForSchool',[SchoolController::class, 'nominateUserForSchool']);
    Route::post('deleteNominatedUser',[SchoolController::class, 'deleteNominatedUserSchool']);
    Route::post('getNominatedUsersFromSchool',[SchoolController::class, 'getNominatedUsersFromSchool']);
    Route::post('createOrUpdateSchoolContact', [SchoolController::class , 'createOrUpdateContact']);
    Route::post('fetchSchoolContact', [SchoolController::class, 'fetchSchoolContact']);

    Route::get('fetchAllTechs', [TechController::class, 'fetchAllTechs']);

    // Image upload
    Route::post('imageUpload', [ImageController::class, 'imageUpload']);
    Route::post('imageUploadEditorjs', [ImageController::class, 'imageUploadEditorjs']);

    // Like and Bookmark
    Route::post('like', [LikeBookmarkController::class, 'like']);
    Route::post('bookmark', [LikeBookmarkController::class, 'bookmark']);
    Route::post('fetchAllLikes', [LikeBookmarkController::class, 'fetchAllLikes']);
    Route::post('fetchAllBookmarks', [LikeBookmarkController::class, 'fetchAllBookmarks']);
    Route::post('fetchAllBookmarksWithTitle', [LikeBookmarkController::class, 'fetchAllBookmarksWithTitle']);
    Route::post('fetchAllLikesByType', [LikeBookmarkController::class, 'fetchAllLikesByType']);
    Route::post('fetchAllBookmarksByType', [LikeBookmarkController::class, 'fetchAllBookmarksByType']);

    // Notifications
    Route::get('fetchAllNotifications/{userId}', [NotificationController::class, 'getAllNotifications']);
    Route::get('fetchSingleNotification', [NotificationController::class, 'getSingleNotification']);
    Route::get('fetchNotificationByType', [NotificationController::class, 'getNotificationByType']);

    Route::post('authenticate', [OktaAuthController::class, 'authenticate']);

    // Partners API
    Route::get('fetchAllPartners', [PartnerController::class, 'fetchAllPartners']);
    Route::get('fetchPartnerById/{id}', [PartnerController::class, 'fetchPartnerById']);

    // Event Rsvp
    Route::get('fetchRsvpByEventId/{event_id}',[RsvpController::class,'fetchRsvpByEventId']);
    Route::post('addRsvpToEvent',[RsvpController::class,'addRsvpToEvent']);
    Route::post('removeRsvpFromEvent',[RsvpController::class,'removeRsvpFromEvent']);
    Route::post('checkIfUserRsvped',[RsvpController::class,'checkIfUserRsvped']);
    Route::post('addEventRecording',[EventController::class,'addEventRecording']);
    Route::get('checkEventRecording/{event_id}',[EventController::class,'checkEventRecording']);

});