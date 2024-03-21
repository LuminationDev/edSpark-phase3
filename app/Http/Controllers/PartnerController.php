<?php

namespace App\Http\Controllers;

use App\Helpers\JsonHelper;
use App\Helpers\Metahelper;
use App\Helpers\RoleHelpers;
use App\Helpers\UserRole;
use App\Models\Advice;
use App\Models\Hardware;
use App\Models\Partner;
use App\Models\Partnermeta;
use App\Models\Partnerprofile;
use App\Models\Role;
use App\Models\Software;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            $partnerProfile = Partnerprofile::create([
                'partner_id' => $partner->id,
                'user_id' => $partner->user_id,
                'content' => "<p>Partner Content</p>",
                'status' => 'Published',
            ]);
        }
        return $partnerProfile;
    }

    private function partnerModelToJson($partner, $request = NULL)
    {

        $userId = Auth::user()->id;
        $isLikedByUser = $partner->likes()->where('user_id', $userId)->exists();
        $isBookmarkedByUser = $partner->bookmarks()->where('user_id', $userId)->exists();
        $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner, 'partner_id', 'partner_meta_key', 'partner_meta_value');

        // Fetch or create partner profile.
        $partnerProfile = $this->getOrCreatePartnerProfile($partner);

        return [
            'id' => $partner->id,
            'user_id' => $partner->user_id,
            'name' => $partner->name,
            'email' => $partner->email,
            'logo' => JsonHelper::safelyDecodeString($partnerProfile->logo) ?: NULL,
            'cover_image' => JsonHelper::safelyDecodeString($partnerProfile->cover_image) ?: NULL,
            'motto' => $partnerProfile->motto,
            'introduction' => $partnerProfile->introduction,
            'metadata' => $partnerMeta,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
            'profile' => JsonHelper::safelyDecodeString($partnerProfile->content),
            'updated_at' => $partnerProfile->updated_at,
        ];
    }

    private function handleImageUpload($image, $prefix, $folder)
    {
        if (isset($image) && is_string($image) === false) {
            $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $image) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/uploads/partner/' . $folder, $imgName);
            return "uploads/partner/$folder/" . $imgName;
        }
        return null;
    }

    private function replacePreviousPendingPartnerProfileEntry($partnerId)
    {
        Partnerprofile::where('partner_id', $partnerId)
            ->where('status', 'Pending')
            ->update(['status' => 'Archived']);
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

    public function fetchPartnerById(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|gt:0',
                'preview' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return ResponseService::error('Invalid request parameters', 400);
            }
            $id = $request->input('id');
            $preview = $request->input('preview');
            $partner = Partner::where('user_id', $id)->first();

            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }
            $this->initializePartnerMetadata($partner); // Ensure metadata exists.
            $data = $this->partnerModelToJson($partner, $request);

            return ResponseService::success('Successfully retreived partner', $data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchPendingPartnerProfile(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'partner_id' => 'required|integer|exists:partner_profiles,user_id',
            ]);

            $currentUserId = $validatedData['user_id'];
            $partnerId = $validatedData['partner_id'];

            $partner = Partner::where('user_id', $partnerId)->first();

            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }

            $partnerProfile = $partner->profiles()->where('status', 'Pending')->latest()->first();

            // If there's no pending profile, return a message
            if (!$partnerProfile) {
                return response()->json([
                    'message' => 'No pending profile found for this partner.',
                    'pending_available' => false,
                    'result' => null], Response::HTTP_NOT_FOUND);
            }

            $partnerData = $this->partnerModelToJson($partner);
            $partnerData['profile'] = json_decode($partnerProfile->content);

            // Update all other profiles with the same partner_id to "Archived"
            Partnerprofile::where('user_id', $partnerId)
                ->where('id', '!=', $partnerProfile->id)
                ->update(['status' => 'Archived']);

            return response()->json([
                'message' => 'Pending profile found',
                'pending_available' => true,
                "result" => $partnerData
            ]);

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->errors()], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            // Handle other general errors
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePartnerContent(Request $request)
    {
        try {
            // Validate the request data
            $data = $request->all();
            if(Auth::user()->id !== $data['partner_id'] && !RoleHelpers::has_minimum_privilege(UserRole::ADMIN)){
                return response()->json(['error' => 'Not authorised to edit this profile'], Response::HTTP_UNAUTHORIZED);
            }

            // Fetch the partner based on the provided ID
            $partner = Partner::where('user_id', $request->partner_id)->first();
            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }
            $newIntro = $data['introduction'] ?? '';
            $newMotto = $data['motto'] ?? '';
            $prefix = "edSpark-partner";
            $partnerLogoUrl = $this->handleImageUpload($data['logo'] ?? null, $prefix, 'logo');
            $coverImageUrl = $this->handleImageUpload($data['cover_image'] ?? null, $prefix, '');

            // mark prev listing archived before entering a new entry
            $this->replacePreviousPendingPartnerProfileEntry($partner->id);

            // Create a new PartnerProfile entry with the content and status as "Pending"
            Partnerprofile::create([
                'partner_id' => $partner->id,
                'user_id' => $partner->user_id,
                'content' => JsonHelper::safelyEncodeData($data['content']),
                'introduction' => $newIntro,
                'motto' => $newMotto,
                'logo' => $partnerLogoUrl,
                'cover_image' => $coverImageUrl,
                'status' => 'Pending'
            ]);


            return response()->json(['message' => 'Content added successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkIfUserCanEditPartner(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'partner_id' => 'required'
            ]);

            if (Auth::user()->id === $validatedData['partner_id']) {
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    "canNominate" => false,
                    "message" => "You are editing as a Partner"
                ]);
            }
            if(RoleHelpers::has_minimum_privilege(UserRole::ADMIN)){
                return response()->json([
                    "status" => 200,
                    "result" => true,
                    "canNominate" => false,
                    "message" => "You are editing as a Superadmin"
                ]);
            }
            else {
                return response()->json([
                    "status" => 401,
                    "result" => false,
                    "canNominate" => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


}
