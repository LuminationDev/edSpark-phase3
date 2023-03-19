<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolInfo;

class SchoolInfoController extends Controller
{
   public function fetchSchoolInfoById($id){
       $schoolInfo = SchoolInfo::find($id);
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
}
