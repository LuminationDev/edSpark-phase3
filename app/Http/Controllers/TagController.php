<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class TagController extends Controller
{
    public function getTopTagsByModelType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:advice,event,software,hardware',
            'unit' => 'sometimes|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first());
        }

        $type = $request->input('type');
        $unit = $request->input('unit', 10);


        $key = $type . "s";

        $tags = Tag::whereHas($key)
            ->withCount($key)
            ->orderBy($key . '_count', 'desc')
            ->take($unit)
            ->get();

        return ResponseService::success($tags);
    }


}