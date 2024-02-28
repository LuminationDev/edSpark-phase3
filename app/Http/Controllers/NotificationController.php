<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use App\Models\User;
class NotificationController extends Controller
{

    public function getUserNotification($userId){
        $user = User::find($userId);
        if(!$user){
            return ResponseService::error("User not found");
        }
        $userNotifications = $user->notifications;
        dd($user->notifications);
        return ResponseService::success('Notification fetched successfully', $userNotifications);

    }
//    /**
//     * Get All Notifications
//     *
//     */
//    public function getAllNotifications($userId)
//    {
//        $notifications = Notification::where('user_id', '=', $userId)
//                                ->where('status', '=', 0)
//                                ->get();
//        $dataToSend = [];
//        $count = 0;
//        if ($notifications) {
//            $count += count($notifications);
//            foreach ($notifications as $notification) {
//                $result = [
//                    'id' => $notification->id,
//                    'type' => ($notification->type) ? $notification->type : NULL,
//                    'data' => $notification->data,
//                    'status' => $notification->status,
////                    'read_at' => ($notification->read_at) ? $notification->read_at : NULL
//                ];
//                $dataToSend[] = $result;
//            }
//        }
//        return response()->json([
//            "result" => $dataToSend,
//            "count" => $count
//        ]);
//
//    }




}
