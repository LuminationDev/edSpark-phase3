<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Partner;
use App\Models\Partnermeta;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PartnerController extends Controller
{
    public function fetchAllPartners()
    {
        try {
            $partners = Partner::all();

            $data = [];

            foreach ($partners as $partner) {
                $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner,'partner_id', 'partner_meta_key', 'partner_meta_column');
                $result = [
                    'id' => $partner->id,
                    'user_id' => $partner->user_id,
                    'name' => $partner->name,
                    'email' => $partner->email,
                    'logo' => $partner->logo,
                    'cover_image' => $partner->cover_image,
                    'motto' => $partner->motto,
                    'introduction' => $partner->introduction,
                    'metadata' => $partnerMeta

                ];

                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchPartnerById($id)
    {
        try {
            $partner = Partner::find($id);

            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }
            $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner,'partner_id', 'partner_meta_key', 'partner_meta_value');


            $data = [
                'id' => $partner->id,
                'user_id' => $partner->user_id,
                'name' => $partner->name,
                'email' => $partner->email,
                'logo' => $partner->logo,
                'cover_image' => $partner->cover_image,
                'motto' => $partner->motto,
                'introduction' => $partner->introduction,
                'metadata' => $partnerMeta

            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}