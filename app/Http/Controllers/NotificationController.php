<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public static function formatNotification($notifications)
    {
        $formattedNotifications = [];

        foreach ($notifications as $notification) {
            $authorDisplayName = User::find($notification->data['data']['author_id'])->display_name ?? "Name unavailable";
            $formattedNotification = [
                'id' => $notification->id,
                'resource_id' => $notification->data['data']['id'],
                'title' => $notification->data['data']['title'] ?? null,
                'author_name' => $authorDisplayName, // change to display name
                'type' => $notification->data['data']['type'],
                'action' => $notification->data['data']['action'],
                'updated_at' => $notification->updated_at
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

    public function readNotification(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications->find($notificationId);
        if (!$notification) {
            return ResponseService::error("Notification not found");
        }

        $notification->markAsRead();

        return ResponseService::success("Notification marked as read");
    }

    public function readAllNotifications($userId){
        $user = User::find($userId);
        if(!$user){
            return ResponseService::error("User not found");
        }
        $user->unreadNotifications->markAsRead();
        return ResponseService::success("All notifications marked as read");


    }





}
