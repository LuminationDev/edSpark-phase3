<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResourceCreated extends Notification
{
    use Queueable;
    private $resource;
    /**
     * Create a new notification instance.
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'resource_id' => $this->resource->id,
            'title' => $this->resource->title,
            'author' => $this->resource->author,
            'type' => $this->resource->type,
            'action' => $this->resource->action
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
