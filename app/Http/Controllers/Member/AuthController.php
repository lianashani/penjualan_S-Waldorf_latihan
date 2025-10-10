<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->route('member.dashboard');
        }
        return view('member.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('member')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'aktif'
        ], $request->remember)) {
            return redirect()->intended(route('member.dashboard'));
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function showRegister()
    {
        return view('member.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_member' => 'required|string|max:100',
            'email' => 'required|email|unique:members,email',
            'password' => 'required|min:6|confirmed',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        $member = Member::create([
            'nama_member' => $request->nama_member,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'status' => 'aktif'
        ]);

        Auth::guard('member')->login($member);

        return redirect()->route('member.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di S&Waldorf!');
    }

    public function logout()
    {
        Auth::guard('member')->logout();
        return redirect()->route('member.login')
            ->with('success', 'Berhasil logout!');
    }
}
