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

                // initiated default meta if it doesn't exists
                $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner,'partner_id', 'partner_meta_key', 'partner_meta_value');
                if (!Metahelper::checkHasMetakey($partnerMeta, 'single_submenu','partner_meta_key')) {
                    // Create new entry with 'overview,access' as the partner_meta_value
                    Metahelper::insert( $partner->id, ['single_submenu' => 'overview,access'],'partner_id','partner_meta_key', 'partner_meta_value', Partnermeta::class  );
                    $partnerMeta['single_submenu'] = 'overview,access'; // Update the $partnerMeta array
                }
                if (!Metahelper::checkHasMetakey($partnerMeta, 'contact_info','partner_meta_key')) {
                    Metahelper::insert( $partner->id, ['contact_info' => '{}'] , 'partner_id', 'partner_meta_key', 'partner_meta_value',Partnermeta::class);
                    $partnerMeta['single_submenu'] = 'overview,access'; // Update the $partnerMeta array
                }
                $result = [
                    'id' => $partner->id,
                    'user_id' => $partner->user_id,
                    'name' => $partner->name,
                    'email' => $partner->email,
                    'logo' => json_decode($partner->logo) ?: NULL,
                    'cover_image' => json_decode($partner->cover_image)?: NULL,
                    'motto' => $partner->motto,
                    'introduction' => $partner->introduction,
                    'content' => $partner->content,
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
            $partner = Partner::where('user_id', $id)->first();

            if (!$partner) {
                return response()->json(['error' => 'Partner not found'], Response::HTTP_NOT_FOUND);
            }
            $partnerMeta = Metahelper::getMeta(Partnermeta::class, $partner,'partner_id', 'partner_meta_key', 'partner_meta_value');


            $data = [
                'id' => $partner->id,
                'user_id' => $partner->user_id,
                'name' => $partner->name,
                'email' => $partner->email,
                'logo' => json_decode($partner->logo),
                'cover_image' => json_decode($partner->cover_image),
                'motto' => $partner->motto,
                'introduction' => $partner->introduction,
                'metadata' => $partnerMeta,
                'content' => $partner->content,

            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
