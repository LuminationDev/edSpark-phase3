<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ImageController extends Controller
{
    public function imageUpload(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            dd($data);
            $imagePath = "";
            if ($data) {
                $type = $data['type'];
                $image = $data['image'];

                $prefix = "edspark-".$type;
                $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$image).'.'.$image->getClientOriginalExtension();
                $image->storeAs('public/uploads/'.$type, $imgName);
                $imagePath .= "uploads/".$type."/".$imgName;
            }

            return response()->json([
                "success" => 1,
                "file" => [
                    "url" =>  $imagePath
                ]
            ]);
        }
    }

    public function imageUploadEditorjs(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $imagePath = "";
            if ($data) {
                $type = 'image';
                $image = $data['image'];

                $prefix = "edspark-".$type;
                $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$image).'.'.$image->getClientOriginalExtension();
                $image->storeAs('public/uploads/'.$type, $imgName);
                $imagePath .= "uploads/".$type."/".$imgName;
            }

            return response()->json([
                "success" => 1,
                "file" => [
                    "url" =>  'http://localhost:8000/storage/'.$imagePath
                ]
            ]);
        }
    }
}

