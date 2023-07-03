<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Usermeta;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function fetchEventPosts()
    {
        $events = Event::where('event_status', 'Published')->get();

        $data = [];

        foreach ($events as $event){
            $author_type =  $event->author->usertype->user_type_name;
            $author_id = $event->author->id;
            if($author_type == 'user'){
                $avatar = Usermeta::where('user_id', $author_id)->where('user_meta_key','userAvatar')->first();
                $author_logo = $avatar->user_meta_value;

            } elseif($author_type == 'partner'){
                $author_logo = Partner::where('user_id', $author_id)->first()->logo;
            }else{
                $author_logo = '';
            }
            $result = [
                'event_id' => $event->id,
                'event_title' => $event->event_title,
                'event_content' => $event->event_content,
                'event_excerpt' => $event->event_excerpt,
                'author'=> [
                    'author_id' => $event->author->id,
                    'author_name'=> $event->author->full_name,
                    'author_email'=> $event->author->email,
                    'author_type' => $event->author->usertype->user_type_name,
                    'author_logo' => json_decode($author_logo)
                ],
                'cover_image' => ($event->cover_image) ? $event->cover_image : NULL,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'event_status' => $event->event_status,
                'event_type' => ($event->eventtype) ? $event->eventtype->event_type_name : NULL,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
                'extra_content' => ($event->extra_content) ? $event->extra_content : NULL,
            ];

            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchEventPostById($id)
    {
        $event = Event::find($id);
        $author_type =  $event->author->usertype->user_type_name;
        $author_id = $event->author->id;
        if($author_type == 'user'){
            $avatar = Usermeta::where('user_id', $author_id)->where('user_meta_key','userAvatar')->first();
            $author_logo = $avatar->user_meta_value;

        } elseif($author_type == 'partner'){
            $author_logo = Partner::where('user_id', $author_id)->first()->logo;
        }else{
            $author_logo = '';
        }

        $data = [
            'event_id' => $event->id,
            'event_title' => $event->event_title,
            'event_content' => $event->event_content,
            'event_excerpt' => $event->event_excerpt,
            'author'=> [
                'author_id' => $event->author->id,
                'author_name'=> $event->author->full_name,
                'author_email'=> $event->author->email,
                'author_type' => $event->author->usertype->user_type_name,
                'author_logo' => json_decode($author_logo)
            ],
            'cover_image' => ($event->cover_image) ? $event->cover_image : NULL,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'event_status' => $event->event_status,
            'event_type' => ($event->eventtype) ? $event->eventtype->event_type_name : NULL,
            'created_at' => $event->created_at,
            'updated_at' => $event->updated_at,
            'extra_content' => ($event->extra_content) ? $event->extra_content : NULL,
        ];

        return response()->json($data);
    }
}
