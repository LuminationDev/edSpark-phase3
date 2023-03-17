<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Usermeta;
use Carbon\Carbon;

class UserController extends Controller
{
    public function fetchUser($id)
    {

        $user = User::find($id);

        $userMetaData = Usermeta::where('user_id', $id)->get();
        $userMetaDataToSend = [];
        if( $userMetaData) {
            foreach($userMetaData as $value){
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
            'metadata' => ($userMetaDataToSend) ? $userMetaDataToSend : NULL
        ];

        return response()->json($data);
    }

    public function createUser(Request $request)
    {
        if ($request->isMethod('post')) {

            $userId = '';
            $data = $request->data;

            $error = '';

            if($data) {
                //save user info into user table
                try{
                    $dataToInsert = [
                        'full_name' => $data['full_name'],
                        'email' => $data['email'],
                        'status' => 'Inactive',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                    $userId = User::insertGetId($dataToInsert);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }

            $metaData = $request->metaData;
            if ($metaData) {
                // save user info into meta table
                $dataToInsert = [];
                try{
                    foreach ($metaData as $key => $value){
                        $result = [
                            'user_id' => $userId,
                            'user_meta_key' => $key,
                            'user_meta_value' => implode(', ', $value),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                        $dataToInsert[] = $result;
                    }
                    Usermeta::insert($dataToInsert);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }

            }

            return response()->json([
                'message' => "User added successfully",
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
            // dd($data['updateField']);

            $updatedData = [];
            $updatedMetaData = [];

            $error = '';

            if ($data) {
                // TODO update user table
                try{
                    User::where('id', '=', $userId)
                        ->update([$data['updateField'] => $data['updateValue']]);
                    $updatedUser = User::find($userId);
                } catch (Exception $e){
                    $error = $e->getMessage();
                }
            }

            if ($metaData) {
                // TODO update user meta table
                try {
                    Usermeta::where('user_id', '=', $userId)
                        ->update([
                            'user_meta_key' => $metaData['updateField'],
                            'user_meta_value' => implode(', ', $metaData['updateValue']),
                        ]);
                    $updatedMetaData = Usermeta::where('user_id', $userId)->get();

                } catch (Exception $e){
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
}
