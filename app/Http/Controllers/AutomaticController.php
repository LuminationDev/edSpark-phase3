<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutomaticController extends Controller
{
    public function initialLogin(Request $request){
        // dd(Auth::user()->id);
        // dd(Session::all());
        // dd(Session::get('currentUser'));
        // dd($request->session()->get('currentUser'));

        Auth::loginUsingId(33, true);
        if( Auth::check()) {
                return redirect()->intended('http://localhost:8000/dashboard',302);
                // return response()->json();
                // return redirect()->intended('http://localhost:8000/dashboard');
            }else{
                dd('there');
            }
        }
}
