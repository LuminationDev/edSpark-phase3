<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Software;

class SoftwareController extends Controller
{
    public function fetchSoftwarePosts()
    {
        $softwares = Software::where('post_status', 'Published')->get();
        $data = [];

        foreach ($softwares as $software){
            $result = [
                'post_id' => $software->id,
                'post_title' => $software->post_title,
                'post_content' => $software->post_content,
                'post_excerpt' => $software->post_excerpt,
                'author' => ($software->author) ? $software->author->full_name : NULL,
                'cover_image' => ($software->cover_image) ? $software->cover_image : NULL,
                'post_date' => $software->post_date,
                'post_modified' => $software->post_modified,
                'post_status' => $software->post_status,
                'software_type' => ($software->softwaretype) ? $software->softwaretype->software_type_name : NULL ,
                'created_at' => $software->created_at,
                'updated_at' => $software->updated_at
            ];

            $data[] = $result;
        }

        return response()->json($data);
    }
}
