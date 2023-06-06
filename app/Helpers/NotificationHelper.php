<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Models\Notification;


class NotificationHelper
{
    public static function create(int $userId, string $type, string $data)
    {
        try{
            $result = [
                'type' => $type,
                'user_id' => $userId,
                'data' => $data
            ];

            Notification::insert($result);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

    }
}
