<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect('/');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user must change password
            if (Auth::user()->must_change_password) {
                return redirect()->route('change-password')
                    ->with('warning', 'Anda harus mengubah password default terlebih dahulu!');
            }

            return redirect()->intended('/')
                ->with('success', 'Selamat datang, ' . Auth::user()->nama_user . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }
}
