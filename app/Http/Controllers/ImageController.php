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
                // dd(array_values($data)[0]);
                $type = '';
                $image = '';
                $file = array_values($data)[0];
                $prefix = "edspark-".$type;
                $imgName = '';

                if ($file->getMimeType() == 'video/mp4') {
                    $type = 'video';
                    $image = $data['file'];
                    $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$image).'.'.$data['file']->extension();
                } else {
                    $type = 'image';
                    $image = $data['image'];
                    $imgName = $prefix.'-'.md5(Str::random(30).time().'_'.$image).'.'.$image->getClientOriginalExtension();
                }
                $image->storeAs('public/uploads/'.$type, $imgName);
                $imagePath .= "uploads/".$type."/".$imgName;
            }

            return response()->json([
                "success" => 1,
                "file" => [
                    "url" =>  $_ENV['VITE_SERVER_IMAGE_API'].$imagePath
                ]
            ]);
        }
    }
}

