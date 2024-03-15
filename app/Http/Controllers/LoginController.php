<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Site;
use App\Models\Usermeta;
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

    public function handleOktaCallback(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        try {
            $state = $request->get('state');
            $request->session()->put('state', $state);

            $user = $this->getOktaUser();

            $localUser = $this->updateOrCreateLocalUser($user);

            Auth::login($localUser);

            return redirect('/dashboard');
        } catch (\Throwable $e) {
            return redirect('/login');
        }
    }

    private function getOktaUser()
    {
        return Socialite::driver('okta')->user();
    }

    private function updateOrCreateLocalUser($user)
    {
        $idToken = $user->token;
        // with user's email check for edSpark's Id
        $userEdSparkId = User::where('email', $user->email)->first()->id;

        // Manually find the user meta based on conditions
        $isSuperAdminMeta = Usermeta::where('user_id', $userEdSparkId)
            ->where('user_meta_key', 'is_super_admin')
            ->where('user_meta_value', 1)
            ->first();
        $isSuperAdmin = (bool)$isSuperAdminMeta;

        $role = $isSuperAdmin ? Role::find(1) : Role::where('role_name', $user->user['mainrolecode'])->first() ?? Role::find(4);

        $siteId = $user->user['mainsiteid'];
        $site = Site::where('site_id', $siteId)->first();

        return User::updateOrCreate(
            ['email' => $user->email],
            [
                'full_name' => $user->name,
                'role_id' => $isSuperAdmin ? 1 : $role->id,
                'site_id' => $site ? $siteId : 10000,
                'remember_token' => Str::random(15),
                'token' => $idToken ?? $user->token,
                'isFirstTimeVisit' => false,
                'status' => 'Active',
            ]
        );
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
