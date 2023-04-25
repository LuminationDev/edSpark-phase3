<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Like;
use App\Models\Bookmark;
use App\Models\User;


class LikeBookmarkController extends Controller
{
    /**
     * Like Feature
     * Toggable: like and unlike
     */
    public function like(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($data) {
                $postId = $data['post_id'];
                $postType = $data['post_type'];
                $userId = $data['user_id'];

                //check whether the post id exists or not in Like table
                $checkLike = Like::where('post_id', '=', $postId)
                                ->where('user_id', '=', $userId)
                                ->first();

                if($checkLike) {
                    // if like exists delete from database
                    $checkLike->delete();

                    return response()->json([
                        "message" => "You have unliked a post.",
                        "isLiked" => FALSE,
                        "status" => 200
                    ]);

                } else {
                    // if like doesnot exists insert into database
                    $dataToInsert = [
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'user_id' => $userId
                    ];
                    Like::insert($dataToInsert);

                    return response()->json([
                        "message" => "You have liked a post.",
                        "isLiked" => TRUE,
                        "status" => 200
                    ]);

                }

            }
        }
    }

    /**
     * Bookmark Feature
     * Toggable: Bookmark and Unbookmark
     */
    public function bookmark(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($data) {
                $postId = $data['post_id'];
                $postType = $data['post_type'];
                $userId = $data['user_id'];

                // Check whether the post id exists in Bookmark table
                $checkBookmark = Bookmark::where('post_id', '=', $postId)
                                            ->where('user_id', '=', $userId)
                                            ->first();

                if ($checkBookmark) {
                    $checkBookmark->delete();

                    return response()->json([
                        "message" => "You have unbookmarked a post.",
                        "isBookmarked" => FALSE,
                        "status" => 200
                    ]);

                } else {
                    $dataToInsert = [
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'user_id' => $userId
                    ];
                    Bookmark::insert($dataToInsert);

                    return response()->json([
                        "message" => "You have bookmarked a post.",
                        "isBookmarked" => TRUE,
                        "status" => 200
                    ]);
                }

            }
        }
    }

    // Get all likes
    public function fetchAllLikes(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            $userId = $data['user_id'];
            $user = User::findOrFail($userId);
            $likes = $user->likes;
            $dataToSend = [];
            if ($likes) {
                foreach ($likes as $like) {
                    $result = [
                        'post_id' => $like->post_id,
                        'post_type' => $like->post_type
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json($dataToSend);
        }

    }

    // Get all bookmarks
    public function fetchAllBookmarks(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            $userId = $data['user_id'];
            $user = User::findOrFail($userId);
            $bookmarks = $user->bookmarks;
            $dataToSend = [];
            if ($bookmarks) {
                foreach ($bookmarks as $bookmark) {
                    $result = [
                        'post_id' => $bookmark->post_id,
                        'post_type' => $bookmark->post_type
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json($dataToSend);
        }
    }

    // Get all likes by post type
    public function fetchAllLikesByType(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            $userId = $data['user_id'];
            $postType = $data['post_type'];
            $likes = Like::where('user_id', '=', $userId)
                            ->where('post_type', '=', $postType)
                            ->get();
            $dataToSend = [];
            if ($likes) {
                foreach ($likes as $like) {
                    $result = [
                        'post_id' => $like->post_id
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json($dataToSend);

        }
    }

    // Get all bookmarks by post type
    public function fetchAllBookmarksByType(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            $userId = $data['user_id'];
            $postType = $data['post_type'];
            $bookmarks = Bookmark::where('user_id', '=', $userId)
                                    ->where('post_type', '=', $postType)
                                    ->get();
            $dataToSend = [];

            if ($bookmarks) {
                foreach ($bookmarks as $bookmark) {
                    $result = [
                        'post_id' => $bookmark->post_id
                    ];
                    $dataToSend[] = $result;
                }
            }

            return response()->json($dataToSend);
        }
    }
}
