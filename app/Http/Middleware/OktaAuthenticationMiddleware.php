<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;


class OktaAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request);
        // $test = true;
        // // dd(Auth::check());
        // //check if the user is authenticated through okta
        // if ($test) {
        //     // Store the authenticated user's id in the session
        //     session(['authenticated_user_id' => Auth::id()]);
        // }else {
        //     // check if the authenticated user id is stored in the session
        //     $authenticatedUserId = session('authenticated_user_id');
        //     if ($authenticatedUserId) {
        //         // Log in the user using their stored ID
        //         Auth::loginUsingId($authenticatedUserId, true);
        //     }
        // }

        // // continue with the request
        // return $next($request);
        // dd(Auth::check());
        // dd($request->all());
        // dd($request->cookie('authenticated_user_id'));
        dd(Cookie::get('authenticated_user_id'));
        // var_dump(session('authenticated_user_id'));
        // var_dump(Auth::check());
        // dd($request->session()->pull('authenticated_user_id'));
        // var_dump($request->cookie('authenticated_user_id'));
        // dd(Session::get('authenticated_user_id'));

    }
}


