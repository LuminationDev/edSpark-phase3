<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Software;
use App\Models\Softwaremeta;

class SoftwareController extends Controller
{
    public function fetchSoftwarePosts(): JsonResponse
    {
        try {
            $softwares = Software::where('post_status', 'Published')
                ->orderBy('created_at', 'DESC')
                ->get();

            $data = [];

            foreach ($softwares as $software) {
//                $softwareMetadata = Softwaremeta::where('software_id', $software->id)->get();
//                $softwareMetadataToSend = $softwareMetadata->map(function ($item) {
//                    return [
//                        'softwaremeta_key' => $item->software_meta_key,
//                        'softwaremeta_value' => $item->sofware_meta_value
//                    ];
//                })->toArray();
                $softwareMetadataToSend = Metahelper::getMeta(Softwaremeta::class, $software, 'software_id','software_meta_key', 'software_meta_value');

                $result = [
                    'post_id' => $software->id,
                    'post_title' => $software->post_title,
                    'post_content' => $software->post_content,
                    'post_excerpt' => $software->post_excerpt,
//                    'author' => ($software->author) ? $software->author->full_name : null,
                    'author' => [
                        'author_id' => $software->author->id,
                        'author_name' => ($software->author->id) ? $software->author->full_name : NULL
                    ],
                    'cover_image' => ($software->cover_image) ? $software->cover_image : null,
                    'post_date' => $software->post_date,
                    'post_modified' => $software->post_modified,
                    'post_status' => $software->post_status,
                    'software_type' => ($software->softwaretypes) ? $software->softwaretypes->pluck('software_type_name') : null,
                    'template' => ($software->template) ? $software->template : null,
                    'extra_content' => ($software->extra_content) ? $software->extra_content : null,
                    'metadata' => ($softwareMetadataToSend) ?: null,
                    'created_at' => $software->created_at,
                    'updated_at' => $software->updated_at
                ];

                $data[] = $result;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => "$e"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fetchSoftwarePostById($id)
    {
        $software = Software::find($id);

        $data = [
            'post_id' => $software->id,
            'post_title' => $software->post_title,
            'post_content' => $software->post_content,
            'post_excerpt' => $software->post_excerpt,
            'author' => ($software->author) ? $software->author->full_name : NULL,
            'cover_image' => ($software->cover_image) ? $software->cover_image : NULL,
            'post_date' => $software->post_date,
            'post_modified' => $software->post_modified,
            'post_status' => $software->post_status,
            'software_type' => ($software->softwaretypes) ? $software->softwaretypes->pluck('software_type_name') : NULL,
            'template' => ($software->template) ? $software->template : NULL,
            'extra_content' => ($software->extra_content) ? $software->extra_content : NULL,
            'created_at' => $software->created_at,
            'updated_at' => $software->updated_at
        ];

        return response()->json($data);

    }
}
