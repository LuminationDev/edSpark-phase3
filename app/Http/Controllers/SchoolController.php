<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Schoolmeta;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
                try{
                    $prefix = "edspark-school";
                    if ($data['logo']){
                        $schoolLogo = $data['logo'];
//                         $schoolLogo = $request->file($data['logo']);
//                         dd(is_file($data['logo']));
//                         dd($data['logo']->getClientOriginalExtension());
//                         var_dump(file_get_contents($data['logo']->path())); exit;
                        $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$schoolLogo).'.'.$schoolLogo->getClientOriginalExtension();
                        $schoolLogo->storeAs('public/uploads/school/logo', $imgName);
                        $schoolLogoUrl = "uploads\/school\/logo\/". $imgName;

                    }

                    if ($data['cover_image']) {
//                         $coverImage = $request->file($data['cover_image']);
                        $coverImage = $data['cover_image'];
                        $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$coverImage).'.'.$coverImage->getClientOriginalExtension();
                        $coverImage->storeAs('public/uploads/school', $imgName);
                        $coverImageUrl = "uploads\/school\/". $imgName;
                    }
                    $dataToInsert = [
                        'site_id' => $data['site_id'],
                        'owner_id' => $data['owner_id'],
                        // 'allowEditIds' => ($data['allowEditIds'])
                        'name' => $data['name'],
                        'content_blocks' => json_encode($data['content_blocks']),
                        // 'logo' => isset($data['logo']) ? $data['logo'] : NULL,
                        'logo' => isset($schoolLogoUrl) ? $schoolLogoUrl : NULL,
                        // 'cover_image' => isset($data['cover_image']) ? $data['cover_image'] : NULL,
                        'cover_image' => isset($coverImageUrl) ? $coverImageUrl : NULL,
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
            if($schoolMeta) {
                try {
                    foreach ($schoolMeta as $key => $value){
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

                } catch(Exception $e){
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
            $data = $request->schoolData;
            $error = '';

            if ($data) {
                try {
                    $dataToUpdate = [
                        'name' => $data['name'],
                        'content_blocks' => json_encode($data['content_blocks']),
                        'logo' => isset($data['logo']) ? $data['logo'] : NULL,
                        'cover_image' => isset($data['cover_image']) ? $data['cover_image'] : NULL,
                        'tech_used' => json_encode($data['tech_used']),
                        'pedagogical_approaches' => json_encode($data['pedagogical_approaches']),
                        'tech_landscape' => json_encode($data['tech_landscape']),
                        'updated_at' => Carbon::now()

                    ];
                    School::where('id', '=', $data['id'])
                        ->update($dataToUpdate);
                } catch (Exception $e){
                    $error = $e->getMessage();
                }
            }

            $metadata = $request->schoolMetadata;
            if($metadata){
                foreach ($metadata as $key => $value){
                    try {
                        $meta = Schoolmeta::where([['school_id',$data['id']],['schoolmeta_key', $key]])->get();
                        //meta already exist
                        if(count($meta) > 0 ) {
                            Schoolmeta::where([['school_id',$data['id']],['schoolmeta_key',$key]])->update([
                                'schoolmeta_value' => $value,
                            ]);
                        }
                        // meta doesnt exist and create a new one
                        else {
                            Schoolmeta::insert([
                                'school_id' => $data['id'],
                                'schoolmeta_key' => $key,
                                'schoolmeta_value' => $value,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ]);
                        }
                    } catch (Exception $e){
                        $error = $e->getMessage();
                    }
                }
            };

            return response()->json([
                'message' => "School updated successfully",
                'error' => $error,
                'status' => 200
            ]);
        }
    }

    public function fetchAllSchools() {
        $schools = School::get();

        $data = [];

        foreach ($schools as $school) {
            $schoolMetadata = Schoolmeta::where('school_id', $school->id)->get();
            $schoolMetadataToSend = [];
            if($schoolMetadata){
                foreach($schoolMetadata as $key => $value){
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
                'metadata' => ($schoolMetadataToSend) ? $schoolMetadataToSend : NULL
            ];
            $data[] = $result;
        }

        return response()->json($data);

    }

    //TODO: fetch featured schools only 4
    public function fetchFeaturedSchools() {
        $schools = School::where('isFeatured', 1)->get();
        // dd($schools);
        $data = [];

        foreach ($schools as $school) {
            $result = [
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
                'tech_landscape' => ($school->tech_landscape) ? json_decode($school->tech_landscape) : NULL
            ];
            $data[] = $result;
        }

        return response()->json($data);

    }

    //TODO: fetch featured schools only 4
}
