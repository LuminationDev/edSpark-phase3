<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\Bookmark;
use App\Models\User;
use App\Models\Advice;
use App\Models\Software;
use App\Models\Hardware;
use App\Models\Event;
use App\Models\School;

class PostHelper
{
    public static function getPostTitle(int $post_id, string $post_type): array
    {
        $postTitle = '';
        $coverImage = '';

        switch ($post_type) {
            case 'school':
                $school = School::where('id', $post_id)->first();
                if ($school) {
                    $postTitle = $school->name;
                    $coverImage = $school->cover_image;
                }
                break;

            case 'advice':
                $advice = Advice::where('id', $post_id)->first();
                if ($advice) {
                    $postTitle = $advice->title;
                    $coverImage = $advice->cover_image;
                }
                break;

            case 'software':
                $software = Software::where('id', $post_id)->first();
                if ($software) {
                    $postTitle = $software->title;
                    $coverImage = $software->cover_image;
                }
                break;

            case 'hardware':
                $hardware = Hardware::where('id', $post_id)->first();
                if ($hardware) {
                    $postTitle = $hardware->product_name;
                    $coverImage = $hardware->cover_image;
                }
                break;

            case 'event':
                $event = Event::where('id', $post_id)->first();
                if ($event) {
                    $postTitle = $event->event_title;
                    $coverImage = $event->cover_image;
                }
                break;
            default:
                break;
        }
        return [
            'title' => $postTitle,
            'cover_image' => $coverImage
        ];
    }


}
