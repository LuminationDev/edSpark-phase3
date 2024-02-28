<?php

namespace App\Http\Controllers;

use App\Helpers\JsonHelper;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\User;
use App\Models\Usermeta;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Schoolmeta;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\Metahelper;
use stdClass;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class SchoolController extends Controller
{
    private string $defaultSchoolContent = "<h1>School snapshot</h1>\n<p>Write a brief paragraph that captures the essence of the school, highlighting its character as a dynamic and inclusive educational environment committed to fostering excellence in both students and educators.</p>\n<h1>Technology across the school</h1>\n<p>Provide a concise overview of how technology is integrated into various aspects of the school, emphasizing its role in enhancing the learning experience, facilitating communication, and preparing students for the digital challenges of the modern era.</p>\n<h1>Digital technology</h1>\n<p>Offer a short paragraph focusing specifically on the school's approach to digital technology. Describe how the institution embraces innovation, incorporating tools like smart classrooms and online platforms to promote digital literacy and empower students as both users and creators of technology.</p>\n<h1>Student learning</h1>\n<p>Summarize the school's approach to student learning, emphasizing a holistic strategy that accommodates diverse learning styles. Mention key elements such as personalized learning plans, project-based assignments, and real-world applications, showcasing how the school equips students with essential skills for success in a rapidly changing global landscape.</p>";

    private function checkUserCanAccess($site_id)
    {
        $current_user_id = Auth::user()->id;
        $current_user = User::find($current_user_id);

        if ($current_user
            && $current_user->site_id === $site_id && $current_user->role->role_name === 'SCHLDR') {
            return true;
        } elseif (RoleHelpers::has_minimum_privilege(UserRole::ADMIN)) {
            return true;
        } else return false;
    }

    private function formatSchoolMetadata($schoolMetadata)
    {
        $tempMetadata = [];
        if ($schoolMetadata) {
            foreach ($schoolMetadata as $key => $value) {
                $res = [
                    'schoolmeta_key' => $value->schoolmeta_key,
                    'schoolmeta_value' => $value->schoolmeta_value
                ];
                $tempMetadata[] = $res;
            }
            return $tempMetadata;
        }
        return [];
    }

    private function schoolModelToJson($school, $schoolMetadata = NULL, $request = NULL)
    {
        // LIKE AND BOOKMARK
        $userId = Auth::user()->id;
        $isLikedByUser = $school->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $school->bookmarks()->where('user_id', $userId)->exists();

        // LOCATION AND META
        $site = Site::where('site_id', $school->site_id)->first();
        $siteLocation = (object)[
            'lat' => $site ? (float)$site->site_latitude : 0.0,
            'lng' => $site ? (float)$site->site_longitude : 0.0
        ];

        // adding schoolType into meta
        $schoolType = [
            'schoolmeta_key' => 'school_type',
            'schoolmeta_value' => $school->site->site_sub_type_desc ?? ""
        ];
        $schoolMetadata[] = $schoolType;

        return [
            'school_id' => $school->school_id,
            'site' => [
                'site_id' => $school->site->site_id,
                'site_name' => ($school->site->site_id) ? $school->site->site_name : NULL,
                'site_type_code' => ($school->site) ? $school->site->site_type_code : NULL
            ],
            'owner' => [
                'owner_id' => $school->owner_id,
                'owner_name' => ($school->owner_id) ? $school->owner->full_name : NULL
            ],
            'name' => $school->name,
            'content_blocks' => JsonHelper::safelyDecodeString($school->content_blocks) ?: NULL,
            'logo' => ($school->logo) ? $school->logo : NULL,
            'cover_image' => ($school->cover_image) ? $school->cover_image : NULL,
            'tech_used' => ($school->tech_used) ? json_decode($school->tech_used) : NULL,
            'status' => $school->status,
            'pedagogical_approaches' => ($school->pedagogical_approaches) ? json_decode($school->pedagogical_approaches) : NULL,
            'tech_landscape' => ($school->tech_landscape) ? json_decode($school->tech_landscape) : NULL,
            'metadata' => ($schoolMetadata) ?: NULL,
            'location' => $siteLocation,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'isFeatured' => (bool)$school->isFeatured,
            'updated_at' => $school->updated_at ?: "",

        ];
    }

    private function handleImageUpload($image, $prefix, $folder)
    {
        if (isset($image) && is_string($image) === false) {
            $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $image) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/uploads/school/' . $folder, $imgName);
            return "uploads/school/$folder/" . $imgName;
        }
        return null;
    }

    private function handleMetadata($schoolId, $metadata)
    {
        foreach ($metadata as $key => $value) {
            $valueToInsert = is_string($value) ? $value : implode(', ', $value);

            Schoolmeta::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'schoolmeta_key' => $key
                ],
                [
                    'schoolmeta_value' => $valueToInsert,
                    'updated_at' => Carbon::now()
                ]
            );
        }
    }

    private function safelyEncode($data)
    {
        // Check if data is already a JSON string
        if (is_string($data) && json_decode($data) && json_last_error() == JSON_ERROR_NONE) {
            return $data;  // It's already a JSON string, so return it as is
        }
        return json_encode($data);
    }

    private function archivePreviousSchoolEntry($schoolId)
    {
        School::where('school_id', $schoolId)
            ->where('status', '!=', 'Archived')
            ->update(['status' => 'Archived']);
    }

    private function replacePreviousPendingSchoolEntry($schoolId)
    {
        School::where('school_id', $schoolId)
            ->where('status', 'Pending')
            ->update(['status' => 'Archived']);
    }

    private function insertNewSchoolVersion($data, $schoolLogoUrl, $coverImageUrl)
    {
        return School::create([
            'school_id' => $data['school_id'],
            'site_id' => $data['site_id'],
            'owner_id' => $data['owner_id'],
            'name' => $data['name'],
            'content_blocks' => $this->safelyEncode($data['content_blocks']),
            'logo' => $schoolLogoUrl ?? $data['logo'],
            'cover_image' => $coverImageUrl ?? $data['cover_image'],
            'tech_used' => $this->safelyEncode($data['tech_used']),
            'pedagogical_approaches' => $this->safelyEncode($data['pedagogical_approaches']),
            'tech_landscape' => $this->safelyEncode($data['tech_landscape']),
            'status' => 'Pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /*  fetchUser's school based on their site id
        if user is principal, WILL CREATE SCHOOL WITH DEFAULT CONTENT
    */

    public function fetchUserSchool(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'site_id' => 'required|integer'
        ]);

        $userId = $request->input('user_id');
        $siteId = $request->input('site_id');

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Invalid user', 'status' => 403], 403);
        }

        $site = Site::where('site_id', $siteId)->first();


        if (!$site) {
            return response()->json(['message' => 'Site not found', 'status' => 404], 404);
        }

//        // If user is not 'SCHLDR' or 'Superadmin', just fetch the school
//        if ($user->role->role_name !== 'SCHLDR' && $user->role->role_name !== 'Superadmin') {
//            $school = School::where('site_id', $siteId)->where('status', 'Published')->first();
//
//            if (!$school) {
//                return response()->json(['message' => 'School not found based on the provided site id', 'status' => 404], 404);
//            }
//            $schoolMetadata = Schoolmeta::where('school_id', $school->school_id)->get();
//            $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
//            $result = $this->schoolModelToJson($school, $schoolMetadataToSend, $request);
//
//            return response()->json($result);
//        }

        // For 'SCHLDR' or 'Superadmin', use the firstOrCreate logic
        try {
            $latestSchool = School::orderBy('school_id', 'desc')->first();
            $nextSchoolId = ($latestSchool ? $latestSchool->school_id + 1 : 1);
            $school = School::firstOrCreate(
                ['site_id' => $siteId, 'status' => "Published"],
                [
                    'owner_id' => $userId,
                    'school_id' => $nextSchoolId,
                    'name' => $site->site_name,
                    'content_blocks' => $this->defaultSchoolContent,
                    'logo' => '',
                    'cover_image' => '',
                    'tech_used' => '',
                    'pedagogical_approaches' => '',
                    'tech_landscape' => '',
                    'status' => 'Published',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            );
//        $schoolMetadata = Schoolmeta::where('school_id', $school->school_id)->get();
//        $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
//        $result = $this->schoolModelToJson($school,$schoolMetadataToSend, $request);
            $result = $this->schoolModelToJson($school);
            return response()->json($result);
        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);
        }
    }


    public function createSchool(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $error = '';

            if ($data) {
                // save school info into school table
                try {
                    $prefix = "edspark-school";
                    $schoolLogoUrl = $this->handleImageUpload($data['logo'] ?? null, $prefix, 'logo');
                    $coverImageUrl = $this->handleImageUpload($data['cover_image'] ?? null, $prefix, '');
                    $dataToInsert = [
                        'site_id' => $data['site_id'],
                        'owner_id' => $data['owner_id'],
                        'name' => $data['name'],
                        'content_blocks' => $this->safelyEncode($data['content_blocks']),
                        'logo' => $schoolLogoUrl ?? ($data['logo'] ?: NULL),
                        'cover_image' => $coverImageUrl ?? ($data['cover_image'] ?: NULL),
                        'tech_used' => $this->safelyEncode($data['tech_used']),
                        'pedagogical_approaches' => $this->safelyEncode($data['pedagogical_approaches']),
                        'tech_landscape' => $this->safelyEncode($data['tech_landscape']),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $schoolId = School::insertGetId($dataToInsert);
                    School::where('id', $schoolId)->update(['school_id' => $schoolId]);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
            if (isset($request->schoolMetadata)) {
                $this->handleMetadata($schoolId, $request->schoolMetadata);
            }

            return response()->json([
                'message' => 'School added successfully',
                'error' => $error,
                'status' => 200
            ]);
        }
    }

    public function updateSchool(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $error = '';

            if (!$this->checkUserCanAccess($data['school_id'])) {
                return ResponseService::error_unauthorized();
            }

            try {
                $prefix = "edspark-school";
                $schoolLogoUrl = $this->handleImageUpload($data['logo'] ?? null, $prefix, 'logo');
                $coverImageUrl = $this->handleImageUpload($data['cover_image'] ?? null, $prefix, '');

                // Archive previous entries and insert new version
//                $this->archivePreviousSchoolEntry($data['school_id']);
                $this->replacePreviousPendingSchoolEntry($data['school_id']);
                $newSchool = $this->insertNewSchoolVersion($data, $schoolLogoUrl, $coverImageUrl);

                $metadata = json_decode($data['metadata']);
                $this->handleMetadata($newSchool->school_id, $metadata);  // Use the new school's ID for metadata

                // get metadata
                $schoolMetadata = Schoolmeta::where('school_id', $newSchool->school_id)->get();
                $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
                $returningResult = $this->schoolModelToJson($newSchool, $schoolMetadataToSend);

            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            return response()->json([
                'message' => "New school version added successfully",
                'error' => $error,
                'status' => 200,
                'data' => $returningResult ?? null
            ]);
        }
    }


    public function fetchAllSchools(Request $request)
    {
        $schools = School::where('status', 'Published')->get();
        $data = [];
        foreach ($schools as $school) {
            $schoolMetadata = Schoolmeta::where('school_id', $school->school_id)->get();
            $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
            $result = $this->schoolModelToJson($school, $schoolMetadataToSend, $request);
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchSchoolByName(Request $request, $schoolName)
    {
        $schoolName = str_replace('%20', ' ', $schoolName);
        $school = School::where('name', $schoolName)->where('status', 'Published')->first();
        if ($school == null) {
            return response('School Not found', 404);
        } else {
            $schoolMetadata = Schoolmeta::where('school_id', $school->school_id)->get();
            $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
            $result = $this->schoolModelToJson($school, $schoolMetadataToSend, $request);
            return response()->json($result, 200);

        }

    }

    public function fetchPendingSchoolByName(Request $request, $schoolName)
    {
        $currentUser = Auth::user();
        $school = School::where('name', $schoolName)->first();
        if (!RoleHelpers::has_minimum_privilege(UserRole::MODERATOR) && $currentUser->site_id !== $school->site_id) {
            // Return a forbidden response or any other response to indicate lack of permission
            return response()->json([
                "status" => 403,
                "message" => "Forbidden. You do not have permission to access this resource."
            ], 403);
        }

        $schoolName = str_replace('%20', ' ', $schoolName);
        $school = School::where('name', $schoolName)->where('status', 'Pending')->first();

        if ($school == null) {
            return response()->json([
                "status" => 200,
                "pending_available" => false,
                "result" => null
            ]);
        } else {
            $schoolMetadata = Schoolmeta::where('school_id', $school->school_id)->get();
            $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
            $result = $this->schoolModelToJson($school, $schoolMetadataToSend, $request);

            return response()->json([
                "status" => 200,
                "pending_available" => true,
                "result" => $result
            ]);
        }
    }


    public function fetchFeaturedSchools(Request $request)
    {
//        $schools = School::where('isFeatured', 1)->inRandomOrder()->->inRandomOrder()->limit(4)->get();
        $schools = School::where('isFeatured', 1)->inRandomOrder()->limit(4)->get();
        $data = [];

        foreach ($schools as $school) {
            $schoolMetadata = Schoolmeta::where('school_id', $school->school_id)->get();
            $schoolMetadataToSend = $this->formatSchoolMetadata($schoolMetadata);
            $result = $this->schoolModelToJson($school, $schoolMetadataToSend, $request);
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchAllStaffFromSite($site_id): \Illuminate\Http\JsonResponse
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return ResponseService::error('User not authenticated.', null, 401);
        }

        // Check if the authenticated user's site_id matches the given site_id
        if (Auth::user()->site_id !== intval($site_id)) {
            return ResponseService::error('Access denied for this site.', null, 403);
        }

        $all_staff = User::where('site_id', intval($site_id))->get();

        if ($all_staff->isEmpty()) {
            return ResponseService::error('No staff found for this site.', null, 404);
        }

        $final_result = [];
        foreach ($all_staff as $staff) {
            $avatarUrl = Usermeta::where('user_id', $staff->id)
                ->where('user_meta_key', 'userAvatar')
                ->first();
            $result = [
                'id' => $staff->id,
                'name' => $staff->full_name,
                'display_name' => $staff->display_name ?? NULL,
                'role' => ($staff->role) ? $staff->role->role_name : NULL,
                'userAvatar' => $avatarUrl ? stripslashes($avatarUrl->user_meta_value) : NULL
            ];
            $final_result[] = $result;
        }

        return ResponseService::success('Staff fetched successfully.', $final_result);
    }


    public function nominateUserForSchool(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$request->isMethod('post')) {
            return ResponseService::error('Failed to nominate user. Invalid request method.');
        }

        $requestData = $request->validate([
            'school_id' => 'required',
            'site_id' => 'required',
            'nominated_user_id' => 'required',
        ]);

        $school_id = $requestData['school_id'];
        $site_id = $requestData['site_id'];
        $nominated_user_id = $requestData['nominated_user_id'];

        $user_record = Auth::user();

        if (!$user_record || $user_record->site_id !== $site_id || ($user_record->role->role_name !== 'SCHLDR' && $user_record->role->role_name !== 'Superadmin')) {
            return ResponseService::error('Failed to nominate user. User is not authorized.');
        }

        $meta_to_insert = [
            "school_id" => $school_id,
            "schoolmeta_key" => 'nominated_user',
            'schoolmeta_value' => $nominated_user_id,
        ];
        Schoolmeta::create($meta_to_insert);

        return ResponseService::success('User has been nominated successfully.');
    }


    public function deleteNominatedUserSchool(Request $request)
    {
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'site_id' => 'required',
                'nominated_id_delete' => 'required',
            ]);

            $school_id = $requestData['school_id'];
            $site_id = $requestData['site_id'];
            $nominated_id_delete = $requestData['nominated_id_delete'];

            $user_record = Auth::user();

            if ($user_record && $user_record->site_id == $site_id && ($user_record->role->role_name === 'SCHLDR' || $user_record->role->role_name === 'Superadmin')) {

                $deleted = Schoolmeta::where('school_id', $school_id)
                    ->where('schoolmeta_key', 'nominated_user')
                    ->where('schoolmeta_value', $nominated_id_delete)
                    ->delete();

                if ($deleted) {
                    return response()->json([
                        "status" => 200,
                        "result" => 'User nomination has been deleted successfully.'
                    ]);
                } else {
                    return response()->json([
                        "status" => 400,
                        "result" => 'Failed to delete user nomination. Key not found.'
                    ]);
                }
            } else {
                return response()->json([
                    "status" => 401,
                    "result" => 'Failed to delete user nomination. User is not authorized.'
                ]);
            }
        } else {
            return response()->json([
                "status" => 401,
                "result" => 'Failed to delete user nomination. Invalid request method.'
            ]);
        }
    }

    public function getNominatedUsersFromSchool(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$request->isMethod('post')) {
            return ResponseService::error('Invalid request method.');
        }

        $requestData = $request->validate([
            'school_id' => 'required',
            'user_id' => 'required',
            'site_id' => 'required'
        ]);

        $school_id = $requestData['school_id'];
        $site_id = $requestData['site_id'];
        $user_id = $requestData['user_id'];

        $user_record = User::find($user_id);

        if (!$user_record || $user_record->site_id !== $site_id || $user_record->role->role_name !== 'SCHLDR' && $user_record->role->role_name !== 'Superadmin') {
            return ResponseService::error('Failed to retrieve nominated users. User is not authorized as the school principal.');
        }

        $nominated_users_ids = Schoolmeta::where('school_id', $school_id)
            ->where('schoolmeta_key', 'nominated_user')
            ->pluck('schoolmeta_value');

        $nominated_users = User::whereIn('id', $nominated_users_ids)->get();

        $final_result = [];
        foreach ($nominated_users as $user) {
            $avatarUrl = Usermeta::where('user_id', $user->id)
                ->where('user_meta_key', 'userAvatar')
                ->first();

            $result = [
                'id' => $user->id,
                'name' => $user->full_name,
                'display_name' => $user->display_name ?? NULL,
                'role' => ($user->role) ? $user->role->role_name : NULL,
                'userAvatar' => $avatarUrl ? stripslashes($avatarUrl->user_meta_value) : NULL
            ];
            $final_result[] = $result;
        }

        return ResponseService::success('Nominated users retrieved successfully.', $final_result);
    }


    public function checkUserCanEdit(Request $request): \Illuminate\Http\JsonResponse
    {
        // user id
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'site_id' => 'required'
            ]);

            $site_id = $requestData['site_id'];
            $user_id = Auth::user()->id;
            $school_id = $requestData['school_id']; // only for sake of schools meta
            //check user site id == school Id && user role === school principal
            $user_record = User::find($user_id);
            $user_message = '';
            $user_can_edit = false;
            $user_can_nominate = false;
            $user_can_publish = false;


            $schoolmeta_record = Schoolmeta::where('school_id', $school_id)
                ->where('schoolmeta_key', 'nominated_user')
                ->where('schoolmeta_value', $user_id)
                ->first();

            if ($schoolmeta_record) {
                $user_can_edit = true;
                $user_can_nominate = false;
                $user_can_publish = false;
                $user_message = 'You are editing as a Nominated user';
            }

            if ($user_record && $user_record->site_id == $site_id && ($user_record->role->role_name === 'SCHLDR')) {
                $user_can_edit = true;
                $user_can_nominate = true;
                $user_can_publish = false;
                $user_message = 'You are editing as a School leader';
            }

            if ($user_record->role->role_name === 'Superadmin') {
                $user_can_edit = true;
                $user_can_publish = true;
                $user_message = 'You are editing as a Superadmin';
            }
            if ($user_can_edit) {
                return response()->json([
                    "status" => 200,
                    "result" => $user_can_edit,
                    'canNominate' => $user_can_nominate,
                    'canPublish' => $user_can_publish,
                    'message' => $user_message
                ]);
            } else {
                return response()->json([
                    "status" => 401,
                    "result" => False
                ]);

            }
        }
        return response()->json([
            "status" => 401,
            "result" => False
        ]);

    }

    /************************************
     * School Contact Functions         *
     ************************************/
    public function createOrUpdateContact(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!$request->isMethod('post')) {
            return response()->json([
                "status" => 401,
                "result" => false,
                'message' => 'Unauthorized'
            ]);
        }

        $requestData = $request->validate([
            'school_id' => 'required',
            'school_contact' => 'required'
        ]);

        $school_id = $requestData['school_id'];
        $school_contact = $requestData['school_contact'];

        Schoolmeta::updateOrCreate(
            [
                'school_id' => $school_id,
                'schoolmeta_key' => 'school_contact'
            ],
            [
                'schoolmeta_value' => $school_contact
            ]
        );

        return response()->json([
            "status" => 200,
            "result" => true,
            'message' => 'School contact saved successfully.'
        ]);
    }


    public function fetchSchoolContact(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
            ]);

            $school_id = $requestData['school_id'];

            $schoolmeta_record = Schoolmeta::where('school_id', $school_id)
                ->where('schoolmeta_key', 'school_contact')
                ->first();

            if ($schoolmeta_record) {
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    'school_contact' => json_decode($schoolmeta_record->schoolmeta_value)
                ]);
            }

            return response()->json([
                "status" => 404,
                "result" => false,
                'message' => 'School contact not found.'
            ]);
        }

        return response()->json([
            "status" => 401,
            "result" => false,
            'message' => 'Unauthorized'
        ]);
    }
    /**
     * End of SchoolContact Function
     */
}
