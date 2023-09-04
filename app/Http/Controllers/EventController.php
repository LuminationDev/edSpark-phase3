<?php

namespace App\Http\Controllers;

use App\Models\Eventmeta;
use App\Models\Partner;
use App\Models\Usermeta;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
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

    private function eventModelToJson($event, $request){
        $author = $event->author;
        $author_logo = $this->getAuthorLogo($author);

        $isLikedByUser = false;
        $isBookmarkedByUser = false;

        if (isset($request) && $request->has('usid')) {
            $userId = $request->input('usid');
            $isLikedByUser = $event->likes()->where('user_id', $userId)->exists();
            $isBookmarkedByUser = $event->bookmarks()->where('user_id', $userId)->exists();
        }
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
            'extra_content' => ($event->extra_content)?? NULL,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'tags' => $event->tags->pluck('name')
        ];
    }


    public function fetchEventPosts(Request $request): \Illuminate\Http\JsonResponse
    {
        $events = Event::where('event_status', 'Published')->get();

        $data = [];

        foreach ($events as $event) {

            $result = $this->eventModelToJson($event, $request);

            $data[] = $result;
        }

        return response()->json($data);

    }

    public function fetchEventPostById(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $event = Event::find($id);
        $data = $this->eventModelToJson($event, $request);
        return response()->json($data);
    }

    public function addEventRecording(Request $request): \Illuminate\Http\JsonResponse
    {
        $eventId = $request->input('event_id');
        $userId = $request->input('user_id');
        $recordingLink = $request->input('recording_link');

        // Check if the user is the author of the event
        $event = Event::find($eventId);
        if ($event->author->id != $userId) {
            return response()->json(['error' => 'You are not authorized to update the event recording.'], 403);
        }

        // Check if the event already has an 'event_recording' meta
        $eventRecordingMeta = Eventmeta::where('event_id', $eventId)
            ->where('event_meta_key', 'event_recording')
            ->first();

        if ($eventRecordingMeta) {
            // Update the existing 'event_recording' meta
            $eventRecordingMeta->event_meta_value = $recordingLink;
            $eventRecordingMeta->save();
        } else {
            // Create a new 'event_recording' meta entry
            $eventRecordingMeta = new Eventmeta([
                'event_id' => $eventId,
                'event_meta_key' => 'event_recording',
                'event_meta_value' => $recordingLink
            ]);
            $eventRecordingMeta->save();
        }

        return response()->json(['message' => 'Event recording updated successfully.']);

    }
    public function checkEventRecording($eventId): \Illuminate\Http\JsonResponse
    {
        // Check if the 'event_recording' meta exists for the given event ID
        $eventRecordingMeta = Eventmeta::where('event_id', $eventId)
            ->where('event_meta_key', 'event_recording')
            ->first();

        if ($eventRecordingMeta) {
            $recordingLink = $eventRecordingMeta->event_meta_value;
            return response()->json(['event_recording' => $recordingLink]);
        } else {
            return response()->json(['error' => 'Event recording not found.'], 404);
        }
    }
}
