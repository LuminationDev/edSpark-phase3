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
            $currPath = "";
            if ($data) {
                // dd($data['file']->getMimeType());
                $type = $data['file']->getMimeType() == 'video/mp4' ? 'video' : 'image';
                $image = $data['file']->getMimeType() == 'video/mp4' ? $data['file'] : $data['image'];

                $prefix = "edspark-".$type;
                $imgName = '';
                if ($data['file']->getMimeType() == 'video/mp4') {
                    $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$image).'.'.$data['file']->extension();
                } else {
                    $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$image).'.'.$image->getClientOriginalExtension();
                }

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

