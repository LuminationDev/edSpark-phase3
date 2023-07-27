<?php

namespace App\Http\Controllers;

use App\Helpers\OutputHelper;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RsvpController extends Controller
{
    public function fetchRsvpByEventId($event_id)
    {
        $rsvps = RSVP::where('event_id', $event_id)->get();

        $data = [];

        foreach ($rsvps as $rsvp) {
            $user = User::find($rsvp->user_id);

            $result = [
                'rsvp_id' => $rsvp->id,
                'user_id' => $rsvp->user_id,
                'full_name' => $user->full_name,
                'number_of_guests' => $rsvp->number_of_guests,
                'school_name' => $rsvp->school_name,
                'user' => [
                    'user_id' => $user->id,
                    'full_name' => $user->full_name,
                ],
            ];

            $data[] = $result;
        }

        return response()->json($data);
    }

    // public function addRsvpToEvent(Request $request)
    // {
    //     try {
    //         $data = $request->all();

    //         // Validate the request data as needed
    //         // Example validation rules:
    //         $rules = [
    //             'user_id' => 'required|integer',
    //             'event_id' => 'required|integer',
    //             'full_name' => 'required|string',
    //             'school_name' => 'required|string',
    //             'number_of_guests' => 'required|integer',
    //         ];

    //         $validator = Validator::make($data, $rules);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }


    //         $existingRsvp = RSVP::where('event_id', $data['event_id'])
    //             ->where('user_id', $data['user_id'])
    //             ->first();

    //         if ($existingRsvp) {
    //             return response()->json(['error' => 'RSVP already exists for the user and event.'], 400);
    //         }
    //         $event = Event::find($data['event_id']);
    //         // Create a new RSVP entry
    //         $rsvp = new RSVP();
    //         $rsvp->user_id = $data['user_id'];
    //         $rsvp->event_type = $event->eventtype->event_type_name;
    //         $rsvp->event_id = $data['event_id'];
    //         $rsvp->full_name = $data['full_name'];
    //         $rsvp->school_name = $data['school_name'];
    //         $rsvp->number_of_guests = $data['number_of_guests'];
    //         $rsvp->save();

    //         return response()->json(['message' => 'RSVP added successfully'], 200);
    //     } catch (\Exception $e) {
    //         // Handle any exceptions that occur during RSVP creation
    //         return response()->json(['error' => $e], 500);
    //     }
    // }

    public function addRsvpToEvent(Request $request)
{
    try {
        $data = $request->all();

        // Validate the request data as needed
        // Example validation rules:
        $rules = [
            'user_id' => 'required|integer',
            'event_id' => 'required|integer',
            'full_name' => 'required|string',
            'school_name' => 'required|string',
            'number_of_guests' => 'required|integer',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $event = Event::find($data['event_id']);

        // Update or create a new RSVP entry
        $rsvpData = [
            'user_id' => $data['user_id'],
            'event_type' => $event->eventtype->event_type_name,
            'event_id' => $data['event_id'],
            'full_name' => $data['full_name'],
            'school_name' => $data['school_name'],
            'number_of_guests' => $data['number_of_guests'],
        ];

        RSVP::updateOrCreate(
            ['user_id' => $data['user_id'], 'event_id' => $data['event_id']],
            $rsvpData
        );

        return response()->json(['message' => 'RSVP added or updated successfully'], 200);
    } catch (\Exception $e) {
        // Handle any exceptions that occur during RSVP creation or update
        return response()->json(['error' => $e], 500);
    }
}

    public function removeRsvpFromEvent(Request $request)
    {
        try {
            $data = $request->all();

            // Validate the request data as needed
            // Example validation rules:
            $rules = [
                'event_id' => 'required|integer',
                'user_id' => 'required|integer',
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            // Find and delete the RSVP entry
            $rsvp = RSVP::where('event_id', $data['event_id'])
                ->where('user_id', $data['user_id'])
                ->first();

            if (!$rsvp) {
                return response()->json(['error' => 'RSVP not found'], 404);
            }

            $rsvp->delete();

            return response()->json(['message' => 'RSVP removed successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during RSVP removal
            return response()->json(['error' => 'An error occurred while removing RSVP'], 500);
        }
    }

    public function checkIfUserRsvped(Request $request)
    //Check if user is RSVPED or isOwner
    {
        try {
            $data = $request->all();

            // Validate the request data as needed
            // Example validation rules:
            $rules = [
                'event_id' => 'required|integer',
                'user_id' => 'required|integer',
            ];

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            // check isOwner
            $user = User::find($data['user_id']);
            $event = Event::find($data['event_id']);
            if ($event->author_id == $user->id) {
                $isOwner = 'true';
                $guestSum = Rsvp::where('event_id', $data['event_id'])->sum('number_of_guests');
            } else {
                $isOwner = 'false';
            }


            if (!(RSVP::where('event_id', $data['event_id'])->where('user_id', $data['user_id'])->exists())) {
//                return response()->json(['rsvped' => 'false']);
                $isRsvped = 'false';

            } else {
                $rsvp = RSVP::where('event_id', $data['event_id'])
                    ->where('user_id', $data['user_id'])->first();
                $isRsvped = 'true';
            }

            $result = [
                'rsvped' => $isRsvped,
                'isOwner' => $isOwner,
                'owner_info' => $isOwner == 'true' ? [
                    'total_guest' => $guestSum ?? 0
                ] : [],
                'rsvp_info' => (isset($rsvp) ? [
                    'full_name' => $rsvp->full_name,
                    'school_name' => $rsvp->school_name,
                    'number_of_guests' => $rsvp->number_of_guests,
                ] : [])
            ];
            return response()->json($result);

        } catch (\Exception $e) {
            // Handle any exceptions that occur during RSVP check
            return response()->json(['error' => 'An error occurred while checking RSVP', 'message' => $e], 500);
        }

    }

}
