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
        $models = [Software::class, Hardware::class, Advice::class, Event::class];
        $results = [];
        foreach ($models as $model) {
            $searchResults = $model::search($query)->get();
            foreach ($searchResults as $result){
                $stdResult = [
                    'id' => $result->id,
                    'title' => $result->post_title ?? $result->product_name ?? $result->event_title,
                    'content' => $result->post_content?? $result->product_content ?? $result->event_content,
                    'excerpt' => $result->post_excerpt ?? $result->product_excerpt ?? $result->event_excerpt,
                    'author' =>[
                        'author_id' => $result->author->id ?? '',
                        'author_name' => $result->author->full_name ?? '',
                        'author_type' => $result->author->usertype->user_type_name ?? '',
                    ],
                    'type' => (isset($result->advicetypes) ? 'advice' : NULL)
                        ?? (isset($result->softwaretypes) ? 'software' : NULL)
                        ?? (isset($result->product_name) ? 'hardware' : NULL)
                        ?? (isset($result->event_title) ? 'event' : NULL),
                    'tags' => $result->tags->pluck('name'),
                    'cover_image' => $result->cover_image

                ];
                $results[] = $stdResult;
            }
        }

        return $results;
    }

    // uses query database,slows down meilisearch query. should entirely rely on meilisearch
//    protected function multiModelSearch(string $query): array
//    {
//        $models = [Software::class, Hardware::class, Advice::class, Event::class];
//        $results = [];
//
//        foreach ($models as $model) {
//            $ids = $model::search($query)->keys();
////            $searchResults = $model::whereIn('id', $ids)->get();
//            $searchResults = $model::whereIn('id', $ids)->get();
//            foreach ($searchResults as $singleModel) {
//                $singleResult = $singleModel->getSearchResult();
//                // Do something with $result or store it in an array
//                $results = array_merge($results, $singleResult);
//            }
//        }
//
//        return $results;
//    }
}