<?php

namespace App\Http\Controllers;

use App\Models\Partner;
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
                $result = [
                    'id' => $partner->id,
                    'user_id' => $partner->user_id,
                    'name' => $partner->name,
                    'email' => $partner->email
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

            $data = [
                'id' => $partner->id,
                'user_id' => $partner->user_id,
                'name' => $partner->name,
                'email' => $partner->email
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}