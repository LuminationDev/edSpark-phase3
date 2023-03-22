<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Carbon\Carbon;

class SchoolController extends Controller
{
    public function createSchool(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all(); // if not data key present
            // $data = $request->data; // if data key present can be used later for metaData
            $error = '';

            if ($data) {
                // save school info into school table
                try{
                    $dataToInsert = [
                        'site_id' => $data['site_id'],
                        'owner_id' => $data['owner_id'],
                        // 'allowEditIds' => ($data['allowEditIds'])
                        'name' => $data['name'],
                        'content_blocks' => json_encode($data['content_blocks']),
                        'logo' => isset($data['logo']) ? $data['logo'] : NULL,
                        'cover_image' => isset($data['cover_image']) ? $data['cover_image'] : NULL,
                        'tech_used' => json_encode($data['tech_used']),
                        'pedagogical_approaches' => json_encode($data['pedagogical_approaches']),
                        'tech_landscape' => json_encode($data['tech_landscape']),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    School::insert($dataToInsert);
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
            // dd($data);
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
                'content_blocks' => ($school->content_blocks) ? json_decode($school->owner_id) : NULL,
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
                'content_blocks' => ($school->content_blocks) ? json_decode($school->owner_id) : NULL,
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
}
