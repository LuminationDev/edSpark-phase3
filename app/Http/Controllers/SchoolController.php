<?php

namespace App\Http\Controllers;

use App\Helpers\OutputHelper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Schoolmeta;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\Metahelper;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class SchoolController extends Controller
{
    public function createSchool(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all(); // if not data key present
            // $data = $request->data; // if data key present can be used later for metaData
//             dd(json_decode($data));
            $error = '';

            if ($data) {
                // save school info into school table
                try {
                    $prefix = "edspark-school";
                    if (isset($data['logo']) && is_string($data['logo']) === false) {
                        $schoolLogo = $data['logo'];
                        $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $schoolLogo) . '.' . $schoolLogo->getClientOriginalExtension();
                        $schoolLogo->storeAs('public/uploads/school/logo', $imgName);
                        $schoolLogoUrl = "uploads\/school\/logo\/" . $imgName;
                    }
                    if (isset($data['cover_image']) && is_string($data['cover_image']) === false) {
                        $coverImage = $data['cover_image'];
                        $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $coverImage) . '.' . $coverImage->getClientOriginalExtension();
                        $coverImage->storeAs('public/uploads/school', $imgName);
                        $coverImageUrl = "uploads\/school\/" . $imgName;
                    }
                    $dataToInsert = [
                        'site_id' => $data['site_id'],
                        'owner_id' => $data['owner_id'],
                        'name' => $data['name'],
                        'content_blocks' => json_encode($data['content_blocks']),
                        'logo' => $schoolLogoUrl ?? ($data['logo'] ?: NULL),
                        'cover_image' => $coverImageUrl ?? ($data['cover_image'] ?: NULL),
                        'tech_used' => json_encode($data['tech_used']),
                        'pedagogical_approaches' => json_encode($data['pedagogical_approaches']),
                        'tech_landscape' => json_encode($data['tech_landscape']),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $schoolId = School::insertGetId($dataToInsert);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
            $schoolMeta = $request->schoolMetadata;
            if ($schoolMeta) {
                try {
                    foreach ($schoolMeta as $key => $value) {
                        $res =
                            [
                                'school_id' => $schoolId,
                                'schoolmeta_key' => $key,
                                'schoolmeta_value' => (is_string($value)) ? $value : implode(', ', $value),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        $metadataToInsert[] = $res;
                    }
                    Schoolmeta::insert($metadataToInsert);

                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
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

            if ($data) {
                try {
                    $prefix = "edspark-school";
                    if (isset($data['logo']) && is_string($data['logo']) === false) {
                        $schoolLogo = $data['logo'];
                        $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $schoolLogo) . '.' . $schoolLogo->getClientOriginalExtension();
                        $schoolLogo->storeAs('public/uploads/school/logo', $imgName);
                        $schoolLogoUrl = "uploads\/school\/logo\/" . $imgName;

                    }

                    if (isset($data['cover_image']) && is_string($data['cover_image']) === false) {
                        $coverImage = $data['cover_image'];
                        $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $coverImage) . '.' . $coverImage->getClientOriginalExtension();
                        $coverImage->storeAs('public/uploads/school', $imgName);
                        $coverImageUrl = "uploads\/school\/" . $imgName;
                    }

                    $dataToUpdate = [
                        'name' => $data['name'],
                        'content_blocks' => json_encode($data['content_blocks']),
                        // if logo or coverImage is not string, schoolLogoURL and coverImageUrl will be set
                        // and will be used as new value
                        // if not set, old value will be used instead
                        'logo' => isset($schoolLogoUrl) ? $schoolLogoUrl : $data['logo'],
                        'cover_image' => isset($coverImageUrl) ? $coverImageUrl : $data['cover_image'],
                        'tech_used' => json_encode($data['tech_used']),
                        'pedagogical_approaches' => json_encode($data['pedagogical_approaches']),
                        'tech_landscape' => json_encode($data['tech_landscape']),
                        'updated_at' => Carbon::now()

                    ];
                    School::where('id', '=', $data['id'])
                        ->update($dataToUpdate);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
            $metadata = json_decode($data['metadata']);
            if (!empty($metadata)) {
                if(gettype($metadata) != 'array'){
                    $metadata = [$metadata]; // cast it to an array containing one object
                }
                foreach ($metadata as $meta) {
                    foreach ($meta as $key => $value){
                        try {
                            $existingMeta = Schoolmeta::where('school_id', $data['id'])
                                ->where('schoolmeta_key', $key)
                                ->first();

                            if ($existingMeta) {
                                $existingMeta->schoolmeta_value = $value;
                                $existingMeta->save();
                            } else {
                                Schoolmeta::create([
                                    'school_id' => $data['id'],
                                    'schoolmeta_key' => $key,
                                    'schoolmeta_value' => $value,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                            // Handle the error as needed
                        }
                    }

                }
            }
            // get the latest data with the correct/expected form and return with res()
            $school = School::where('id', $data['id'])->first();

            // get metadata
            $schoolMetadata = Schoolmeta::where('school_id', $school->id)->get();
            $schoolMetadataToSend = [];
            if ($schoolMetadata) {
                foreach ($schoolMetadata as $key => $value) {
                    $res = [
                        'schoolmeta_key' => $value->schoolmeta_key,
                        'schoolmeta_value' => $value->schoolmeta_value
                    ];
                    $schoolMetadataToSend[] = $res;
                }
            }

            $returningResult = [
                'id' => $school->id,
                'site' => [
                    'site_id' => $school->site_id,
                    'site_name' => ($school->site_id) ? $school->site->site_name : NULL
                ],
                'owner' => [
                    'owner_id' => $school->owner_id,
                    'owner_name' => ($school->owner_id) ? $school->owner->full_name : NULL
                ],
                'name' => $school->name,
                'content_blocks' => ($school->content_blocks) ? json_decode($school->content_blocks) : NULL,
                'logo' => ($school->logo) ? $school->logo : NULL,
                'cover_image' => ($school->cover_image) ? $school->cover_image : NULL,
                'tech_used' => ($school->tech_used) ? json_decode($school->tech_used) : NULL,
                'pedagogical_approaches' => ($school->pedagogical_approaches) ? json_decode($school->pedagogical_approaches) : NULL,
                'tech_landscape' => ($school->tech_landscape) ? json_decode($school->tech_landscape) : NULL,
                'metadata' => isset($schoolMetadataToSend) ? $schoolMetadataToSend : NULL
            ];

            return response()->json([
                'message' => "School updated successfully",
                'error' => $error,
                'status' => 200,
                'data' => $returningResult
            ]);
        }
    }

    public function fetchAllSchools()
    {
        $schools = School::get();
        $data = [];
        foreach ($schools as $school) {
            $schoolMetadata = Schoolmeta::where('school_id', $school->id)->get();
            $site = Site::where('site_id',$school->site_id)->first();
            $siteLocation = (object)[
                'lat' => (float)($site->site_latitude ?: 0),
                'lng' => (float)($site->site_longitude ?: 0)
            ];
            $site_type = $site->site_type_code;
            $schoolMetadataToSend = Metahelper::getMeta(Schoolmeta::class, $school, 'school_id','schoolmeta_key','schoolmeta_value' );
//            if ($schoolMetadata) {
//                foreach ($schoolMetadata as $key => $value) {
//                    $res = [`
//                        'schoolmeta_value' => $value->schoolmeta_value
//                    ];
//                    $schoolMetadataToSend[] = $res;
//                }
//            }
            //adding school_type into metadata
            $schoolType = [
                'schoolmeta_key' => 'school_type',
                'schoolmeta_value' => $school->site->site_sub_type_desc
            ];
            $schoolMetadataToSend[] = $schoolType;
            $result = [
                'id' => $school->id,
                'site' => [
                    'site_id' => $school->site->site_id,
                    'site_name' => ($school->site_id) ? $school->site->site_name : NULL,
                    'site_type' => ($site_type) ?: NULL,
                ],
                'owner' => [
                    'owner_id' => $school->owner_id,
                    'owner_name' => ($school->owner_id) ? $school->owner->full_name : NULL
                ],
                'name' => $school->name,
                'content_blocks' => ($school->content_blocks) ? json_decode($school->content_blocks) : NULL,
                'logo' => ($school->logo) ?: NULL,
                'cover_image' => ($school->cover_image) ? $school->cover_image : NULL,
                'tech_used' => ($school->tech_used) ? json_decode($school->tech_used) : NULL,
                'pedagogical_approaches' => ($school->pedagogical_approaches) ? json_decode($school->pedagogical_approaches) : NULL,
                'tech_landscape' => ($school->tech_landscape) ? json_decode($school->tech_landscape) : NULL,
                'metadata' => ($schoolMetadataToSend) ?: NULL,
                'location' => $siteLocation
            ];
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchSchoolByName($schoolName)
    {
        $schoolName = str_replace('%20', ' ', $schoolName);
        $school = School::where('name', $schoolName)->first();
        if ($school == null) {
            return response('School Not found', 404);
        } else {
            $schoolMetadata = Schoolmeta::where('school_id', $school->id)->get();
            $site = Site::where('site_id',$school->site_id)->first();
            $siteLocation = (object)[
                'lat' => (float)$site->site_latitude,
                'lng' => (float)$site->site_longitude
            ];
            $schoolMetadataToSend = [];
            if ($schoolMetadata) {
                foreach ($schoolMetadata as $key => $value) {
                    $res = [
                        'schoolmeta_key' => $value->schoolmeta_key,
                        'schoolmeta_value' => $value->schoolmeta_value
                    ];
                    $schoolMetadataToSend[] = $res;
                }
            }


            $result = [
                'id' => $school->id,
                'site' => [
                    'site_id' => $school->site->site_id,
                    'site_name' => ($school->site->site_id) ? $school->site->site_name : NULL
                ],
                'owner' => [
                    'owner_id' => $school->owner_id,
                    'owner_name' => ($school->owner_id) ? $school->owner->full_name : NULL
                ],
                'name' => $school->name,
                'content_blocks' => ($school->content_blocks) ? json_decode($school->content_blocks) : NULL,
                'logo' => ($school->logo) ? $school->logo : NULL,
                'cover_image' => ($school->cover_image) ? $school->cover_image : NULL,
                'tech_used' => ($school->tech_used) ? json_decode($school->tech_used) : NULL,
                'pedagogical_approaches' => ($school->pedagogical_approaches) ? json_decode($school->pedagogical_approaches) : NULL,
                'tech_landscape' => ($school->tech_landscape) ? json_decode($school->tech_landscape) : NULL,
                'metadata' => ($schoolMetadataToSend) ?: NULL,
                'location' => $siteLocation
            ];
            return response()->json($result, 200);

        }

    }

    //TODO: fetch featured schools only 4
    public function fetchFeaturedSchools()
    {
        $schools = School::where('isFeatured', 1)->get();
        $data = [];

        foreach ($schools as $school) {
            $result = [
                'id' => $school->id,
                'site' => [
                    'site_id' => $school->site->site_id,
                    'site_name' => ($school->site->site_id) ? $school->site->site_name : NULL
                ],
                'owner' => [
                    'owner_id' => $school->owner_id,
                    'owner_name' => ($school->owner_id) ? $school->owner->full_name : NULL
                ],
                'name' => $school->name,
                'content_blocks' => ($school->content_blocks) ? json_decode($school->content_blocks) : NULL,
                'logo' => ($school->logo) ? $school->logo : NULL,
                'cover_image' => ($school->cover_image) ? $school->cover_image : NULL,
                'tech_used' => ($school->tech_used) ? json_decode($school->tech_used) : NULL,
                'pedagogical_approaches' => ($school->pedagogical_approaches) ? json_decode($school->pedagogical_approaches) : NULL,
                'tech_landscape' => ($school->tech_landscape) ? json_decode($school->tech_landscape) : NULL
            ];
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchAllStaffFromSite($site_id): \Illuminate\Http\JsonResponse
    {
        $all_staff = User::where('site_id', $site_id)->get();
        $final_result = [];
        foreach ($all_staff as $staff) {
            $result = [
                'id' => $staff->id,
                'name' => $staff->full_name,
                'email' => $staff->email,
                'role' => ($staff->role) ? $staff->role->role_name : NULL,
            ];
            $final_result[] = $result;
        }
        return response()->json($final_result);
    }

    public function nominateUserForSchool(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'site_id' => 'required',
                'user_id' => 'required',
                'nominated_user_id' => 'required',
            ]);

            $school_id = $requestData['school_id'];
            $site_id = $requestData['site_id'];
            $user_id = $requestData['user_id'];
            $nominated_user_id = $requestData['nominated_user_id'];

            $user_record = User::find($user_id);

            if ($user_record && $user_record->site_id == $site_id && $user_record->role->role_name === 'SCHLDR') {
                OutputHelper::print('User is the school principal.');
                $meta_to_insert = [
                    "school_id" => $school_id,
                    "schoolmeta_key" => 'nominated_user',
                    'schoolmeta_value' => $nominated_user_id,
                ];
                Schoolmeta::create($meta_to_insert);

                return response()->json([
                    "status" => 200,
                    "result" => 'User has been nominated successfully.'
                ]);
            } else {
                return response()->json([
                    "status" => 401,
                    "result" => 'Failed to nominate user. User is not authorized.'
                ]);
            }
        } else {
            return response()->json([
                "status" => 401,
                "result" => 'Failed to nominate user. Invalid request method.'
            ]);
        }
    }

    public function deleteNominatedUserSchool(Request $request)
    {
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'site_id' => 'required',
                'user_id' => 'required',
                'nominated_id_delete' => 'required',
            ]);

            $school_id = $requestData['school_id'];
            $site_id = $requestData['site_id'];
            $user_id = $requestData['user_id'];
            $nominated_id_delete = $requestData['nominated_id_delete'];

            $user_record = User::find($user_id);

            if ($user_record && $user_record->site_id == $site_id && $user_record->role->role_name === 'SCHLDR') {
                OutputHelper::print('User is the school principal.');

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
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'user_id' => 'required',
                'site_id' => 'required'
            ]);

            $school_id = $requestData['school_id'];
            $site_id = $requestData['site_id'];
            $user_id = $requestData['user_id'];

            $user_record = User::find($user_id);

            if ($user_record && $user_record->site_id == $site_id && $user_record->role->role_name === 'SCHLDR') {
                OutputHelper::print('User is the school principal.');

                $nominated_users = Schoolmeta::where('school_id', $school_id)
                    ->where('schoolmeta_key', 'nominated_user')
                    ->pluck('schoolmeta_value');

                $users = User::whereIn('id', $nominated_users)
                    ->pluck('full_name', 'id')
                    ->toArray();

                return response()->json([
                    "status" => 200,
                    "result" => $users
                ]);
            } else {
                return response()->json([
                    "status" => 401,
                    "result" => 'Failed to retrieve nominated users. User is not authorized as the school principal.'
                ]);
            }
        } else {
            return response()->json([
                "status" => 401,
                "result" => 'Invalid request method.'
            ]);
        }
    }

    public function checkUserCanEdit(Request $request): \Illuminate\Http\JsonResponse
    {
        // user id
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'user_id' => 'required',
                'site_id' => 'required'
            ]);

            $site_id = $requestData['site_id'];
            $user_id = $requestData['user_id'];
            $school_id = $requestData['school_id'];
            //check user site id == school Id && user role === school principal
            $user_record = User::find($user_id);

            if ($user_record && $user_record->site_id == $site_id && $user_record->role->role_name === 'SCHLDR') {
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    'canNominate' => true
                ]);
            }
            // check school meta
            // if user's id is inside meta, in the nominated_user field
            $schoolmeta_record = Schoolmeta::where('school_id', $school_id)
                ->where('schoolmeta_key', 'nominated_user')
                ->where('schoolmeta_value', $user_id)
                ->first();

            if ($schoolmeta_record) {
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    'canNominate' => false
                ]);
            }

            return response()->json([
                "status" => 401,
                "result" => False
            ]);

        }
        return response()->json([
            "status" => 401,
            "result" => False
        ]);

    }

    /**
     * School Contact Functions
     */
    public function createOrUpdateContact(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('post')) {
            $requestData = $request->validate([
                'school_id' => 'required',
                'school_contact' => 'required'
            ]);

            $school_id = $requestData['school_id'];
            $school_contact = $requestData['school_contact'];
//            dd($school_contact);
            $schoolmeta_record = Schoolmeta::where('school_id', $school_id)
                ->where('schoolmeta_key', 'school_contact')
                ->first();

            if ($schoolmeta_record) {
                // Update existing school contact
                $schoolmeta_record->schoolmeta_value = $school_contact;
                $schoolmeta_record->save();

                OutputHelper::print('School contact updated successfully.');
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    'message' => 'School contact updated successfully.'
                ]);
            } else {
                // Create new school contact
                $schoolmeta = new Schoolmeta();
                $schoolmeta->school_id = $school_id;
                $schoolmeta->schoolmeta_key = 'school_contact';
                $schoolmeta->schoolmeta_value = $school_contact;
                $schoolmeta->save();

                OutputHelper::print('School contact created successfully.');
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    'message' => 'School contact created successfully.'
                ]);
            }
        }

        return response()->json([
            "status" => 401,
            "result" => false,
            'message' => 'Unauthorized'
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

            OutputHelper::print('School contact not found.');
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
