<?php

namespace App\Helpers;

class NotificationResource
{
    public $id;
    public $title;
    public $author;
    public $type;
    public $action;

    public function __construct($id, $title, $author, $type, $action)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->type = $type;
        $this->action = $action;
    }
}