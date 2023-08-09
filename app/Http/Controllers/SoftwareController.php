<?php

namespace App\Http\Controllers;

use App\Helpers\Metahelper;
use App\Models\Partner;
use App\Models\Usermeta;
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
                $softwareMetadataToSend = Metahelper::getMeta(Softwaremeta::class, $software, 'software_id', 'software_meta_key', 'software_meta_value');
                $author_type = $software->author->usertype->user_type_name;
                $author_id = $software->author->id;
                if ($author_type == 'user') {
                    $avatar = Usermeta::where('user_id', $author_id)->where('user_meta_key', 'userAvatar')->first();
                    if (isset($avatar)) {
                        $author_logo = $avatar->user_meta_value;
                    } else {
                        $author_logo = '';
                    }

                } elseif ($author_type == 'partner') {
                    $author_logo = json_decode(Partner::where('user_id', $author_id)->first()->logo);
                } else {
                    $author_logo = '';
                }

                $result = [
                    'post_id' => $software->id,
                    'post_title' => $software->post_title,
                    'post_content' => $software->post_content,
                    'post_excerpt' => $software->post_excerpt,
                    'author' => [
                        'author_id' => $software->author->id,
                        'author_name' => $software->author->full_name,
                        'author_email' => $software->author->email,
                        'author_type' => $software->author->usertype->user_type_name,
                        'author_logo' => $author_logo
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

    public function fetchSoftwarePostById($id): JsonResponse
    {
        $software = Software::find($id);

        $author_type = $software->author->usertype->user_type_name;
        $author_id = $software->author->id;
        if ($author_type == 'user') {
            $avatar = Usermeta::where('user_id', $author_id)->where('user_meta_key', 'userAvatar')->first();
            if (isset($avatar)) {
                $author_logo = $avatar->user_meta_value;
            } else {
                $author_logo = '';
            }

        } elseif ($author_type == 'partner') {
            $author_logo = json_decode(Partner::where('user_id', $author_id)->first()->logo);
        } else {
            $author_logo = '';
        }

        $data = [
            'post_id' => $software->id,
            'post_title' => $software->post_title,
            'post_content' => $software->post_content,
            'post_excerpt' => $software->post_excerpt,
            'author' => [
                'author_id' => $software->author->id,
                'author_name' => $software->author->full_name,
                'author_email' => $software->author->email,
                'author_type' => $software->author->usertype->user_type_name,
                'author_logo' => $author_logo
            ],
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
