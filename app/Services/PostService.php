<?php

namespace App\Services;

use App\Helpers\StatusHelpers;
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
                    ->where('meta_key', 'userAvatar')
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
            $posts = $model::where('status', StatusHelpers::DRAFT)
                ->where('author_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->get();
        } else {

            $posts = $model::where('status', StatusHelpers::DRAFT)
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

    public function adviceModelToJson($advice, $request, $withContent = true): array
    {

        $userId = Auth::user()->id;
        $isLikedByUser = $advice->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $advice->bookmarks()->where('user_id', $userId)->exists();
        $author = $advice->author;

        $authorLogo = $this->getAuthorLogo($author);

        return [
            'id' => $advice->id,
            'title' => $advice->title,
            'content' => $withContent ? $advice->content : NULL,
            'excerpt' => $advice->excerpt,
            'author' => [
                'author_id' => $author->id,
                'author_name' => $author->full_name,
                'author_logo' => $authorLogo
            ],
            'cover_image' => ($advice->cover_image) ?: NULL,
            'template' => ($advice->template) ?: NULL,
            'extra_content' =>$withContent ?(($advice->extra_content) ?: NULL) : NULL,
            'created_at' => $advice->created_at,
            'updated_at' => $advice->updated_at,
            'status' => $advice->status,
            'type' => ($advice->advice_types) ? $advice->advice_types->pluck('advice_type_name') : NULL,
            'post_type' => 'advice',
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $advice->tags->pluck('name'),
            'labels' => $advice->labels->map(function ($label) {
                return [
                    'label_id' => $label['id'],
                    'label_value' => $label['value'],
                    'label_description' => $label['description'],
                    'label_type' => $label['type'],
                ];
            }),
//            'labels' => $groupedLabels
        ];
    }


    public function softwareModelToJson($software, $schoolMetadata = NULL, $request = NULL,  $withContent = true): array
    {

        $userId = Auth::user()->id;
        $isLikedByUser = $software->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $software->bookmarks()->where('user_id', $userId)->exists();
        $author = $software->author;
        $authorLogo = $this->getAuthorLogo($author);
        return [
            'id' => $software->id,
            'title' => $software->title,
            'content' => $withContent? $software->content : NULL,
            'excerpt' => $software->excerpt,
            'author' => [
                'author_id' => $author->id ?? '',
                'author_name' => $author->full_name ?? '',
                'author_email' => $author->email ?? '',
                'author_type' => $author->usertype->user_type_name ?? '',
                'author_logo' => $authorLogo
            ],
            'cover_image' => $software->cover_image ?? null,
            'created_at' => $software->created_at ?? null,
            'updated_at' => $software->updated_at ?? null,
            'status' => $software->status ?? null,
            'type' => ($software->software_types)
                ? $software->software_types->pluck('software_type_name')
                : null,
            'post_type' => 'software',
            'template' => $software->template ?? null,
            'extra_content' => $withContent ? ($software->extra_content ?? NULL) : NULL,
            'how_to_access' => $withContent ? (($software->how_to_access) ?: NULL) : NULL,
            'metadata' => $softwareMetadataToSend ?? null,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $software->tags->pluck('name'),
            'labels' => $software->labels->map(function ($label) {
                return [
                    'label_id' => $label['id'],
                    'label_value' => $label['value'],
                    'label_description' => $label['description'],
                    'label_type' => $label['type'],
                ];
            }),
        ];
    }

    public function eventModelToJson($event, $request,  $withContent = true): array
    {
        $author = $event->author;
        $author_logo = $this->getAuthorLogo($author);

        $userId = Auth::user()->id;
        $isLikedByUser = $event->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $event->bookmarks()->where('user_id', $userId)->exists();
        return [
            'id' => $event->id,
            'title' => $event->title,
            'content' => $withContent ? $event->content : NULL,
            'excerpt' => $event->excerpt,
            'location' => json_decode($event->location),
            'author' => [
                'author_id' => $event->author->id,
                'author_name' => $event->author->full_name,
                'author_email' => $event->author->email,
                'author_type' => $event->author->usertype->user_type_name ?? '',
                'author_logo' => $author_logo
            ],
            'cover_image' => ($event->cover_image) ?? NULL,
            'start_date' => $event->start_date,
            'end_date' => $event->end_date,
            'status' => $event->status,
            'type' => ($event->event_type) ? $event->event_type->event_type_name : NULL,
            'format' => ($event->event_format) ? $event->event_format->event_format_name : NULL,
            'post_type' => 'event',
            'created_at' => $event->created_at,
            'updated_at' => $event->updated_at,
            'extra_content' => $withContent ? (($event->extra_content) ?? NULL) : NULL,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $event->tags->pluck('name'),
            'labels' => $event->labels->map(function ($label) {
                return [
                    'label_id' => $label['id'],
                    'label_value' => $label['value'],
                    'label_description' => $label['description'],
                    'label_type' => $label['type'],
                ];
            }),
        ];
    }
}
