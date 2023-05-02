<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get All Notifications
     *
     */
    public function getAllNotifications(Request $request)
    {
        $data = $request->all();
        $userId = $data['userId'];
        $notifications = Notification::where('user_id', '=', $userId)
                                ->where('status', '=', 0)
                                ->get();
        $dataToSend = [];
        $count = 0;
        if ($notifications) {
            $count += count($notifications);
            foreach ($notifications as $notification) {
                $result = [
                    'id' => $notification->id,
                    'type' => ($notification->type) ? $notification->type : NULL,
                    'data' => $notification->data,
                    'status' => $notification->status,
//                    'read_at' => ($notification->read_at) ? $notification->read_at : NULL
                ];
                $dataToSend[] = $result;
            }
        }
        return response()->json([
            "data" => $dataToSend,
            "count" => $count
        ]);

    }

    /**
     * Get single notification
     */
    public function getSingleNotification(Request $request)
    {
        $data = $request->all();
        $notificationId = $data['notificationId'];
        $notification = Notification::findOrFail($notificationId);
        $read_at = Carbon::now();
        if($notification) {
            $notification->update([
                'read_at' => $read_at,
                'status' => 1
            ]);
            $result = [
                'id' => $notification->id,
                'type' => ($notification->type) ? $notification->type : NULL,
                'data' => $notification->data,
                'status' => $notification->status,
                'read_at' => ($notification->read_at) ? $notification->read_at : NULL
            ];

            return response()->json($result);
        }
    }

    /**
     * Get notifications by type
     */
    public function getNotificationByType(Request $request)
    {
        $data = $request->all();
        $userId = $data['userId'];
        $type = $data['type'];
        $notifications = Notification::where('user_id', '=', $userId)
                                        ->where('type', '=', $type)
                                        ->where('status', '=', 0)
                                        ->get();
        $dataToSend = [];
        $count = 0;
        if ($notifications) {
            $count += count($notifications);
            foreach ($notifications as $notification) {
                $result = [
                    'id' => $notification->id,
                    'type' => ($notification->type) ? $notification->type : NULL,
                    'data' => $notification->data,
                    'status' => $notification->status,
//                    'read_at' => ($notification->read_at) ? $notification->read_at : NULL
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
