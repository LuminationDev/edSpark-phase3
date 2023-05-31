<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Usermeta;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\OutputHelper;

class UserController extends Controller
{
    public function fetchUser($id)
    {

        $user = User::find($id);
        OutputHelper::print("HAHHAHAHAHAAHHA");

        $userMetaData = Usermeta::where('user_id', $id)->get();
        $userMetaDataToSend = [];
        if ($userMetaData) {
            foreach ($userMetaData as $key => $value) {
                $result = [
                    'user_meta_key' => $value->user_meta_key,
                    'user_meta_value' => explode(', ', $value->user_meta_value)
                ];
                $userMetaDataToSend[] = $result;
            }
        }

        $data = [
            'id' => $user->id,
            'full_name' => $user->full_name,
            'display_name' => ($user->display_name) ? $user->display_name : NULL,
            'email' => $user->email,
            'status' => $user->status,
            'role' => ($user->role) ? $user->role->role_name : NULL,
            'permissions' => ($user->role) ? $user->role->permissions->pluck('permission_name') : NULL,
            'metadata' => ($userMetaDataToSend) ? $userMetaDataToSend : NULL,
        ];

        return response()->json($data);
    }

    public function fetchUserByEmail($email)
    {

        $user = User::where('email', $email)->get();
        $error = '';
        $data = [];
        $isFirstVisit = false;
        // dd($user);

        try {
            if (isset($user)) {
                $user_id = $user['id'];

                $userMetaData = Usermeta::where('user_id', $id)->get();
                $userMetaDataToSend = [];
                if ($userMetaData) {
                    foreach ($userMetaData as $key => $value) {
                        $result = [
                            'user_meta_key' => $value->user_meta_key,
                            'user_meta_value' => explode(', ', $value->user_meta_value)
                        ];
                        $userMetaDataToSend[] = $result;
                    }
                }

                $data[] = [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'display_name' => ($user->display_name) ? $user->display_name : NULL,
                    'email' => $user->email,
                    'status' => $user->status,
                    'role' => ($user->role) ? $user->role->role_name : NULL,
                    'permissions' => ($user->role) ? $user->role->permissions->pluck('permission_name') : NULL,
                    'metadata' => ($userMetaDataToSend) ? $userMetaDataToSend : NULL,
                ];

                $isFirstVisit = false;

                // return response()->json($data);
            } else {
                $isFirstVisit = false;
            }

            return response()->json([
                'message' => isset($data) ? "User exists" : "User does not exist",
                'user' => isset($data) ? $data : NULL,
                'isFirstVisit' => $isFirstVisit,
                'error' => $error,
                'status' => 200
            ]);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }


    }

    public function createUser(Request $request)
    {
        $data = $request->all();

        if ($request->isMethod('post')) {
            $user = User::where('email', $data['email'])->get();
            // user already exists
            OutputHelper::print($user);
            if($user){
             return response('User Already exist', 403);
            }

            $error = '';
            if ($data) {
                // Handle Main Data
                try {
                    $dataToInsert = [
                        'full_name' => $data['full_name'],
                        'email' => $data['email'],
                        'display_name' => $data['display_name'],
                        'status' => 'Active',
                        'role_id' => $data['role_id'],
                        'site_id' => $data['site_id'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $userId = User::insertGetId($dataToInsert);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }

                // Handle Metadata
                try {
                    $metadata = json_decode($data['metadata']);
                    try {
                        foreach ($metadata as $metakey => $metavalue){
                            $checkMetaData = Usermeta::where('user_id', '=', $userId)
                                ->where('user_meta_key', '=', $metakey)
                                ->first();

                            if ($checkMetaData) { // IF EXISTS UPDATE
                                $checkMetaData->update([
                                    'user_meta_key' =>  $metakey,
                                    'user_meta_value' => (is_string($metavalue)) ? $metavalue : implode(', ', $metavalue),
                                ]);
                            } else { // IF NOT CREATE A NEW USER META
                                Usermeta::create([
                                    'user_id' => $userId,
                                    'user_meta_key' => $metakey,
                                    'user_meta_value' => (is_string($metavalue)) ? $metavalue : implode(', ', $metavalue)
                                ]);
                            }
                        }
                        // CHECK IN USER META TABLE

                    } catch (Exception $e) {
                        $error = $e->getMessage();
                    }

                } catch (Exception $e) {
                    $error = $e->getMessage();
                }

                // Handle User Avatar
                try {
                    if ($data['userAvatar']) {
                        $userAvatar = $data['userAvatar'];
                        $prefix = "edpsark-user";
                        $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $userAvatar) . '.' . $userAvatar->getClientOriginalExtension();
                        $userAvatar->storeAs('public/uploads/user', $imgName);
                        $imageUrl = "uploads\/user\/" . $imgName;

                        $result = [
                            'user_id' => $userId,
                            'user_meta_key' => 'userAvatar',
                            'user_meta_value' => $imageUrl,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        Usermeta::insert($result);
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }

            }

            return response()->json([
                'message' => "User added successfully",
                'uid' => $userId,
                'avatarUrl' => $imageUrl,
                'error' => $error,
                'status' => 200
            ]);
        }
    }

    public function updateUser(Request $request)
    {
        if ($request->isMethod('post')) {

            $userId = $request->id;
            $data = $request->data;
            $metaData = $request->metaData;
            $updatedData = [];
            $updatedMetaData = [];

            $error = '';

            /**
             * If Data exists
             */
            if ($data) {

                try {
                    User::where('id', '=', $userId)
                        ->update([$data['updateField'] => $data['updateValue']]);

                    $updatedUser = User::find($userId);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }

            /*
             * If MetaData exists
             */
            if ($metaData) {

                try {
                    // CHECK IN USER META TABLE
                    $checkMetaData = Usermeta::where('user_id', '=', $userId)
                        ->where('user_meta_key', '=', $metaData['updateField'])
                        ->first();

                    if ($checkMetaData) { // IF EXISTS UPDATE
                        $checkMetaData->update([
                            'user_meta_key' => $metaData['updateField'],
                            'user_meta_value' => (is_string($metaData['updateValue'])) ? $metaData['updateValue'] : implode(', ', $metaData['updateValue']),
                        ]);
                    } else { // IF NOT CREATE A NEW USER META
                        Usermeta::create([
                            'user_id' => $userId,
                            'user_meta_key' => $metaData['updateField'],
                            'user_meta_value' => $metaData['updateValue']
                        ]);
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }

            return response()->json([
                'message' => "User updated successfully",
                // 'data' => $updatedData,
                // 'metaData' => $updatedMetaData,
                'error' => $error,
                'status' => 200
            ]);

        }
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
        if ($request->isMethod('post')) {
            $email = $request->email;
            $userEmailDetails = User::where('email', '=', $email)->first();
            if ($userEmailDetails === null) {
                return response()->json([
                    "message" => "The email is not registered",
                    "status" => FALSE,

                ]);
            } else {
                return response()->json([
                    "message" => "The email already exists",
                    "status" => TRUE,
                    "userdata" => [
                        'user_id' => $userEmailDetails->id,
                        'user_name' => $userEmailDetails->full_name,
                        'user_status' => $userEmailDetails->status
                    ],
                ]);
            }
        }
    }
}
