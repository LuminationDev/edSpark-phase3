<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilterPartnerResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Auth::check()) {
            // If the user is a partner, filter the posts by their own ID
            $user = Auth::user();
            $content = json_decode($response->content(), true);
            if (isset($content['data'])) {
                $content['data'] = array_filter($content['data'], function ($post) use ($user) {
                    dd($post['author_id']);
                    return $post['author_id'] === $user->id;
                });
            }

            $response->setContent(json_encode($content));
        }

        return $response;
    }

}
