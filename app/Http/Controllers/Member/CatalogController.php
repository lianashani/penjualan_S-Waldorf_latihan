<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class CatalogController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->where('stok', '>', 0)->orderBy('nama_produk')->get();
        return view('member.catalog.index', compact('produks'));
    }

    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        return view('member.catalog.show', compact('produk'));
    }
}
