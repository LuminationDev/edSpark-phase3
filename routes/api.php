
<?php

use App\Http\Controllers\AutoSaveController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LikeBookmarkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\RsvpController;

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
Route::middleware('auth:sanctum')->group(function() {
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
    Route::post('fetchPendingSchoolByName/{schoolName}', [SchoolController::class, 'fetchPendingSchoolByName']);

    // Return School based on the User's site (source Okta)
    // return existing school profile or create one with default template if not exists yet
    Route::post('fetchUserSchool', [SchoolController::class, 'fetchUserSchool']);

    // Advice APIs
    Route::post('createAdvicePost',[AdviceController::class, 'createAdvicePost']);
    Route::get('fetchAdvicePosts', [AdviceController::class, 'fetchAdvicePosts']);
    Route::get('fetchAdvicePostById/{id}', [AdviceController::class, 'fetchAdvicePostById']);
    Route::get('fetchAdvicePostByType/{type}', [AdviceController::class, 'fetchAdvicePostByType']);
    Route::post('fetchRelatedAdvice',[AdviceController::class, 'fetchRelatedAdvice']);
    Route::get('fetchAdviceTypes', [AdviceController::class,'fetchAdviceTypes']);
    Route::get('fetchUserAdvice', [AdviceController::class, 'fetchUserAdvicePosts']);

    // Software APIs
    Route::post('createSoftwarePost', [SoftwareController::class,'createSoftwarePost']);
    Route::get('fetchSoftwarePosts', [SoftwareController::class, 'fetchSoftwarePosts']);
    Route::get('fetchSoftwarePostById/{id}', [SoftwareController::class, 'fetchSoftwarePostById']);
    Route::post('fetchRelatedSoftware', [SoftwareController::class, 'fetchRelatedSoftware']);
    Route::get('fetchSoftwareTypes', [SoftwareController::class,'fetchSoftwareTypes']);
    Route::get('fetchUserSoftware',[SoftwareController::class, 'fetchUserSoftwarePosts']);

    // Event APIs
    Route::post('createEventPost',[EventController::class,'createEventPost']);
    Route::get('fetchEventPosts', [EventController::class, 'fetchEventPosts']);
    Route::get('fetchEventPostById/{id}', [EventController::class, 'fetchEventPostById']);
    Route::get('fetchEventTypes',[EventController::class, 'fetchEventTypes']);

    // Community APIs
    Route::get('fetchCommunityPosts', [CommunityController::class, 'fetchCommunityPosts']);

    // Product/Hardware Management
    Route::get('fetchAllBrands', [ProductController::class, 'fetchAllBrands']);
    Route::get('fetchAllCategories', [ProductController::class, 'fetchAllCategories']);
    Route::get('fetchAllProducts', [ProductController::class, 'fetchAllProducts']);
    Route::get('fetchProductById/{id}', [ProductController::class, 'fetchProductById']);
    Route::post('fetchProductByBrand', [ProductController::class, 'fetchProductByBrand']);
    Route::get('fetchUserProduct', [ProductController::class, 'fetchUserProductPosts']);

    // User Management
    Route::get('fetchUser', [UserController::class, 'fetchUser']);
    Route::get('fetchUserByEmail/{email}', [UserController::class, 'fetchUserByEmail']);
    Route::get('fetchAllSites', [SiteController::class, 'fetchAllSites']);
    Route::get('fetchSiteById/{id}', [SiteController::class, 'fetchSiteById']);
    Route::get('fetchSiteByCode/{siteCode}', [SiteController::class, 'fetchSiteByCode']);
    Route::get('fetchAllRoles', [RoleController::class, 'fetchAllRoles']);
    Route::get('fetchRoleByCode/{roleCode}', [RoleController::class, 'fetchRoleByCode']);
    Route::post('getUserMetadata',[UserController::class, 'getUserMetadata'] );
    Route::post('createUser', [UserController::class, 'createUser']);
    Route::post('updateFirstTimeVisitUser', [UserController::class, 'updateFirstTimeVisitUser']);
    Route::post('updateUser', [UserController::class, 'updateUser']);
    Route::post('checkEmail', [UserController::class, 'checkEmail']);

    // Image upload


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

    // Partners API
    Route::get('fetchAllPartners', [PartnerController::class, 'fetchAllPartners']);
    Route::get('fetchPartnerById/{id}', [PartnerController::class, 'fetchPartnerById']);
    Route::post('updatePartnerContent', [PartnerController::class, 'updatePartnerContent']);
    Route::post('checkIfUserCanEditPartner',[PartnerController::class, 'checkIfUserCanEditPartner']);
    Route::post('fetchPartnerPendingProfile', [PartnerController::class, 'fetchPendingPartnerProfile']);

    // Event Rsvp
    Route::get('fetchRsvpByEventId/{event_id}',[RsvpController::class,'fetchRsvpByEventId']);
    Route::post('addRsvpToEvent',[RsvpController::class,'addRsvpToEvent']);
    Route::post('removeRsvpFromEvent',[RsvpController::class,'removeRsvpFromEvent']);
    Route::post('checkIfUserRsvped',[RsvpController::class,'checkIfUserRsvped']);
    Route::post('addEventRecording',[EventController::class,'addEventRecording']);
    Route::get('checkEventRecording/{event_id}',[EventController::class,'checkEventRecording']);

    //Search
    Route::get(
        'search',
        App\Http\Controllers\SearchController::class
    )->name('search');

    //Auto-save
    Route::match(['get', 'post'], '/auto-save', [AutoSaveController::class, 'handleAutoSave']);
});

