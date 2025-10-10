<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('membership')->orderBy('nama_pelanggan')->get();
        return view('home.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        $memberships = Membership::all();
        return view('home.pelanggan.create', compact('memberships'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggans,email',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:aktif,nonaktif',
            'id_membership' => 'nullable|exists:memberships,id_membership'
        ]);

        $data = $request->all();
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        Pelanggan::create($data);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['membership', 'penjualans'])->findOrFail($id);
        return view('home.pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $memberships = Membership::all();
        return view('home.pelanggan.edit', compact('pelanggan', 'memberships'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggans,email,' . $id . ',id_pelanggan',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:aktif,nonaktif',
            'id_membership' => 'nullable|exists:memberships,id_membership'
        ]);

        $data = $request->except('password');
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }
}
