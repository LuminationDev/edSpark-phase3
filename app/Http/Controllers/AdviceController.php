<?php

namespace App\Http\Controllers;

use App\Models\Advicemeta;
use App\Models\Advicetype;
use Illuminate\Http\Request;
use App\Models\Advice;

class AdviceController extends Controller
{
    public function fetchAdvicePosts()
    {
        $advices = Advice::where('post_status', 'Published')->orderBy('created_at', 'DESC')->get();
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
                'advice_type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL ,
                'created_at' => $advice->created_at,
                'updated_at' => $advice->updated_at
            ];

            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchAdvicePostById($id)
    {
        $advice = Advice::find($id);

        $data = [
            'post_id' => $advice->id,
            'post_title' => $advice->post_title,
            'post_content' => $advice->post_content,
            'post_excerpt' => $advice->post_excerpt,
            'author' => $advice->author->full_name,
            'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL,
            'template' => ($advice->template) ? $advice->template : NULL,
            'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
            'post_date' => $advice->post_date,
            'post_modified' => $advice->post_modified,
            'post_status' => $advice->post_status,
            'advice_type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL ,
            'created_at' => $advice->created_at,
            'updated_at' => $advice->updated_at
        ];

        return response()->json($data);
    }

    public function fetchDAGAdvice() {

    }

    public function fetchAdvice() {

    }

    public function fetchAdvicePostByType($type) {

        $typeArray = [];
        $data = [];

        if (strpos($type, ',')) {
            // var_dump($type);
            // var_dump('aa'); exit;
            $typeArray = explode(',', $type);

            foreach($typeArray as $typeItem) {
                $adviceTypes = Advicetype::where('advice_type_name', $typeItem)->first();
                $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

                foreach ($adviceArticles as $advice) {

                    $result = [
                        'post_id' => $advice->id,
                        'post_title' => $advice->post_title,
                        'post_content' => $advice->post_content,
                        'post_excerpt' => $advice->post_excerpt,
                        'author' => $advice->author->full_name,
                        'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL,
                        'template' => ($advice->template) ? $advice->template : NULL,
                        'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
                        'post_date' => $advice->post_date,
                        'post_modified' => $advice->post_modified,
                        'post_status' => $advice->post_status,
                        'advice_type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL ,
                        'created_at' => $advice->created_at,
                        'updated_at' => $advice->updated_at
                    ];
                    $data[] = $result;

                }
            }

        } else {
            // var_dump($type);
            // var_dump('bb'); exit;
            $adviceTypes = Advicetype::where('advice_type_name', $type)->first();
            $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

            foreach ($adviceArticles as $advice) {

                $result = [
                    'post_id' => $advice->id,
                    'post_title' => $advice->post_title,
                    'post_content' => $advice->post_content,
                    'post_excerpt' => $advice->post_excerpt,
                    'author' => $advice->author->full_name,
                    'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL,
                    'template' => ($advice->template) ? $advice->template : NULL,
                    'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
                    'post_date' => $advice->post_date,
                    'post_modified' => $advice->post_modified,
                    'post_status' => $advice->post_status,
                    'advice_type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL ,
                    'created_at' => $advice->created_at,
                    'updated_at' => $advice->updated_at
                ];
                $data[] = $result;

            }
        }


        return response()->json($data);


    }
}


