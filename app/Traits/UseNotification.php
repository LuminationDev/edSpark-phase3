<?php

namespace App\Traits;

use App\Helpers\NotificationResource;
use App\Notifications\ResourceCreated;

trait UseNotification{
    protected function sendNotification($user_to_notify,$resource_id, $resource_title, $author_id, $notification_resource_type, $notification_action_type){
        $notificationObject = new NotificationResource($resource_id, $resource_title, $author_id, $notification_resource_type, $notification_action_type);
        $user_to_notify->notify(new ResourceCreated($notificationObject));
    }
}