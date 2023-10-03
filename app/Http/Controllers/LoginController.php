<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class LoginController extends Controller
{
    public function redirectToOkta()
    {
        return Socialite::driver('okta')->redirect();
    }

    public function handleOktaCallback(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $state = $request->get('state');
        $request->session()->put('state',$state);

        $user = Socialite::driver('okta')->user();
        $idToken = $user->token;

        //TODO
        // $idToken = $user->attributes['id_token'];
        // $scopes = $user->accessTokenResponseBody['scope'];

        $localUser = User::updateOrCreate(
            ['email' => $user->email],  // columns for finding the existing model
            [
                'full_name' => $user->name,
                'name'  => $user->name,
                // 'role_id' => $role_id, //TODO
                // 'site_id' => $site_id, //TODO
                'remember_token' => Str::random(15),
                'token' => $idToken ?? $user->token,  // Adjusted to fall back to `$user->token` if `$idToken` is not set
                'isFirstTimeVisit' => false,
                'status' => 'Inactive',
            ]
        );

        try {
            Auth::login($localUser);
        } catch (\Throwable $e) {
            return redirect('/login');
        }

        return redirect('/dashboard'); //working code

    }
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logout successful',
            'status' => 200
        ]);
    }

    public function oktaData(Request $request)
    {
        if (Auth::check()){
            return response()->json([
                'success' => true,
                'email' => Auth::user()->email,
                'name' => Auth::user()->full_name,
            ]);
        }
        return response()->json([
            'success' => false,
            'email' => NULL,
            'name' => NULL,
        ]);

    }

    public function checkAuthentication(Request $request)
    {
        return response()->json([
            'authenticated' => Auth::user() ? true : false,
        ]);
    }
}
