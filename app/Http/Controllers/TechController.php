<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technology;

class TechController extends Controller
{
    public function fetchAllTechs()
    {
        $techs = Technology::get();

        $data = [];

        foreach ($techs as $tech) {
            $result = [
                'tech_id' => $tech->id,
                'tech_name' => $tech->name,
                'tech_value' => $tech->value,
                'tech_icon' => $tech->icon
            ];
            $data[] = $result;
        }

        return response()->json($data);
    }
}
