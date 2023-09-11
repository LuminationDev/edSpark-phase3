<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Partner;
use App\Models\Usermeta;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Software;
use App\Models\Softwaremeta;
use Illuminate\Support\Facades\Validator;

class SoftwareController extends Controller
{
    private function getAuthorLogo($author)
    {
        if (!$author || !$author->usertype) {
            return '';
        }

        $authorType = $author->usertype->user_type_name;
        $authorId = $author->id;

        switch ($authorType) {
            case 'user':
                $avatar = Usermeta::where('user_id', $authorId)
                    ->where('user_meta_key', 'userAvatar')
                    ->first();
                return $avatar->user_meta_value ?? '';

            case 'partner':
                $partnerLogo = Partner::where('user_id', $authorId)->first();
                return $partnerLogo ? json_decode($partnerLogo->logo, true) : '';

            default:
                return '';
        }
    }

    private function softwareModelToJson($software, $schoolMetadata = NULL, $request = NULL): array
    {

        $isLikedByUser = false;
        $isBookmarkedByUser = false;

        if (isset($request) && $request->has('usid')) {
            $userId = $request->input('usid');
            $isLikedByUser = $software->likes()->where('user_id', $userId)->exists();
            $isBookmarkedByUser = $software->bookmarks()->where('user_id', $userId)->exists();
        }
        $author = $software->author;
        $authorLogo = $this->getAuthorLogo($author);
        return [
            'id' => $software->id,
            'title' => $software->post_title,
            'content' => $software->post_content,
            'excerpt' => $software->post_excerpt,
            'author' => [
                'author_id' => $author->id ?? '',
                'author_name' => $author->full_name ?? '',
                'author_email' => $author->email ?? '',
                'author_type' => $author->usertype->user_type_name ?? '',
                'author_logo' => $authorLogo
            ],
            'cover_image' => $software->cover_image ?? null,
            'created_at' => $software->post_date ?? null,
            'modified_at' => $software->post_modified ?? null,
            'post_status' => $software->post_status ?? null,
            'type' => ($software->softwaretypes)
                ? $software->softwaretypes->pluck('software_type_name')
                : null,
            'template' => $software->template ?? null,
            'extra_content' => $software->extra_content ?? null,
            'metadata' => $softwareMetadataToSend ?? null,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
        ];
    }

    public function createSoftwarePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string',
            'post_content' => 'required|string',
            'post_excerpt' => 'sometimes|string',
            'post_status' => 'required|string',
            'author_id' => 'required|integer|exists:users,id',
//            'cover_image' => 'sometimes|string',
//            'softwaretype_id' => 'required|integer|exists:softwaretypes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $software = Software::create($request->all());

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
                $result = $this->softwareModelToJson($software,$softwareMetadataToSend, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
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
                $result = $this->softwareModelToJson($software, $softwareMetadataToSend, $request);
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
        $data = $this->softwareModelToJson($software,$softwareMetadataToSend, $request);
        return response()->json($data);

    }
}
