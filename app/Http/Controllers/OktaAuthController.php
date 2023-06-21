<?php

// namespace App\Http\Controllers;

// use Clousre;
// use Illuminate\Http\Request;
// use Okta\JwtVerifier\JwtVerifier;
// use Okta\JwtVerifier\Adaptors\SpomkyLabsJose;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Session;
// use Illuminate\Support\Facades\Cookie;

class OktaAuthController extends Controller
{
    public function authenticate(Request $request, Response $respone)
    {
        // // 1. Obtain user information from the Okta access token
        // $token = $request->bearerToken();
        // // Extract user information from the token as per your token format
        // $userEmail = "EdSparkTest.IT537@test-schools.sa.edu.au";// Extract the user's email from the token or other relevant identifier

        // // 2. Authenticate the user in Laravel Filament
        // $user = User::where('email', $userEmail)->first(); // Replace 'User' with your User model class
        // if ($user) {
        //     Auth::login($user,true);
        //     // The user is now authenticated in Laravel Filament backend

        //     // Proceed with any additional logic or redirect to the admin panel
        //     if (Auth::check()) {
        //         // echo "The user has been successfully authenticated";
        //         return response()->json([
        //             'status' => 'success',
        //             'url' => route('filament.auth.login'),
        //             'loginStatus' => Auth::check(),
        //             // 'url' => route('/admin')
        //         ]);
        //         // return redirect()->intended('http://localhost:8000/admin/');

        //         // return route('filament.auth.login');
        //     }else{
        //         return response()->json([
        //             'status' => 'failed',
        //             'url' => route('filament.auth.login'),
        //             'loginStatus' => Auth::check()
        //             // 'url' => route('/admin')
        //         ]);
        //         echo "The user is not been authenticated";
        //     }

        // } else {
        //     // User not found, handle the error appropriately
        // }
        // dd($request);
        // dd($request->header());
        // if (! $request->header('Authorization')) {
        //     return false;
        // }

        // $authType = null;
        // $authData = null;

        // // // Extract the auth type and the data from the Authorization header.
        // @list($authType, $authData) = explode(" ", $request->header('Authorization'), 2);

        // // If the Authorization Header is not a bearer type, return a 401.
        // if ($authType != 'Bearer') {
        //     return false;
        // }

        // // dd($authType);
        // dd($authData);
        // // Attempt authorization with the provided token
        // try {

        //     // // Setup the JWT Verifier
        //     // $jwtVerifier = (new \Okta\JwtVerifier\JwtVerifierBuilder())
        //     //                 ->setAdaptor(new \Okta\JwtVerifier\Adaptors\SpomkyLabsJose())
        //     //                 ->setAudience('api://default')
        //     //                 ->setClientId('{yourClientId}')
        //     //                 ->setIssuer('{yourIssuerUrl}')
        //     //                 ->build();

        //     // // Verify the JWT from the Authorization Header.
        //     // $jwt = $jwtVerifier->verify($authData);
        // } catch (\Exception $e) {

        //     // You encountered an error, return a 401.
        //     return false;
        // }

        // return true;

        $userEmail = "EdSparkTest.IT537@test-schools.sa.edu.au";// Extract the user's email from the token or other relevant identifier
        // 2. Authenticate the user in Laravel Filament
        $user = User::where('email', $userEmail)->first(); // Replace 'User' with your User model class
        if ($user) {
            Auth::login($user,true);
            if (Auth::check()){
                // session(['authenticated_user_id' => Auth::id()]);

                // $request->session()->put('authenticated_user_id', Auth::id());
                // echo 'User authenticated';

                //create session data
                Session::put(['authenticated_user_id' => Auth::id()]);
                $cookie = cookie('authenticated_user_id', Auth::id(), 30);
                // $cookie = Cookie::queue('authenticated_user_id', Auth::id(), 1440);
                // dd($cookie);             ;

                return response()->json([
                    'status' => 'success',
                    'message' => 'User authenticated successfully',
                    'authenticated_user_id' => Auth::id(),
                ])->withCookie($cookie);
            }else{
                echo 'User unauthenticated';
            }
        }

    }



}





