<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PartnerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('partner.login');
    }

    public function login(Request $request)
    {
        // dd($request);
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Auth::login(Auth::user());
            return redirect()->intended('/partners');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
