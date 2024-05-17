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
use Illuminate\Support\Facades\Log;


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
            Log::info('Attempting login ' . $user->email);
            $localUser = $this->updateOrCreateLocalUser($user);
            Auth::login($localUser);
            Log::info('Success login ' . $user->email);


            return redirect('/dashboard');
        } catch (\Throwable $e) {
            Log::error('Failed login ' . $user->email);
            return redirect('/error?errtype=failed+login', 302);
        }
    }

    private function getOktaUser()
    {
        try{
            return Socialite::driver('okta')->user();

        } catch (\Exception $e){
            Log::error('Failed to getOktaUser from Socialiate Driver ' .  $e->getMessage());
            return NULL;
        }
    }

    private function updateOrCreateLocalUser($user)
    {
        try {
            $idToken = $user->token;

            // Get the user's edSpark profile/data
            $userEdSpark = isset($user->email) ? User::where('email', $user->email)->first() : NULL;
            $userEdSparkId = isset($userEdSpark) ? $userEdSpark->id : false;

            // If user exists in edSpark, check if Superadmin or not
            if ($userEdSparkId) {
                $isSuperAdminMeta = Usermeta::where('user_id', $userEdSparkId)
                    ->where('meta_key', 'is_super_admin')
                    ->where('meta_value', 1)
                    ->first();
                $isSuperAdmin = (bool)$isSuperAdminMeta;
            } else {
                // New user here. Not superadmin, default role from Okta
                $isSuperAdmin = false;
            }
            $dataToBeUpdatedOrCreated = [
                'full_name' => $user->name,
                'display_name' => (isset($userEdSpark) && $userEdSpark->display_name) ? $userEdSpark->display_name :$user->name,
                'remember_token' => Str::random(15),
                'token' => $idToken ?? "",
                'first_visit' => false,
                'status' => 'Active',
            ];

            // Do role here
            // If user is super admin, re-iterate role as superadmin
            // If user is not superadmin, check if existing user
            // For existing user, do not update role. For new user, update role based on `mainrolecode` from Okta
            if ($isSuperAdmin) {
                $role = Role::find(1);
                $dataToBeUpdatedOrCreated['role_id'] = $role->id;
            } else if (!$userEdSparkId) { // User has no current account, get role from Okta or default to viewer
                try {
                    $userHasMainrolecode = isset($user->user['mainrolecode']);
                    $userRoleOnEdSpark = $userHasMainrolecode ? Role::where('role_name', $user->user['mainrolecode'])->first() : NULL ;
                    $role = $userRoleOnEdSpark ?? Role::where('role_name', 'OTHER')->first();
                    $dataToBeUpdatedOrCreated['role_id'] = $role->id;
                } catch (\Exception $e) {
                    // Handle failure to get role
                    Log::error('Failed to fetch role: ' . $e->getMessage());
                }
            }

            // Determine site here
            if (!$userEdSparkId) {
                try {
                    $userOktaSiteId = $user->user['mainsiteid'] ?? null;
                    $userOktaSite = $userOktaSiteId ? Site::where('site_id', $userOktaSiteId)->first() : null;
                    $dataToBeUpdatedOrCreated['site_id'] = $userOktaSite ? $userOktaSite->site_id : 9999;
                } catch (\Exception $e) {
                    // Handle failure to get site
                    Log::error('Failed to fetch site: ' . $e->getMessage());
                }
            }

            return User::updateOrCreate(
                ['email' => $user->email],
                $dataToBeUpdatedOrCreated
            );
        } catch (\Exception $ex) {
            // Handle any unexpected exceptions
            Log::error('Error in updateOrCreateLocalUser: ' . $ex->getMessage());
            return null;
        }
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
