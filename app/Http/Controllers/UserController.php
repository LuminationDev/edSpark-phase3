<?php

namespace App\Http\Controllers;

use App\Models\Advice;
use App\Models\Event;
use App\Models\Software;
use App\Services\PostService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Usermeta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;


    }

    public function getAllUserDraftPosts(Request $request): JsonResponse
    {
        try {
            $adviceData = $this->postService->fetchUserDraftPosts(Advice::class, $request, [$this->postService, 'adviceModelToJson']);
            $softwareData = $this->postService->fetchUserDraftPosts(Software::class, $request, [$this->postService, 'softwareModelToJson']);
            $eventData = $this->postService->fetchUserDraftPosts(Event::class, $request, [$this->postService, 'eventModelToJson']);
            $data = [
                'advice' => $adviceData,
                'software' => $softwareData,
                'event' => $eventData
            ];
            return ResponseService::success("Success", $data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchCurrentUser(): JsonResponse
    {
        try {
            $user = User::find(Auth::user()->id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $userData = $this->getUserData($user);
            $userMetaData = $this->getUserMetadataById($user->id);

            $response = array_merge($userData, ['metadata' => $userMetaData]);

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching user data', 'error' => $e->getMessage()], 500);
        }
    }

    public function fetchUserByEmail($email): JsonResponse
    {
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'message' => "User does not exist",
                    'status' => 404
                ]);
            }

            $userData = $this->getUserData($user);
            $userMetaData = $this->getUserMetadataById($user->id);

            return response()->json([
                'message' => "User exists",
                'user' => array_merge($userData, ['metadata' => $userMetaData]),
                'isFirstVisit' => false, // Always false in current logic
                'status' => 200
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => "Error fetching the user",
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    private function getUserData($user): array
    {
        return [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'display_name' => $user->display_name ?: NULL,
            'email' => $user->email,
            'site_id' => $user->site_id ?: NULL,
            'status' => $user->status,
            'role' => $user->role ? $user->role->role_name : NULL,
            'permissions' => $user->role ? $user->role->permissions->pluck('permission_name') : NULL,
            'site' => [
                'site_name' => $user->site ? $user->site->site_name : null,
                'site_id' => $user->site_id ?: NULL,
            ]
        ];
    }

    private function getUserMetadataById($userId): array
    {
        $userMetaData = Usermeta::where('user_id', $userId)->get();
        $metaDataArray = [];

        foreach ($userMetaData as $meta) {
            $metaDataArray[] = [
                'user_meta_key' => $meta->user_meta_key,
                'user_meta_value' => explode(', ', $meta->user_meta_value)
            ];
        }

        return $metaDataArray;
    }

    public function createUser(Request $request): \Illuminate\Http\Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        if ($request->isMethod('post')) {
            try {
                $user = User::where('email', $request->email)->first();

                if ($user) {
                    return response('User Already exist', 403);
                }

                $userId = $this->handleMainDataCreation($request->all());
                $this->handleMetadata($request->metadata, $userId);
                $imageUrl = $this->handleUserAvatar($request->userAvatar, $userId);

                return response()->json([
                    'message' => "User added successfully",
                    'uid' => $userId,
                    'avatarUrl' => $imageUrl,
                    'status' => 200
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => "Error creating the user",
                    'error' => $e->getMessage(),
                    'status' => 500
                ]);
            }
        }

        return response()->json(['message' => 'Invalid request method', 'status' => 400]);
    }

    private function handleMainDataCreation($data)
    {
        $dataToInsert = [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'display_name' => $data['display_name'],
            'status' => 'Active',
            'role_id' => $data['role_id'],
            'site_id' => intval($data['site_id']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        return User::insertGetId($dataToInsert);
    }

    public function updateFirstTimeVisitUser(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('post')) {

            try {
                $user = User::where('email', $request->email)->firstOrFail();

                $this->handleMainData($request->all(), $user);
                $this->handleMetadata($request->metadata, $user);
                $imageUrl = $this->handleUserAvatar($request->userAvatar, $user);

                return response()->json([
                    'message' => "First time user updated successfully",
                    'uid' => $user->id,
                    'avatarUrl' => $imageUrl,
                    'status' => 200
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => "Error updating the user",
                    'error' => $e->getMessage(),
                    'status' => 500
                ]);
            }
        }

        return response()->json(['message' => 'Invalid request method', 'status' => 400]);
    }

    private function handleMainData($data, $user)
    {
        $dataToUpdate = [
            'full_name' => $data['full_name'],
            'display_name' => $data['display_name'],
            'status' => 'Active',
            'role_id' => $data['role_id'],
            'site_id' => intval($data['site_id']),
            'isFirstTimeVisit' => FALSE,
            'updated_at' => Carbon::now()
        ];
        $user->update($dataToUpdate);
    }

    private function handleMetadata($metadata, $user)
    {
        $metadata = json_decode($metadata);
        foreach ($metadata as $metakey => $metavalue) {
            $meta = Usermeta::firstOrNew(['user_id' => $user->id, 'user_meta_key' => $metakey]);
            $meta->user_meta_value = (is_string($metavalue)) ? $metavalue : implode(', ', $metavalue);
            $meta->save();
        }
    }

    private function handleUserAvatar($userAvatar, $user): ?string
    {
        if ($userAvatar) {
            $prefix = "edpsark-user";
            $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $userAvatar) . '.' . $userAvatar->getClientOriginalExtension();
            $userAvatar->storeAs('public/uploads/user', $imgName);
            $imageUrl = "uploads\/user\/" . $imgName;

            Usermeta::updateOrCreate(
                ['user_id' => $user->id, 'user_meta_key' => 'userAvatar'],
                ['user_meta_value' => $imageUrl]
            );

            return $imageUrl;
        }
        return null;
    }


    public function updateUser(Request $request): JsonResponse
    {
        if ($request->isMethod('post')) {
            try {
                $userId = $request->id;

                if ($request->data) {
                    $this->handleUserDataUpdate($request->data, $userId);
                }

                if ($request->metaData) {
                    $this->handleUserMetaDataUpdate($request->metaData, $userId);
                }

                return response()->json([
                    'message' => "User updated successfully",
                    'uid' => $userId,
                    'status' => 200
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'message' => "Error updating the user",
                    'error' => $e->getMessage(),
                    'status' => 500
                ]);
            }
        }

        return response()->json(['message' => 'Invalid request method', 'status' => 400]);
    }

    private function handleUserDataUpdate($data, $userId)
    {
        User::where('id', '=', $userId)
            ->update([$data['updateField'] => $data['updateValue']]);
    }

    private function handleUserMetaDataUpdate($metaData, $userId)
    {
        $meta = Usermeta::firstOrNew(['user_id' => $userId, 'user_meta_key' => $metaData['updateField']]);
        $meta->user_meta_value = (is_string($metaData['updateValue'])) ? $metaData['updateValue'] : implode(', ', $metaData['updateValue']);
        $meta->save();
    }

    public function getUserMetadata(Request $request)
    {
        if ($request->isMethod('post')) {
            $userMetaDataToSend = [];
            $userId = $request->id;
            $userMetakey = $request->userMetakey;
            $result = Usermeta::where([['user_id', $userId], ['user_meta_key', $userMetakey]])->get();
            if ($result) {
                foreach ($result as $key => $value) {
                    $result = [
                        'user_meta_key' => $value->user_meta_key,
                        'user_meta_value' => $value->user_meta_value
                    ];
                    $userMetaDataToSend[] = $result;
                }
            }
        }
        return response()->json($userMetaDataToSend);
    }

    public function checkEmail(Request $request)
    {
        if (!$request->isMethod('post')) {
            return response()->json([
                "message" => "Invalid request method.",
                "status" => FALSE,
            ], 400);
        }

        $email = $request->email;

        if (empty($email)) {
            return response()->json([
                "message" => "Email is required.",
                "status" => FALSE,
            ], 400);
        }

        $userEmailDetails = User::where('email', '=', $email)
            ->first();

        if (!$userEmailDetails) {
            return response()->json([
                "message" => "Email not found.",
                "status" => FALSE,
            ], 404);
        }

        if ($userEmailDetails->isFirstTimeVisit == 1) {
            return response()->json([
                "message" => "The email exists but not activated",
                "status" => TRUE,
                "isFirstTimeVisit" => TRUE,
                "userdata" => [
                    'user_id' => $userEmailDetails->id,
                    'user_name' => $userEmailDetails->full_name,
                    'user_status' => $userEmailDetails->status
                ],
            ]);
        } else {
            return response()->json([
                "message" => "The email is activated",
                "status" => TRUE,
                "isFirstTimeVisit" => FALSE,
                "userdata" => [
                    'user_id' => $userEmailDetails->id,
                    'user_name' => $userEmailDetails->full_name,
                    'user_status' => $userEmailDetails->status
                ],
            ]);
        }
    }
}
