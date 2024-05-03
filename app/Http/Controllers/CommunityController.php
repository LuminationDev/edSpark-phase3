<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;

class CommunityController extends Controller
{
    public function fetchCommunityPosts()
    {
        $communities = Community::where('status', \App\Helpers\StatusHelpers::PUBLISHED)->get();

        $data = [];

        foreach ($communities as $community){
            $result = [
                'community_id' => $community->id,
                'post_title' => $community->post_title,
                'content' => $community->content,
                'post_excerpt' => $community->excerpt,
                'author' => ($community->author) ? $community->author->full_name : '',
                'cover_image' => ($community->cover_image) ? $community->cover_image : NULL,
                'status' => $community->status,
                'community_type' => ($community->communitytype) ? $community->communitytype->community_type_name : NULL,
                'created_at' => $community->created_at,
                'updated_at' => $community->updated_at
            ];

            $data[] = $result;
        }

        return response()->json($data);

    }
}
