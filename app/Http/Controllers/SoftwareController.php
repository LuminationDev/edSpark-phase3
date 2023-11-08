<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Softwaretype;
use App\Services\PostService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Software;
use App\Models\Softwaremeta;
use Illuminate\Support\Facades\Validator;

class SoftwareController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    public function createSoftwarePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string',
            'post_content' => 'required|string',
            'post_excerpt' => 'sometimes|string',
            'post_status' => 'required|string',
            'author_id' => 'required|integer|exists:users,id',
            'softwaretype_id' => 'required|array',
            'softwaretype_id.*' => 'integer|exists:software_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $softwareData = $request->except('softwaretype_id'); // Exclude the softwaretype_id from the data to be directly used in the creation
        $software = Software::create($softwareData);

        // Attach the software types to the created software
        if ($request->has('softwaretype_id')) {
            $software->softwaretypes()->sync($request->input('softwaretype_id'));
        }
        if ($request->has('tags')) {
            $software->attachTags($request->input('tags'));
        }

        return response()->json(['message' => 'Software created successfully!', 'software' => $software], 201);
    }

    public function fetchSoftwarePosts(Request $request): JsonResponse
    {
        try {
            $softwares = Software::where('post_status', 'Published')
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($softwares as $software) {
                $softwareMetadataToSend = Metahelper::getMeta(
                    Softwaremeta::class,
                    $software,
                    'software_id',
                    'software_meta_key',
                    'software_meta_value'
                );
                $result = $this->postService->softwareModelToJson($software, $softwareMetadataToSend, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchUserSoftwarePosts(Request $request): JsonResponse
    {
        try {
            $userId = $request->user_id;
            $softwares = Software::where('post_status', 'Published')
                ->where('author_id', $userId)  // Filter by partner (author) ID
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($softwares as $software) {
                $softwareMetadataToSend = Metahelper::getMeta(
                    Softwaremeta::class,
                    $software,
                    'software_id',
                    'software_meta_key',
                    'software_meta_value'
                );
                $result = $this->postService->softwareModelToJson($software, $softwareMetadataToSend, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function fetchRelatedSoftware(Request $request): JsonResponse
    {
        try {
            $currentSoftwareId = $request->input('currentId');

            // Fetch the software associated with the currentSoftwareId
            $currentSoftware = Software::find($currentSoftwareId);
            if (!$currentSoftware) {
                return response()->json(['error' => 'Software not found'], Response::HTTP_NOT_FOUND);
            }

            // Retrieve the tags from the current software
            $tags = $currentSoftware->tags->pluck('name')->toArray();

            // Fetch software posts that have at least one of the current software's tags and don't have the currentSoftwareId
            $relatedSoftwares = Software::withAnyTags($tags)
                ->where('id', '!=', $currentSoftwareId)
                ->where('post_status', 'Published')
                ->orderBy('created_at', 'DESC')
                ->get();

            if ($relatedSoftwares->isEmpty()) {
                // Fetch two random software posts excluding the currentSoftwareId
                $relatedSoftwares = Software::where('id', '!=', $currentSoftwareId)
                    ->where('post_status', 'Published')
                    ->inRandomOrder()
                    ->limit(2)
                    ->get();
            }

            $data = [];

            foreach ($relatedSoftwares as $software) {
                $softwareMetadataToSend = Metahelper::getMeta(
                    Softwaremeta::class,
                    $software,
                    'software_id',
                    'software_meta_key',
                    'software_meta_value'
                );
                $result = $this->postService->softwareModelToJson($software, $softwareMetadataToSend, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchSoftwarePostById(Request $request, $id): JsonResponse
    {
        $software = Software::find($id);
        $softwareMetadataToSend = Metahelper::getMeta(
            Softwaremeta::class,
            $software,
            'software_id',
            'software_meta_key',
            'software_meta_value'
        );
        $data = $this->postService->softwareModelToJson($software, $softwareMetadataToSend, $request);
        return response()->json($data);

    }

    public function fetchSoftwareTypes(Request $request): JsonResponse
    {
        $softwareTypes = Softwaretype::all()
            ->map(function ($softwaretype) {
                return [
                    'id' => $softwaretype->id,
                    'name' => $softwaretype->software_type_name,
                    'value' => $softwaretype->software_type_value
                ];
            })
            ->toArray();

        return response()->json($softwareTypes);
    }

}
