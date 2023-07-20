<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Okta\JwtVerifier\JwtVerifierBuilder;
use Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Config;


class LoginController extends Controller
{
    public function redirectToOkta()
    {
        return Socialite::driver('okta')->redirect();
    }

    public function handleOktaCallback(Request $request)
    {
        $state = $request->get('state');
        $request->session()->put('state',$state);

        $user = Socialite::driver('okta')->user();
        $idToken = $user->token;

        //TODO
        // $idToken = $user->attributes['id_token'];
        // $scopes = $user->accessTokenResponseBody['scope'];
         // Decode the ID token to access its claims
        // $claims = $this->decodeIdToken($idToken);
        // dd($idToken, $user, $scopes, $claims);

        // Do something with the claims
        // Access custom claims (e.g., role_id and site_id)
        // $roleId = $claims['role_id'];
        // $siteId = $claims['site_id'];
        // var_dump($roleId);
        // var_dump($siteId);
        // Retrieve Okta issuer URL and client ID from .env
        //     $issuerUrl = config('services.okta.base_url');
        //     $clientId = config('services.okta.client_id');
        //     var_dump($issuerUrl);
        //     var_dump($clientId);

        //    // Configure the HTTP client and message factory
        //    $httpClient = HttpClientDiscovery::find();
        //    $httpClient = new GuzzleAdapter($httpClient);
        //    $messageFactory = MessageFactoryDiscovery::find();

        //    // Create the JwtVerifierBuilder
        //    $verifierBuilder = new JwtVerifierBuilder($httpClient, $messageFactory);

        //    // Set the necessary configuration options
        //    $verifierBuilder->setAdaptor(new \Okta\JwtVerifier\Adaptors\FirebasePhpJwt());
        //    $verifierBuilder->setClientId(env('OKTA_CLIENT_ID'));
        //    $verifierBuilder->setIssuer(env('OKTA_ISSUER_URL'));

        //    // Build the JwtVerifier
        //    $verifier = $verifierBuilder->build();

        //    // Verify and decode the Okta ID token
        //    $decodedToken = $verifier->verify($idToken);

        //    // Access the claims
        //    $claims = $decodedToken->claims;

        //     dd($claims);
        // Create the verifier
        // $verifier = (new JwtVerifierBuilder())
        //                 ->setIssuer($issuerUrl)
        //                 ->setClientId($clientId)
        //                 ->build();
        // $verifier = (new JwtVerifierBuilder())
        // ->setAdaptor(new \Okta\JwtVerifier\Adaptors\FirebasePhpJwt())
        // ->build();
        // $decodedToken = $verifier->verify($token);
        // dd($decodedToken);
        // $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
        //                 ->setDiscovery(new \Okta\JwtVerifier\Discovery\Oauth) // This is not needed if using oauth.  The other option is `new \Okta\JwtVerifier\Discovery\OIDC`
        //                 ->setAdaptor(new \Okta\JwtVerifier\Adaptors\FirebasePhpJwt)
        //                 ->setAudience('api://default')
        //                 ->setClientId($clientId)
        //                 ->setIssuer($issuerUrl)
        //                 ->build();

        // Decode the ID token
        // $jwt = $jwtVerifier->verifyAccessToken($jwtString);
        // var_dump($jwt);
        // echo '<hr>';

        // Access the claims
        // $claims = $decodedToken->claims;
        // dd($claims);

        // Assuming we get all the claims from okta login
        // $claims = $user->user;
        // $role_id = 3; //$claims->role_id;
        // $site_id = 106; //$claims->site_id;

        $localUser = User::where('email', $user->email)->first();

        // create a local user with the email and token from Okta
        if (! $localUser) {
            $localUser = User::create([
                'full_name' => $user->name,
                'email' => $user->email,
                'name'  => $user->name,
                // 'role_id' => $role_id, //TODO
                // 'site_id' => $site_id, //TODO
                'remember_token' => Str::random(15),
                'token' => $idToken,
                'isFirstTimeVisit' => True,
                'status' => 'Inactive',
            ]);
        } else {
            // if the user already exists, just update the token:
            $localUser->remember_token = Str::random(15);
            $localUser->token = $user->token;
            // $localUser->isFirstTimeVisit = FALSE; //TODO
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

    private function decodeIdToken($idToken)
    {
        // Extract the second part of the ID token (payload)
        $payload = explode('.', $idToken)[1];

        // Base64 decode the payload
        $decodedPayload = base64_decode($payload);

        // Convert the decoded payload to an associative array
        $claims = json_decode($decodedPayload, true);

        return $claims;
    }


    // private function decodeIdToken($idToken)
    // {
    //     // Explode the ID token by '.'
    //     $parts = explode('.', $idToken);

    //     // Check if the token has the necessary parts
    //     if (count($parts) !== 3) {
    //         // Invalid token format
    //         return null; // Or handle the error in an appropriate way
    //     }

    //     // Extract the second part of the ID token (payload)
    //     $payload = $parts[1];

    //     // Base64 decode the payload
    //     $decodedPayload = base64_decode($payload);

    //     // Convert the decoded payload to an associative array
    //     $claims = json_decode($decodedPayload, true);

    //     return $claims;
    // }

    public function bypassAuthentication()
    {
        dd('bypass authentication ');

    }

    public function logout()
    {
        // Clear Laravel authentication
        // dd(Auth::check());
        // if (Auth::check()){
        //     Auth::logout();
        //     // Redirect to Okta logout
        //     return redirect()->away('https://portal-test.edpass.sa.edu.au/oauth2/default/v1/logout?id_token_hint=' . Auth::user()->token . '&post_logout_redirect_uri=' . urlencode(route('login')));
        // }

        // Redirect to Okta logout URL
        // return Socialite::driver('okta')->redirect();

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
