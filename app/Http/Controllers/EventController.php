<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function fetchEventPosts()
    {
        $events = Event::where('event_status', 'Published')->get();

        $data = [];

        foreach ($events as $event){
            $result = [
                'event_id' => $event->id,
                'event_title' => $event->event_title,
                'event_content' => $event->event_content,
                'event_excerpt' => $event->event_excerpt,
                'author' => ($event->author) ? $event->author->full_name : '',
                'cover_image' => ($event->cover_image) ? $event->cover_image : NULL,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'event_status' => $event->event_status,
                'event_type' => ($event->eventtype) ? $event->eventtype->event_type_name : NULL,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at
            ];

            $data[] = $result;
        }

        return response()->json($data);

    }
}
