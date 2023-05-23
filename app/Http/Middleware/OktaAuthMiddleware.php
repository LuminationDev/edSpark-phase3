<?php
//namespace App\Http\Middleware;

//use Closure;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Http\Request;
//
//class OktaAuthMiddleware
//{


//    public function handle(Request $request, Closure $next)
//    {
////        dd(Auth::guest());
//        if (Auth::guest()) {
//            $token = $request->header('Authorization');
////            $token = 'eyJraWQiOiJzd1FkeUVYR0lmUG1kQjFpUTB1OHNrM0tDd0pJREx3V1pYdHM5eEprWk5BIiwiYWxnIjoiUlMyNTYifQ';
//            if (!$token) {
//                return response('Unauthorized.', 401);
//            }
//
//            try {
//                $decodedToken = JWT::decode($token, OktaJwtVerifier::$publicKey, ['RS256']);
//                $oktaUserId = $decodedToken->claims->get('sub');
//
//                // Check if the user with the given Okta user ID exists in your application's user table
//                $user = User::where('okta_user_id', $oktaUserId)->first();
//
//                if (!$user) {
//                    return response('Unauthorized.', 401);
//                }
//
//                Auth::login($user);
//            } catch (\Exception $e) {
//                return response('Unauthorized.', 401);
//            }
//        }
//
//        return $next($request);
//    }

//    public function handle(Request $request, Closure $next)
//    {
//        if ($this->isAuthorized($request)) {
//            return $next($request);
//        } else {
//            return response('Unauthorized.', 401);
//        }
//    }
//
//    public function isAuthorized($request)
//    {
//        if (! $request->header('Authorization')) {
//            return false;
//        }
//
//        $authType = null;
//        $authData = null;
//
//        // Extract the auth type and the data from the Authorization header.
//        @list($authType, $authData) = explode(" ", $request->header('Authorization'), 2);
//
//        // If the Authorization Header is not a bearer type, return a 401.
//        if ($authType != 'Bearer') {
//            return false;
//        }
//
//        // Attempt authorization with the provided token
//        try {
//
//            // Setup the JWT Verifier
//            $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
//                ->setAdaptor(new \Okta\JwtVerifier\Adaptors\SpomkyLabsJose())
//                ->setAudience('api://default')
//                ->setClientId('{yourClientId}')
//                ->setIssuer('{yourIssuerUrl}')
//                ->build();
//
//            // Verify the JWT from the Authorization Header.
//            $jwt = $jwtVerifier->verify($authData);
//        } catch (\Exception $e) {
//
//            // You encountered an error, return a 401.
//            return false;
//        }
//
//        return true;
//    }

//    public function handle(Request $request, Closure $next)
//    {
//        if (Auth::loginUsingId(7, true)) {
//            return $next($request);
//        }
//
//        return redirect('/home');
//    }
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OktaAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            var_dump('already authenticated');
            return $next($request);
        }

        // Add your Okta authentication logic here
        // For example, you can check the Okta access token or session

        // If the user is authenticated in Okta, manually authenticate the user in Laravel
        dd($this->userIsAuthenticatedInOkta());
        if ($this->userIsAuthenticatedInOkta()) {
//            var_dump('here'); exit;
            $user = $this->retrieveUserFromOkta(); // Implement the logic to retrieve the user based on Okta details
            Auth::login($user);
            return $next($request);
        }

        // If the user is not authenticated, redirect to Okta login
        return redirect()->away('https://portal-test.edpass.sa.edu.au/oauth2/default');
    }

    private function userIsAuthenticatedInOkta()
    {
        // Implement the logic to check if the user is authenticated in Okta
        // For example, check the Okta access token or session
        // Return true if authenticated, false otherwise

        $accessToken = 'eyJraWQiOiJzd1FkeUVYR0lmUG1kQjFpUTB1OHNrM0tDd0pJREx3V1pYdHM5eEprWk5BIiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiIwMHUyZmlzd3dyV0RhUDJhYzNsNyIsIm5hbWUiOiJXaWxsaWFtcywgQ2xpbnRvbiBUZWFjaGVyIEIgKENsYXJlIEhpZ2ggU2Nob29sKSIsImVtYWlsIjoiQ2xpbnRvblRlYWNoZXJCLldpbGxpYW1zMTk5QHRlc3Qtc2Nob29scy5zYS5lZHUuYXUiLCJ2ZXIiOjEsImlzcyI6Imh0dHBzOi8vcG9ydGFsLXRlc3QuZWRwYXNzLnNhLmVkdS5hdS9vYXV0aDIvZGVmYXVsdCIsImF1ZCI6IjBvYTJqcjZ0NzRBa2VnelJEM2w3IiwiaWF0IjoxNjg0ODIzODA4LCJleHAiOjE2ODQ4Mjc0MDgsImp0aSI6IklELmo0Qld3YlVndE0wQXROS29aM0RZTXR0Zm9DV2FrblpxMmFrZzBMMDBrTmMiLCJhbXIiOlsicHdkIl0sImlkcCI6IjBvYTZkMGVicGY4cGZFUzNUM2w2Iiwibm9uY2UiOiI3MUN4dTBKMVg0STZxcHdKOEN4Ump5Q3VKcU04RzVRWFczbjhRTFpuenhEak9TUGQ0MlhNaVI1OU9VQzc0cFh0IiwicHJlZmVycmVkX3VzZXJuYW1lIjoiQ2xpbnRvblRlYWNoZXJCLldpbGxpYW1zMTk5QHRlc3Qtc2Nob29scy5zYS5lZHUuYXUiLCJhdXRoX3RpbWUiOjE2ODQ4MTYyNzQsImF0X2hhc2giOiJNV2JCZFk3VEhfWExLQ2wyLXNoM0tBIiwibWFpbnJvbGVjb2RlIjoiU1RDSCIsIm1haW5zaXRlaWQiOiIwNzczIn0.S6xuAd9rwLKd6kpyhLmaUawzNABuVdsqL4JEnR0-KMorQy40Ds825WwZ-DbmAOKaPqz-P44Mty5ETf4E4drXfXsIxga8r80nfAh_3d2Yfk7tSZy0xon-wQdEgSqM5Fk7K2u-7lli8m5MK6R7ZevwLJ7ysCoMUui9gZJfyj3xlXGb_zQOU79nO1Hft8VrbWBZq62dAJEU1qSQqKBeFJ41FfD8riC3-cX-8GyVReOZzcOUDGTX_ph3-dJxRYa_Xtu-yKak-q_TL5_c2RedM23yGnJFq8ADQA1d6Y-mfmO9smKAHS9Au9tPi_zG3y0adoZ0r5gZjiAF3VIBvZP3ekLkDA'; // Retrieve the access token from Okta, e.g., from session or request headers

            // Make a request to the Okta API to validate the access token
//        $response = Http::withHeaders([
//            'Authorization' => 'Bearer ' . $accessToken,
//        ])->get('https://portal-test.edpass.sa.edu.au/oauth2/default/v1/introspect');
//
//        if ($response->successful()) {
//            $responseData = $response->json();
//            return $responseData['active'] ?? false;
//        }
//
//        return false;

        // Make a request to the Okta API to validate the access token
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://portal-test.edpass.sa.edu.au/oauth2/default/v1/userinfo');

        if ($response->successful()) {
            $responseData = $response->json();
            // Add any additional checks to verify the user's authentication status in Okta
            // For example, you can check for a specific claim or attribute
            return isset($responseData['sub']); // Return true if the 'sub' claim is present, indicating authentication
        }

        return false;
    }

    private function retrieveUserFromOkta()
    {
        // Implement the logic to retrieve the user based on Okta details
        // Return the authenticated user instance
        $userId = 7;
        return $userId;
    }
}
