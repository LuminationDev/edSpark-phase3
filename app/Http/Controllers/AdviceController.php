<?php

namespace App\Http\Controllers;

use App\Helpers\RoleHelpers;
use App\Helpers\StatusHelpers;
use App\Helpers\UserRole;
use App\Http\Middleware\ResourceAccessControl;
use App\Models\Advicetype;
use App\Models\User;
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
        $this->middleware(ResourceAccessControl::class . ':partner,handleFetchAdvicePosts,createAdvicePost,fetchAdvicePostById,fetchRelatedAdvice');
    }


    public function createAdvicePost(Request $request)
    {
        if (strtoupper($request->input('status')) === StatusHelpers::DRAFT) {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string',
                'title' => 'required|string',
            ]);
        } else if (strtoupper($request->input('status')) === StatusHelpers::PENDING) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'content' => 'required|string',
                'excerpt' => 'sometimes|string',
                'status' => 'required|string',
                'author_id' => 'required|integer|exists:users,id',
                'advicetype_id' => 'required|array',
                'advicetype_id.*' => 'integer|exists:advice_types,id',
                'cover_image' => 'sometimes|string',
            ]);
        }


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
        if ($request->has('labels')) {
            $allLabelIds = [];
            $inputArray = $request->input('labels');
            foreach ($inputArray as $subArray) {
                foreach ($subArray as $item) {
                    $allLabelIds[] = $item['id'];
                }
            }
            $advice->labels()->attach($allLabelIds);
        }
        // archive draft
        if ($request->input('existing_id') != 0 && strtolower($request->input('content_origin')) === 'draft') {
            $existingAdvice = Advice::find($request->input('existing_id'));

            if ($existingAdvice) {
                $existingAdvice->update(['status' => StatusHelpers::ARCHIVED]);
            }
        }


        return ResponseService::success('Advice created successfully!');
    }

    public function handleFetchAdvicePosts(Request $request)
    {
        $request_user_id = $request->input('user_id');
        if(isset($request_user_id)){
            $is_requesting_partner_advice = User::find($request_user_id)->isPartner();
        } else $is_requesting_partner_advice = false;

        if (Auth::user()->isPartner() || $is_requesting_partner_advice) {
            return $this->fetchUserPostsAndRelated($request);
        } else {
            return $this->fetchAllAdvicePosts($request);
        }
    }

    public function fetchAllAdvicePosts(Request $request): \Illuminate\Http\JsonResponse
    {


        $advices = Advice::where('status', StatusHelpers::PUBLISHED)->orderBy('created_at', 'DESC')->get();
        $data = [];

        foreach ($advices as $advice) {
            $result = $this->postService->adviceModelToJson($advice, $request);
            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchUserPostsAndRelated(Request $request):JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $user = User::find($userId);
            $tag = $user->full_name;

            // First Query
            $advices = Advice::where('status', \App\Helpers\StatusHelpers::PUBLISHED)
                ->where('author_id', $userId)  // Filter by partner (author) ID
                ->orderBy('created_at', 'DESC')
                ->get();

            // Second Query
            $relatedAdvices = Advice::withAnyTags($tag)
                ->where('status', \App\Helpers\StatusHelpers::PUBLISHED)
                ->where('author_id', '!=', $userId) // Exclude the same user's posts
                ->orderBy('created_at', 'DESC')
                ->get();

            // Merge and remove duplicates
            $mergedAdvices = $advices->merge($relatedAdvices)->unique('id');

            $data = [];

            foreach ($mergedAdvices as $advice) {
                $result = $this->postService->adviceModelToJson($advice, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchUserAdvicePosts(Request $request): JsonResponse
    {
        try {
            $userId = $request->input('user_id');
            $advices = Advice::where('status', \App\Helpers\StatusHelpers::PUBLISHED)
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
            $advices = Advice::where('status', StatusHelpers::DRAFT)
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

    public function fetchAdvicePostById(Request $request): JsonResponse
    {
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|gt:0',
            'preview' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ResponseService::error('Invalid request parameters', 400);
        }

        $id = $request->input('id');
        $preview = $request->input('preview');
        if (RoleHelpers::has_minimum_privilege(UserRole::MODERATOR) && $preview) {
            // Find the advice by ID
            $advice = Advice::find($id);
        } else {
            $advice = Advice::where('id', $id)->where('status', \App\Helpers\StatusHelpers::PUBLISHED)->first();
        }

        // Check if advice with the given ID exists
        if (!$advice) {
            return ResponseService::error('Advice not found', 404);
        }

        $data = $this->postService->adviceModelToJson($advice, $request);

        return ResponseService::success('Successfully retrieved advice', $data);
    }


    public function fetchAdvicePostByType(Request $request, $type): \Illuminate\Http\JsonResponse
    {
        $data = [];

        if (strpos($type, ',')) {
            $typeArray = explode(',', $type);

            foreach ($typeArray as $typeItem) {
                $adviceTypes = Advicetype::where('advice_type_name', $typeItem)->first();
                $adviceArticles = Advice::whereHas('advicetypes', function ($query) use ($adviceTypes) {
                    $query->where('advice_types.id', $adviceTypes->id);
                })->where('status', \App\Helpers\StatusHelpers::PUBLISHED)->orderBy('created_at', 'DESC')->get();

                foreach ($adviceArticles as $advice) {
                    $result = $this->postService->adviceModelToJson($advice, $request);
                    $data[] = $result;

                }
            }

        } else {
            $adviceTypes = Advicetype::where('advice_type_name', $type)->first();
            $adviceArticles = Advice::whereHas('advicetypes', function ($query) use ($adviceTypes) {
                $query->where('advice_types.id', $adviceTypes->id);
            })->where('status', \App\Helpers\StatusHelpers::PUBLISHED)->orderBy('created_at', 'DESC')->get();

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
                ->where('status', \App\Helpers\StatusHelpers::PUBLISHED)
                ->orderBy('created_at', 'DESC')
                ->limit(2)
                ->get();

            // If no related advices are found, fetch two random advice posts
            if ($relatedAdvices->isEmpty()) {
                $relatedAdvices = Advice::where('id', '!=', $currentAdviceId)
                    ->where('status', \App\Helpers\StatusHelpers::PUBLISHED)
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

    public function fetchLearningTask(Request $request): \Illuminate\Http\JsonResponse{
        $learningTasks = Advice::whereHas('advice_types', function ($query) {
            $query->where('advice_type_name', 'Learning Task');
        })->where('status', StatusHelpers::PUBLISHED)->orderBy('created_at', 'DESC')->get();

        $data = [];

        foreach ($learningTasks as $learningTask) {
            $result = $this->postService->adviceModelToJson($learningTask, $request);
            $data[] = $result;
        }

        return response()->json($data);
    }
    public function fetchDAG(Request $request): \Illuminate\Http\JsonResponse{
    $learningTasks = Advice::whereHas('advice_types', function ($query) {
        $query->where('advice_type_name', 'DAG');
    })->where('status', StatusHelpers::PUBLISHED)->orderBy('created_at', 'DESC')->get();

    $data = [];

    foreach ($learningTasks as $learningTask) {
        $result = $this->postService->adviceModelToJson($learningTask, $request);
        $data[] = $result;
    }

    return response()->json($data);
}
}


