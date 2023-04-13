<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advice;

class AdviceController extends Controller
{
    public function fetchAdvicePosts()
    {
        $advices = Advice::where('post_status', 'Published')->get();
        $data = [];

        foreach ($advices as $advice){
            $result = [
                'post_id' => $advice->id,
                'post_title' => $advice->post_title,
                'post_content' => $advice->post_content,
                'post_excerpt' => $advice->post_excerpt,
                'author' => $advice->author->full_name,
                'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL ,
                'template' => ($advice->template) ? $advice->template : NULL,
                'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
                'post_date' => $advice->post_date,
                'post_modified' => $advice->post_modified,
                'post_status' => $advice->post_status,
                'advice_type' => ($advice->advicetype) ? $advice->advicetype->advice_type_name : NULL ,
                'created_at' => $advice->created_at,
                'updated_at' => $advice->updated_at
            ];

            $data[] = $result;
        }

        return response()->json($data);

    }
}


