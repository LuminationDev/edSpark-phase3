<?php

namespace App\Http\Controllers;

use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Http\Middleware\ResourceAccessControl;
use App\Models\Advice;
use App\Models\Eventmeta;
use App\Models\Eventtype;
use App\Services\PostService;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{

    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware(ResourceAccessControl::class . ':partner,handleFetchEventPosts,fetchEventPostById');

    }

    public function createEventPost(Request $request): \Illuminate\Http\JsonResponse
    {
        if (strtolower($request->input('event_status')) === 'draft') {
            $validator = Validator::make($request->all(), [
                'event_title' => 'required|string',
                'event_content' => 'required|string',
            ]);
        } else if (strtolower($request->input('event_status')) === 'pending') {
            $validator = Validator::make($request->all(), [
                'event_title' => 'required|string',
                'event_content' => 'required|string',
                'event_excerpt' => 'sometimes|string',
                'event_location' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'event_status' => 'required|string',
                'author_id' => 'required|integer|exists:users,id',
                'eventtype_id' => 'required|integer|exists:event_types,id',
            ]);
        }
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $eventData = $request->except('eventtype_id');
        $event = Event::create($eventData);

        // Attach the event type to the created event
        if ($request->has('eventtype_id')) {
            $event->eventtype()->associate($request->input('eventtype_id'))->save();
        }
        if ($request->has('tags')) {
            $event->attachTags($request->input('tags'));
        }
        if ($request->has('labels')) {
            $allLabelIds = [];
            $inputArray = $request->input('labels');
            foreach ($inputArray as $subArray) {
                foreach ($subArray as $item) {
                    $allLabelIds[] = $item['id'];
                }
            }
            $event->labels()->attach($allLabelIds);
        }
        // archive draft
        if ($request->input('existing_id') != 0 && strtolower($request->input('content_origin')) === 'draft') {
            $existingEvent = Event::find($request->input('existing_id'));

            if ($existingEvent) {
                $existingEvent->update(['post_status' => 'Archived']);
            }
        }

        return response()->json(['message' => 'Event created successfully!', 'event' => $event], 201);

    }

    public function handleFetchEventPosts(Request $request): \Illuminate\Http\JsonResponse
    {
        if (Auth::user()->isPartner()) {
            return $this->fetchUserEventPosts($request);
        } else {
            return $this->fetchAllEventPosts($request);
        }
    }

    public function fetchAllEventPosts(Request $request): \Illuminate\Http\JsonResponse
    {
        // Get the current date without the time component
        $currentDate = now()->startOfDay();

        $events = Event::where('event_status', 'Published')
            ->where('end_date', '>=', $currentDate)
            ->where('event_status', 'Published')
            ->get();

        $data = [];

        foreach ($events as $event) {
            $result = $this->postService->eventModelToJson($event, $request);
            $data[] = $result;
        }

        return response()->json($data);
    }

    public function fetchUserEventPosts(Request $request): JsonResponse
    {
        try {
            $userId = Auth::user()->id;
            $events = Event::where('event_status', 'Published')
                ->where('author_id', $userId)  // Filter by partner (author) ID
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($events as $event) {

                $result = $this->postService->eventModelToJson($event, $request);
                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function fetchEventPostById(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|gt:0',
            'preview' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ResponseService::error('Invalid request parameters', 400);
        }

        $id = $request->input('id');
        if (RoleHelpers::has_minimum_privilege(UserRole::MODERATOR)) {
            // Find the advice by ID
            $event = Event::find($id);
        } else {
            $event = Event::where('id', $id)->where('event_status', "Published")->first();
        }

        if (!$event) {
            return ResponseService::error('Event not found', 404);
        }
        $data = $this->postService->eventModelToJson($event, $request);


        return ResponseService::success("Successfully retrieved event", $data);
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

    public function fetchEventTypes(Request $request): \Illuminate\Http\JsonResponse
    {
        $eventTypes = Eventtype::all()
            ->map(function ($eventType) {
                return [
                    'id' => $eventType->id,
                    'name' => $eventType->event_type_name,
                    'value' => $eventType->event_type_value
                ];
            })
            ->toArray();

        return response()->json($eventTypes);
    }

    public function addOrEditEMSLink(Request $request): \Illuminate\Http\JsonResponse
    {
        $eventId = $request->input('event_id');
        $emsLink = $request->input('ems_link');

        $event = Event::find($eventId);
        $user = Auth::user();


//        if (strtolower($user->role->role_name) !== 'partner' || $event->author->id != $user->id) {
//            return ResponseService::error('User is not a partner', 'Forbidden', 403);
//        }
        if (!isset($emsLink)) {
            return ResponseService::error('EMS Link is not provided', "Missing Data", 422);
        }
        if (!$event->isActive()) {
            return ResponseService::error('Event has ended', "Ended Event", 400);
        }

        $event_link = Eventmeta::updateOrCreate(
            [
                'event_id' => $eventId,
                'event_meta_key' => 'ems_link',
            ],
            [
                'event_meta_value' => $emsLink,
            ]
        );
        return ResponseService::success('EMS link updated successfully.', $event_link);
    }

    public function fetchEMSLink($eventId): \Illuminate\Http\JsonResponse
    {
        if (!isset($eventId)) {
            return ResponseService::error("Event ID is required", 422);
        }
        $event = Event::find($eventId);
        $user = Auth::user();
        // Check if the 'event_recording' meta exists for the given event ID
        $eventRecordingMeta = Eventmeta::where('event_id', $eventId)
            ->where('event_meta_key', 'ems_link')
            ->first();

        if ($eventRecordingMeta) {
            $recordingLink = $eventRecordingMeta->event_meta_value;
            $isOwner = false;
            if ($event->author_id == $user->id) {
                $isOwner = 'true';
            }
            $result = ['ems_link' => $recordingLink , 'is_owner' => $isOwner];
            return ResponseService::success('Event EMS link found', $result);
        } else {
            return ResponseService::error('Event EMS Link not found', "NOT FOUND", 404);
        }
    }

}
