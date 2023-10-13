<?php

namespace App\Http\Controllers;

use App\Helpers\OutputHelper;
use App\Helpers\PostHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Like;
use App\Models\Bookmark;
use App\Models\User;
use Konnco\FilamentImport\Tests\Resources\Models\Post;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\Console\Output\Output;


class LikeBookmarkController extends Controller
{
    /**
     * Like Feature
     * Toggable: like and unlike
     */
    public function like(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            if (!empty($data)) {
                $postId = $data['post_id'];
                $postType = $data['post_type'];
                $userId = $data['user_id'];

                // Check whether the post exists in the Like table for the given user
                $checkLike = Like::where('post_id', $postId)
                    ->where('user_id', $userId)
                    ->where('post_type', $postType)
                    ->first();

                if ($checkLike) {
                    // Unlike the post
                    $checkLike->delete();

                    return response()->json([
                        "message" => "You have unliked the post.",
                        "isLiked" => false,
                        "status" => 200
                    ]);
                } else {
                    // Like the post
                    $like = Like::create([
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'user_id' => $userId
                    ]);

                    return response()->json([
                        "message" => "You have liked the post.",
                        "isLiked" => true,
                        "status" => 200
                    ]);
                }
            }
        }
        return response()->json([
            "message" => "Invalid request.",
            "status" => 400
        ]);
    }


    /**
     * Bookmark Feature
     * Toggleable: Bookmark and Unbookmark
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
                                            ->where('post_type', $postType)
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
            $count = 0;
            if ($likes) {
                $count += count($likes);
                foreach ($likes as $like) {
                    $result = [
                        'post_id' => $like->post_id,
                        'post_type' => $like->post_type
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json([
                "data" => $dataToSend,
                "count" => $count
            ]);
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
            $count = 0;
            if ($bookmarks) {
                $count += count($bookmarks);
                foreach ($bookmarks as $bookmark) {
                    $result = [
                        'post_id' => $bookmark->post_id,
                        'post_type' => $bookmark->post_type
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json([
                "data" => $dataToSend,
                "count" => $count
            ]);
        }
    }


    public function fetchAllBookmarksWithTitle(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            $userId = $data['user_id'];
            $user = User::findOrFail($userId);
            $bookmarks = $user->bookmarks;
            $dataToSend = [];
            $count = 0;
            if ($bookmarks) {
                $count += count($bookmarks);
                foreach ($bookmarks as $bookmark) {
                    $post_info = PostHelper::getPostTitle($bookmark->post_id,$bookmark->post_type);
                    $result = [
                        'post_id' => $bookmark->post_id,
                        'post_type' => $bookmark->post_type,
                        'post_title' => $post_info['post_title'],
                        'cover_image' => $post_info['cover_image'],
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json([
                "data" => $dataToSend,
                "count" => $count
            ]);
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
            $count = 0;
            if ($likes) {
                $count += count($likes);
                foreach ($likes as $like) {
                    $result = [
                        'post_id' => $like->post_id
                    ];
                    $dataToSend[] = $result;
                }
            }
            return response()->json([
                "data" => $dataToSend,
                "count" => $count
            ]);

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
            $count = 0;
            if ($bookmarks) {
                $count += count($bookmarks);
                foreach ($bookmarks as $bookmark) {
                    $result = [
                        'post_id' => $bookmark->post_id
                    ];
                    $dataToSend[] = $result;
                }
            }

            return response()->json([
                "data" => $dataToSend,
                "count" => $count
            ]);
        }
    }
}
