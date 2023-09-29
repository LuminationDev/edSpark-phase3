<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Advice;
use App\Models\Hardware;
use App\Models\Partner;
use App\Models\Partnermeta;
use App\Models\PartnerProfile;
use App\Models\Software;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PartnerController extends Controller
{

    private function initializePartnerMetadata($partner)
    {
        $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner, 'partner_id', 'partner_meta_key', 'partner_meta_value');

        if (!Metahelper::checkHasMetakey($partnerMeta, 'single_submenu', 'partner_meta_key')) {
            Metahelper::insert($partner->id, ['single_submenu' => 'overview,access'], 'partner_id', 'partner_meta_key', 'partner_meta_value', Partnermeta::class);
        }
        if (!Metahelper::checkHasMetakey($partnerMeta, 'contact_info', 'partner_meta_key')) {
            Metahelper::insert($partner->id, ['contact_info' => '{}'], 'partner_id', 'partner_meta_key', 'partner_meta_value', Partnermeta::class);
        }
    }
    private function getOrCreatePartnerProfile($partner)
    {
        // Fetch the 'Published' status profile for the partner.
        $partnerProfile = $partner->profiles()->where('status', 'Published')->first();

        // If it doesn't exist, create a new one.
        if (!$partnerProfile) {
            $partnerProfile = PartnerProfile::create([
                'partner_id' => $partner->id,
                'user_id' => $partner->user_id,
                'content' => json_encode(new \stdClass()), // Empty object.
                'status' => 'Published',
            ]);
        }

        return $partnerProfile;
    }

    private function partnerModelToJson($partner, $request = NULL)
    {
        $isLikedByUser = false;
        $isBookmarkedByUser = false;

        if (isset($request) && $request->has('usid')) {
            $userId = $request->input('usid');
            $isLikedByUser = $partner->likes()->where('user_id', $userId)->exists();
            $isBookmarkedByUser = $partner->bookmarks()->where('user_id', $userId)->exists();
        }
        $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner, 'partner_id', 'partner_meta_key', 'partner_meta_value');

        // Fetch or create partner profile.
        $partnerProfile = $this->getOrCreatePartnerProfile($partner);

        return [
            'id' => $partner->id,
            'user_id' => $partner->user_id,
            'name' => $partner->name,
            'email' => $partner->email,
            'logo' => json_decode($partner->logo) ?: NULL,
            'cover_image' => json_decode($partner->cover_image) ?: NULL,
            'motto' => $partner->motto,
            'introduction' => $partner->introduction,
            'content' => json_decode($partner->content),
            'metadata' => $partnerMeta,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'profile' => json_decode($partnerProfile->content)
        ];
    }

    public function fetchAllPartners(Request $request)
    {
        try {
            $partners = Partner::all();
            $data = [];

            foreach ($partners as $partner) {
                $this->initializePartnerMetadata($partner);
                $data[] = $this->partnerModelToJson($partner, $request);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchPartnerById(Request $request, $id)
    {
        try {
            $partner = Partner::where('user_id', $id)->first();

            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }
            $this->initializePartnerMetadata($partner); // Ensure metadata exists.
            $data = $this->partnerModelToJson($partner, $request);

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchPartnerResource(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'resource_type' => 'required|string|in:software,hardware,advice',
                'partner_id' => 'required|integer',
            ]);

            // Fetch the resources based on the provided partner_id and resource type
            $resources = [];
            switch ($validatedData['resource_type']) {
                case 'software':
                    $resources = Software::where('author_id', $validatedData['partner_id'])->get();
                    break;
                case 'hardware':
                    $resources = Hardware::where('owner_id', $validatedData['partner_id'])->get();
                    break;
                case 'advice':
                    $resources = Advice::where('author_id', $validatedData['partner_id'])->get();
                    break;
            }

            if (is_array($resources) ? empty($resources) : $resources->isEmpty()) {
                return response()->json(['message' => 'No resources found for the specified partner and resource type'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['resources' => $resources]);

        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function updatePartnerContent(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'content' => 'required', // Validate that content is provided in the request.
                'partner_id' => 'required'
                // Add more validation rules if necessary
            ]);

            // Fetch the partner based on the provided ID
            $partner = Partner::where('user_id', $request->partner_id)->first();

            // If partner does not exist, return an error
            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }

            // Archive all previous entries for that partner
            $partner->profiles()->update(['status' => 'Archived']);

            // Create a new PartnerProfile entry with the content and status as "Pending"
            PartnerProfile::create([
                'partner_id' => $partner->id,
                'user_id' => $partner->user_id,
                'content' => json_encode($validatedData['content']), // Assuming you want to store it as JSON.
                'status' => 'Pending'
            ]);

            return response()->json(['message' => 'Content added successfully and previous content archived']);

        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
