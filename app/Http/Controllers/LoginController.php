<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Okta\JwtVerifier\JwtVerifierBuilder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class LoginController extends Controller
{
    public function redirectToOkta()
    {
        return Socialite::driver('okta')->redirect();
    }

    public function handleOktaCallback(Request $request)
    {
        $user = Socialite::driver('okta')->user();

        $localUser = User::where('email', $user->email)->first();

        // create a local user with the email and token from Okta
        if (! $localUser) {
            $localUser = User::create([
                'full_name' => $user->name,
                'email' => $user->email,
                'name'  => $user->name,
                'remember_token' => Str::random(15),
            ]);
        } else {

            // if the user already exists, just update the token:
            $localUser->remember_token = Str::random(15);
            $localUser->save();
        }

        try {
            Auth::login($localUser);
        } catch (\Throwable $e) {
            return redirect('/login');
        }

        return redirect('/dashboard'); //working code

        // Redirect to the dashboard with the email as a query parameter
        // return redirect('/dashboard?email=', urlencode($user->email));
        // return Redirect::to('/dashboard')->with('email', $user->email);

        // Flash the email to the session
        // Session::flash('email', $user->email);
        // $request->session()->put('email', $user->email);
        // Redirect to the intended dashboard route
        // return redirect()->intended('/dashboard');
    }

    public function bypassAuthentication()
    {
        dd('bypass authentication ');

    }

    public function logout()
    {
        // Logout from Okta using Socialite
        // Socialite::driver('okta')->logout();

        //Logout from laravel
        Auth::logout();
        Session::flush();
        return redirect('/');
    }

    public function oktaData(Request $request)
    {
        if (Auth::check()){
            return response()->json([
                'success' => true,
                'email' => Auth::user()->email,
            ]);
        }

        return response()->json([
            'success' => false,
            'email' => NULL,
        ]);

    }
}
