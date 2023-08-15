<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Partner;
use App\Models\Partnermeta;
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

        return [
            'id' => $partner->id,
            'user_id' => $partner->user_id,
            'name' => $partner->name,
            'email' => $partner->email,
            'logo' => json_decode($partner->logo) ?: NULL,
            'cover_image' => json_decode($partner->cover_image) ?: NULL,
            'motto' => $partner->motto,
            'introduction' => $partner->introduction,
            'content' => $partner->content,
            'metadata' => $partnerMeta,
            'isLikedByUser' => $isLikedByUser,
            'isBookmarkedByUser' => $isBookmarkedByUser,
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
}
