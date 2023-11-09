<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Site;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function redirectToOkta()
    {
        // TODO: SCOPE ONCE WE MIGRATE TO REAL OKTA
        return Socialite::driver('okta')->scopes(['edspark', 'email', 'address'])->redirect();

    }

    public function handleOktaCallback(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $state = $request->get('state');
        $request->session()->put('state', $state);

        $user = Socialite::driver('okta')->user();
        $idToken = $user->token;
//        dd($user->user['mainrolecode']);

        $role = Role::where('role_name', $user->user['mainrolecode'])->first() ?? Role::find(4);

        $siteId = $user->user['mainsiteid'];
        $site = Site::where('site_id', $siteId)->first();

        $localUser = User::updateOrCreate(
            ['email' => $user->email],  // columns for finding the existing model
            [
                'full_name' => $user->name,
                'name' => $user->name,
                'role_id' => $role->id,
                'site_id' => $site ? $user->user['mainsiteid'] : 9999 ,
                'remember_token' => Str::random(15),
                'token' => $idToken ?? $user->token,  // Adjusted to fall back to `$user->token` if `$idToken` is not set
                'isFirstTimeVisit' => false,
                'status' => 'Active',
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
        if (Auth::check()) {
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
            'authenticated' => (bool)Auth::user(),
        ]);
    }
}
