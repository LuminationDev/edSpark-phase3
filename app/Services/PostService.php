<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Partner;
use App\Models\Usermeta;
use Illuminate\Support\Facades\Auth;

class PostService
{
    private function getAuthorLogo($author)
    {
        if (!$author || !$author->usertype) {
            return '';
        }

        $authorType = $author->usertype->user_type_name;
        $authorId = $author->id;

        switch ($authorType) {
            case 'user':
                $avatar = Usermeta::where('user_id', $authorId)
                    ->where('user_meta_key', 'userAvatar')
                    ->first();
                return $avatar->user_meta_value ?? '';

            case 'partner':
                $partnerLogo = Partner::where('user_id', $authorId)->first();
                return $partnerLogo ? json_decode($partnerLogo->logo, true) : '';

            default:
                return '';
        }
    }

    public function fetchUserDraftPosts($model, $request, $callback): array
    {
        $userId = $request->user_id ?? Auth::user()->id;
        if ($model === Event::class) {
            $posts = $model::where('event_status', 'Draft')
                ->where('author_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {

            $posts = $model::where('post_status', 'Draft')
                ->where('author_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        $data = [];
        foreach ($posts as $post) {
            $data[] = $callback($post, $request);
        }

        return $data;
    }

    public function adviceModelToJson($advice, $request): array
    {

        $userId = Auth::user()->id;
        $isLikedByUser = $advice->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $advice->bookmarks()->where('user_id', $userId)->exists();

        return [
            'id' => $advice->id,
            'title' => $advice->post_title,
            'content' => $advice->post_content,
            'excerpt' => $advice->post_excerpt,
            'author' => [
                'author_id' => $advice->author->id,
                'author_name' => $advice->author->full_name
            ],
            'cover_image' => ($advice->cover_image) ? $advice->cover_image : NULL,
            'template' => ($advice->template) ? $advice->template : NULL,
            'extra_content' => ($advice->extra_content) ? $advice->extra_content : NULL,
            'created_at' => $advice->post_date,
            'modified_at' => $advice->post_modified,
            'status' => $advice->post_status,
            'type' => ($advice->advicetypes) ? $advice->advicetypes->pluck('advice_type_name') : NULL,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $advice->tags->pluck('name')
        ];
    }


    public function softwareModelToJson($software, $schoolMetadata = NULL, $request = NULL): array
    {

        $userId = Auth::user()->id;
        $isLikedByUser = $software->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $software->bookmarks()->where('user_id', $userId)->exists();
        $author = $software->author;
        $authorLogo = $this->getAuthorLogo($author);
        return [
            'id' => $software->id,
            'title' => $software->post_title,
            'content' => $software->post_content,
            'excerpt' => $software->post_excerpt,
            'author' => [
                'author_id' => $author->id ?? '',
                'author_name' => $author->full_name ?? '',
                'author_email' => $author->email ?? '',
                'author_type' => $author->usertype->user_type_name ?? '',
                'author_logo' => $authorLogo
            ],
            'cover_image' => $software->cover_image ?? null,
            'created_at' => $software->post_date ?? null,
            'modified_at' => $software->post_modified ?? null,
            'post_status' => $software->post_status ?? null,
            'type' => ($software->softwaretypes)
                ? $software->softwaretypes->pluck('software_type_name')
                : null,
            'template' => $software->template ?? null,
            'extra_content' => $software->extra_content ?? null,
            'metadata' => $softwareMetadataToSend ?? null,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
        ];
    }

    public function eventModelToJson($event, $request): array
    {
        $author = $event->author;
        $author_logo = $this->getAuthorLogo($author);

        $userId = Auth::user()->id;
        $isLikedByUser = $event->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $event->bookmarks()->where('user_id', $userId)->exists();
        return [
            'id' => $event->id,
            'title' => $event->event_title,
            'content' => $event->event_content,
            'excerpt' => $event->event_excerpt,
            'location' => json_decode($event->event_location),
            'author' => [
                'author_id' => $event->author->id,
                'author_name' => $event->author->full_name,
                'author_email' => $event->author->email,
                'author_type' => $event->author->usertype->user_type_name,
                'author_logo' => $author_logo
            ],
            'cover_image' => ($event->cover_image) ?? NULL,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'status' => $event->event_status,
            'type' => ($event->eventtype) ? $event->eventtype->event_type_name : NULL,
            'created_at' => $event->created_at,
            'updated_at' => $event->updated_at,
            'extra_content' => ($event->extra_content) ?? NULL,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $event->tags->pluck('name')
        ];
    }
}