<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ImageController extends Controller
{
    public function imageUpload(Request $request, $type = null): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $imagePath = "";

            // If type isn't present in URL, check the request body
            if (!$type && isset($data['type'])) {
                $type = $data['type'];
            }

            // Ensure type is available before proceeding
            if (!$type) {
                return response()->json([
                    "success" => 0,
                    "file" => [
                        "url" => "",
                        "message" => "Type is required."
                    ]
                ]);
            }

            // Ensure an image is provided in the request body
            if (!isset($data['image']) || !$data['image']->isValid()) {
                return response()->json([
                    "success" => 0,
                    "file" => [
                        "url" => "",
                        "message" => "Image is required or is invalid."
                    ]
                ]);
            }

            try {
                $image = $data['image'];
                $prefix = "edspark-" . $type;
                $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $image) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/uploads/' . $type, $imgName);
                $imagePath .= "uploads/" . $type . "/" . $imgName;
            } catch (\Exception $e) {
                return response()->json([
                    "success" => 0,
                    "file" => [
                        "url" => "",
                        "message" => "Error uploading the image: " . $e->getMessage()
                    ]
                ]);
            }

            return response()->json([
                "success" => 1,
                "file" => [
                    "url" => env('VITE_SERVER_IMAGE_API') . $imagePath,
                    "title" => 'Video.mp4',
                    "extension" => $image->getClientOriginalExtension()
                ]
            ]);
        }

        // If the method is not POST
        return response()->json([
            "success" => 0,
            "file" => [
                "url" => "",
                "message" => "Invalid request method."
            ]
        ]);
    }

    public function imageUploadEditorjs(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $imagePath = "";
            $currPath = "";
            $image = '';

            if ($data) {
                // dd(array_values($data)[0]);
                $type = '';
                $file = array_values($data)[0];
                $prefix = "edspark-" . $type;
                $imgName = '';

                if ($file->getMimeType() == 'video/mp4') {
                    $type = 'video';
                    $image = $data['file'];
                    $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $image) . '.' . $data['file']->extension();
                } else {
                    $type = 'image';
                    $image = (array_key_exists('file', $data) ? $data['file'] : NULL) ?? (array_key_exists('image', $data) ? $data['image'] : NULL);
                    $imgName = $prefix . '-' . md5(Str::random(30) . time() . '_' . $image) . '.' . $image->getClientOriginalExtension();
                }
                $image->storeAs('public/uploads/' . $type, $imgName);
                $imagePath .= "/uploads/" . $type . "/" . $imgName;
            }

            return response()->json([
                "success" => 1,
                "file" => [
                    "url" => env('VITE_SERVER_IMAGE_API') . $imagePath,
                    "title" => $prefix . $image->extension(),
                    "extension" => $image->getClientOriginalExtension()
                ]
            ]);
        }
    }
}

