<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::orderBy('tanggal_mulai', 'desc')->get();
        return view('home.promo.index', compact('promos'));
    }

    public function create()
    {
        return view('home.promo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promos,kode_promo',
            'persen' => 'required|numeric|min:0|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        Promo::create($request->all());

        return redirect()->route('promo.index')
            ->with('success', 'Promo berhasil ditambahkan!');
    }

    public function show($id)
    {
        $promo = Promo::with('penjualans')->findOrFail($id);
        return view('home.promo.show', compact('promo'));
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        return view('home.promo.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        
        $request->validate([
            'kode_promo' => 'required|string|max:50|unique:promos,kode_promo,' . $id . ',id_promo',
            'persen' => 'required|numeric|min:0|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        $promo->update($request->all());

        return redirect()->route('promo.index')
            ->with('success', 'Promo berhasil diupdate!');
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return redirect()->route('promo.index')
            ->with('success', 'Promo berhasil dihapus!');
    }
}
