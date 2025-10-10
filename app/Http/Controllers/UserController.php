<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('nama_user')->get();
        return view('home.user.index', compact('users'));
    }

    public function create()
    {
        return view('home.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,kasir'
        ]);

        User::create([
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'password' => Hash::make('admin123'), // Default password
            'role' => $request->role,
            'must_change_password' => true // Force change password
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan! Password default: admin123');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('home.user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('home.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'role' => 'required|in:admin,kasir'
        ]);

        $data = [
            'nama_user' => $request->nama_user,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Reset password if requested
        if ($request->has('reset_password')) {
            $data['password'] = Hash::make('admin123');
            $data['must_change_password'] = true;
        }

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting own account
        if ($user->id_user == auth()->id()) {
            return redirect()->route('user.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
