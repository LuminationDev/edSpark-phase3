<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\Advicemeta;
use App\Models\Advicetype;
use App\Models\Bookmark;
use App\Models\Like;
use App\Models\Software;
use App\Models\Softwaremeta;
use App\Services\PostService;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Advice;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdviceController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function createAdvicePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string',
            'post_content' => 'required|string',
            'post_excerpt' => 'sometimes|string',
            'post_status' => 'required|string',
            'author_id' => 'required|integer|exists:users,id',
            'advicetype_id' => 'required|array',
            'advicetype_id.*' => 'integer|exists:advice_types,id',
            'cover_image' => 'sometimes|string',
            'extra_content' => 'sometimes|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $adviceData = $request->except('advicetype_id');
        $advice = Advice::create($adviceData);

        // Attach the advice types to the created advice
        if ($request->has('advicetype_id')) {
            $advice->advicetypes()->sync($request->input('advicetype_id'));
        }
        if ($request->has('tags')) {
            $advice->attachTags($request->input('tags'));
        }
        return ResponseService::success('Advice created successfully!');
    }

    public function fetchAdvicePosts(Request $request): \Illuminate\Http\JsonResponse
    {

        $advices = Advice::where('post_status', 'Published')->orderBy('created_at', 'DESC')->get();
        $data = [];

        foreach ($advices as $advice) {
            $result = $this->postService->adviceModelToJson($advice, $request);
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchUserAdvicePosts(Request $request): JsonResponse
    {
        try {
            $userId = $request->user_id;
            $advices = Advice::where('post_status', 'Published')
                ->where('author_id', $userId)  // Filter by partner (author) ID
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($advices as $advice) {

                $result = $this->postService->adviceModelToJson($advice, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchCurrentUserDraftAdvicePosts(Request $request): JsonResponse
    {
        try {
            $userId = Auth::user()->id;
            $advices = Advice::where('post_status', 'Draft')
                ->where('author_id', $userId)  // Filter by partner (author) ID
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($advices as $advice) {

                $result = $this->postService->adviceModelToJson($advice, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchAdvicePostById(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|gt:0',
        ]);
        if ($validator->fails()) {
            return ResponseService::error('Invalid ID', 400);
        }
        if (RoleHelpers::has_minimum_privilege(UserRole::MODERATOR)) {
            // Find the advice by ID
            $advice = Advice::find('id', $id);
        } else {
            $advice = Advice::where('id', $id)->where('post_status', "Published")->first();
        }
        // Check if advice with given ID exists
        if (!$advice) {
            return ResponseService::error('Advice not found', 404);
        }
        $data = $this->postService->adviceModelToJson($advice, $request);

        return ResponseService::success( "Successfully retrieved advice", $data);
    }


    public function fetchAdvicePostByType(Request $request, $type): \Illuminate\Http\JsonResponse
    {
        $data = [];

        if (strpos($type, ',')) {
            $typeArray = explode(',', $type);

            foreach ($typeArray as $typeItem) {
                $adviceTypes = Advicetype::where('advice_type_name', $typeItem)->first();
                $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

                foreach ($adviceArticles as $advice) {
                    $result = $this->postService->adviceModelToJson($advice, $request);
                    $data[] = $result;

                }
            }

        } else {
            $adviceTypes = Advicetype::where('advice_type_name', $type)->first();
            $adviceArticles = Advice::where('advicetype_id', $adviceTypes->id)->get();

            foreach ($adviceArticles as $advice) {
                $result = $this->postService->adviceModelToJson($advice, $request);
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
                $result = $this->postService->adviceModelToJson($advice, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchAdviceTypes(Request $request): JsonResponse
    {

        $adviceTypes = AdviceType::all()
            ->map(function ($adviceType) {
                return [
                    'id' => $adviceType->id,
                    'name' => $adviceType->advice_type_name,
                    'value' => $adviceType->advice_type_value
                ];
            })
            ->toArray();

        return response()->json($adviceTypes);
    }
}


