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
            'id' => $advice->id,
            'title' => $advice->post_title,
            'content' => $advice->post_content,
            'excerpt' => $advice->post_excerpt,
            'author'=> [
                'author_id' => $advice->author->id,
                'author_name'=> $advice->author->full_name
            ],
            'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL ,
            'template' => ($advice->template) ? $advice->template : NULL,
            'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
            'created_at' => $advice->post_date,
            'modified_at' => $advice->post_modified,
            'status' => $advice->post_status,
            'type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL ,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $advice->tags->pluck('name')
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
            $adviceTypes = Advicetype::where('advice_type_name', $type)->first();
            $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

            foreach ($adviceArticles as $advice) {
                $result = $this->adviceModelToJson($advice, $request);
                $data[] = $result;

            }
        }


        return response()->json($data);


    }

    public function fetchRelatedAdvice(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Get currentId from the request body
            $currentAdviceId = $request->input('currentId');

            // Fetch the advice post associated with the currentAdviceId
            $currentAdvice = Advice::find($currentAdviceId);
            if (!$currentAdvice) {
                return response()->json(['error' => 'Advice not found'], Response::HTTP_NOT_FOUND);
            }

            // Retrieve the tags from the current advice
            $tags = $currentAdvice->tags->pluck('name')->toArray();

            // Fetch advice posts that have at least one of the current advice's tags and don't have the currentAdviceId
            $relatedAdvices = Advice::withAnyTags($tags)
                ->where('id', '!=', $currentAdviceId)
                ->where('post_status', 'Published')
                ->orderBy('created_at', 'DESC')
                ->limit(2)
                ->get();

            // If no related advices are found, fetch two random advice posts
            if ($relatedAdvices->isEmpty()) {
                $relatedAdvices = Advice::where('id', '!=', $currentAdviceId)
                    ->where('post_status', 'Published')
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            }

            // Convert each related advice to JSON format
            $data = [];
            foreach ($relatedAdvices as $advice) {
                $result = $this->adviceModelToJson($advice, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}


