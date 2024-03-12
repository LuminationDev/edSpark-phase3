<?php

namespace App\Helpers;

class NotificationResource
{
    public $id;
    public $title;
    public $author_id;
    public $type;
    public $action;

    public function __construct($id, $title, $author, $type, $action)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author_id = $author;
        $this->type = $type;
        $this->action = $action;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author_id' => $this->author_id,
            'type' => $this->type,
            'action' => $this->action
        ];
    }
}