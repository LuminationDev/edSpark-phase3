<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;

class SiteController extends Controller
{
    public function fetchAllSites()
    {
        $sites = Site::all();

        $data = [];

        if($sites) {
            foreach ($sites as $site){
                $result = [
                    'id' => $site->id,
                    'site_id' => $site->site_id,
                    'site_name' => $site->site_name,
                    'site_value' => $site->site_value,
                    'category_code' => $site->category_code,
                    'category_desc' => $site->category_desc,
                    'site_type_code' => $site->site_type_code,
                    'site_type_desc' => $site->site_type_desc,
                    'site_sub_type_code' => $site->site_sub_type_code,
                    'site_sub_type_desc' => $site->site_sub_type_desc
                ];
                $data[] = $result;
            }
        }

        return response()->json($data);
    }

    public function fetchSiteById($id)
    {
        $site = Site::find($id);

        if($site) {
            $result = [
                'id' => $site->id,
                'site_id' => $site->site_id,
                'site_name' => $site->site_name,
                'site_value' => $site->site_value,
                'category_code' => $site->category_code,
                'category_desc' => $site->category_desc,
                'site_type_code' => $site->site_type_code,
                'site_type_desc' => $site->site_type_desc,
                'site_sub_type_code' => $site->site_sub_type_code,
                'site_sub_type_desc' => $site->site_sub_type_desc
            ];

            return response()->json($result);
        } else{
            return response()->json('No site found');
        }
    }

    public function fetchSiteByCode($siteCode)
    {
        $site = Site::where('site_id', '=', $siteCode)->first();
//        dd($site);

        if ($site) {
            $result = [
                'id' => $site->id,
                'site_id' => $site->site_id,
                'site_name' => $site->site_name,
                'site_value' => $site->site_value,
                'category_code' => $site->category_code,
                'category_desc' => $site->category_desc,
                'site_type_code' => $site->site_type_code,
                'site_type_desc' => $site->site_type_desc,
                'site_sub_type_code' => $site->site_sub_type_code,
                'site_sub_type_desc' => $site->site_sub_type_desc
            ];

            return response()->json($result);
        } else {
            return response()->json('No site found');
        }
    }
}

