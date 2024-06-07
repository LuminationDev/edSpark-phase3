<?php

namespace App\Http\Controllers;


use App\Models\Advice;
use App\Models\Event;
use App\Models\Hardware;
use App\Models\Software;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim($request->get('search')) ?? '';
        $results = $this->multiModelSearch($query);

        return new JsonResponse(
            data: $results,
            status: Response::HTTP_OK,
        );
    }

    protected function multiModelSearch(string $query): array
    {
        $models = [
            'software' => Software::class,
            'hardware' => Hardware::class,
            'guide' => Advice::class,
            'event' => Event::class
        ];

        $results = [];
        foreach ($models as $type => $model) {
            $searchResults = $model::search($query)->get();
            foreach ($searchResults as $result) {
                $stdResult = [
                    'id' => $result->id,
                    'title' => $result->title,
                    'content' => $result->content,
                    'excerpt' => $result->excerpt,
                    'author' => [
                        'author_id' => $result->author->id ?? '',
                        'author_name' => $result->author->full_name ?? '',
                    ],
                    'type' => $type,
                    'tags' => $result->tags->pluck('name'),
                    'cover_image' => $result->cover_image
                ];
                $results[] = $stdResult;
            }
        }

        return $results;
    }


}