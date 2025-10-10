<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showProfile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . auth()->id() . ',id_user'
        ]);

        auth()->user()->update([
            'nama_user' => $request->nama_user,
            'email' => $request->email
        ]);

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diupdate!');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        // Check current password
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai!']);
        }

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => false
        ]);

        return redirect()->route('dashboard.index')
            ->with('success', 'Password berhasil diubah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout!');
    }
}
