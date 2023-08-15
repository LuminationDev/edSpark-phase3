<?php

namespace App\Http\Controllers;

use App\Models\Advicemeta;
use App\Models\Advicetype;
use App\Models\Bookmark;
use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Advice;
use Illuminate\Support\Facades\Auth;

class AdviceController extends Controller
{
    private function adviceModelToJson($advice, $request): array
    {
        $isLikedByUser = false;
        $isBookmarkedByUser = false;

        if(isset($request) && $request->has('usid')){
            $userId = $request->input('usid');
            $isLikedByUser = $advice->likes()->where('user_id', $userId)->exists();
            $isBookmarkedByUser = $advice->bookmarks()->where('user_id', $userId)->exists();

        }
        return [
            'post_id' => $advice->id,
            'post_title' => $advice->post_title,
            'post_content' => $advice->post_content,
            'post_excerpt' => $advice->post_excerpt,
            'author'=> [
                'author_id' => $advice->author->id,
                'author_name'=> $advice->author->full_name
            ],
            'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL ,
            'template' => ($advice->template) ? $advice->template : NULL,
            'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
            'post_date' => $advice->post_date,
            'post_modified' => $advice->post_modified,
            'post_status' => $advice->post_status,
            'advice_type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL ,
            'created_at' => $advice->created_at,
            'updated_at' => $advice->updated_at,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
        ];
    }
    public function fetchAdvicePosts(Request $request): \Illuminate\Http\JsonResponse
    {

        $advices = Advice::where('post_status', 'Published')->orderBy('created_at', 'DESC')->get();
        $data = [];

        foreach ($advices as $advice){
            $result = $this->adviceModelToJson($advice, $request);
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchAdvicePostById(Request $request ,$id)
    {
        // Validate the input $id to ensure it's a positive integer
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['error' => 'Invalid ID provided.'], 400);
        }

        // Find the advice by ID
        $advice = Advice::find($id);

        // Check if advice with given ID exists
        if (!$advice) {
            return response()->json(['error' => 'Advice not found.'], 404);
        }

        // Prepare the data to be returned in the response
        $data = $this->adviceModelToJson($advice, $request);

        // Return the data in the response
        return response()->json($data);
    }
    public function fetchAdvicePostByType(Request $request,$type): \Illuminate\Http\JsonResponse
    {
        $data = [];

        if (strpos($type, ',')) {
            $typeArray = explode(',', $type);

            foreach($typeArray as $typeItem) {
                $adviceTypes = Advicetype::where('advice_type_name', $typeItem)->first();
                $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

                foreach ($adviceArticles as $advice) {
                    $result = $this->adviceModelToJson($advice,$request);
                    $data[] = $result;

                }
            }

        } else {
            // var_dump($type);
            // var_dump('bb'); exit;
            $adviceTypes = Advicetype::where('advice_type_name', $type)->first();
            $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

            foreach ($adviceArticles as $advice) {
                $result = $this->adviceModelToJson($advice, $request);
                $data[] = $result;

            }
        }


        return response()->json($data);


    }
}


