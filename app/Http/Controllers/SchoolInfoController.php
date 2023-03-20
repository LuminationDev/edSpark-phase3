<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolInfo;
use Carbon\Carbon;


class SchoolInfoController extends Controller
{
   public function fetchSchoolInfoById($id){
       $schoolInfo = SchoolInfo::where('school_id',$id)->first();
       if($schoolInfo) {
           $result = [
               'id' => $schoolInfo->school_id,
               'full_name' => $schoolInfo->full_name,
               'description' => $schoolInfo->description,
               'cover' => $schoolInfo->cover,
               'tech_used' => $schoolInfo->tech_used,
               'school_content' => $schoolInfo->school_content,
               'created_at' => $schoolInfo->created_at,
               'updated_at' => $schoolInfo->updated_at,

           ];

           return response()->json($result);
       } else{
           return response()->json('No site found');
       }
   }
   public function fetchSchoolByFullName($name){

        $schoolInfo = SchoolInfo::where('full_name', $name)->first();
         if($schoolInfo) {
                   $result = [
                       'id' => $schoolInfo->id,
                       'full_name' => $schoolInfo->full_name,
                       'description' => $schoolInfo->description,
                       'cover' => $schoolInfo->cover,
                       'tech_used' => $schoolInfo->tech_used,
                       'school_content' => $schoolInfo->school_content,
                       'created_at' => $schoolInfo->created_at,
                       'updated_at' => $schoolInfo->updated_at,

                   ];

                   return response()->json($result);
               } else{
                   return response()->json('No site found');
               }

   }

    public function getLastActiveSchoolInfo($name){
        $schoolInfo = SchoolInfo::where([['full_name', $name],['status','published']])->latest()->first();
        return $schoolInfo;

    }
    public function getAllInfoOfOneSchoolByFullName($name){

        $schoolInfo = SchoolInfo::where('full_name', $name)->get();
        $result = [];
        for($i = 0 ; $i < $schoolInfo->count(); $i+=1 ){
            $tempRes = [
                'id' => $schoolInfo[$i]->id,
                'full_name' => $schoolInfo[$i]->full_name,
                'description' => $schoolInfo[$i]->description,
                'cover' => $schoolInfo[$i]->cover,
                'tech_used' => $schoolInfo[$i]->tech_used,
                'school_content' => $schoolInfo[$i]->school_content,
                'created_at' => $schoolInfo[$i]->created_at,
                'updated_at' => $schoolInfo[$i]->updated_at,
            ];
            array_push($result, $tempRes);
        }
        dd($result);

        if($schoolInfo) {
            $result = [
                'id' => $schoolInfo->id,
                'full_name' => $schoolInfo->full_name,
                'description' => $schoolInfo->description,
                'cover' => $schoolInfo->cover,
                'tech_used' => $schoolInfo->tech_used,
                'school_content' => $schoolInfo->school_content,
                'created_at' => $schoolInfo->created_at,
                'updated_at' => $schoolInfo->updated_at,
            ];
            return response()->json($result);
        } else {
            return response()->json('No site found');
        }
    }

    public function setSchoolInfoByName($name, Request $request){
        // Retrieve previous data and taken Id out
        $oldSchoolInfo = $this->getLastActiveSchoolInfo($name);
        $oldId = $oldSchoolInfo->id;
        if ($request->isMethod('post')) {
            $data = $request;
            if($data){
                $dataToInsert = [
                    'school_id' => $data['school_id'],
                    'full_name' => $data['full_name'],
                    'description' => $data['description'],
                    'cover' => $data['cover'],
                    'tech_used' => $data['tech_used'],
                    'school_content' => $data['school_content'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'status' => $data['status']
                ];
                $schoolInfoId = SchoolInfo::insertGetId($dataToInsert);
                // set previous post status to archived
                $oldSchoolInfo->status ='archived';
                $oldSchoolInfo->save();
            };

        };
    }
}
