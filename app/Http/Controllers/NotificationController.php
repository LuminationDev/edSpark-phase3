<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function formatNotification($notifications)
    {
        $formattedNotifications = [];

        foreach ($notifications as $notification) {
            $formattedNotification = [
                'id' => $notification->id,
                'title' => $notification->data['data']['title'] ?? null,
                'author' => $notification->data['data']['author'],
                'type' => $notification->data['data']['type'],
                'action' => $notification->data['data']['action']
            ];

            $formattedNotifications[] = $formattedNotification;
        }

        return $formattedNotifications;
    }

    public function getNotifications(Request $request, $userId){
        $user = User::find($userId);
        if(!$user){
            return ResponseService::error("User not found");
        }
        $userNotifications = $user->unreadNotifications->take(6);
        $formattedNotifications = $this->formatNotification($userNotifications);

        return ResponseService::success('Notification fetched successfully', $formattedNotifications);

    }

    public function getAllNotifications(Request $request, $userId){
        $user = User::find($userId);
        if(!$user){
            return ResponseService::error("User not found");
        }
        $userNotifications = $user->notifications();
        $formattedNotifications = $this->formatNotification($userNotifications);

        return ResponseService::success('All notifications fetched successfully', $formattedNotifications);
    }





}
