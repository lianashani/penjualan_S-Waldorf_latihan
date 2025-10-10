<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = Kategori::withCount('produks')->get();
        
        $query = Produk::with('kategori')->where('stok', '>', 0);
        
        // Filter by category
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('id_kategori', $request->kategori);
        }
        
        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }
        
        // Sort
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'termurah':
                $query->orderBy('harga', 'asc');
                break;
            case 'termahal':
                $query->orderBy('harga', 'desc');
                break;
            case 'nama':
                $query->orderBy('nama_produk', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $produks = $query->paginate(12);
        
        return view('home.katalog.index', compact('produks', 'kategoris'));
    }
    
    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        $relatedProducts = Produk::where('id_kategori', $produk->id_kategori)
            ->where('id_produk', '!=', $id)
            ->where('stok', '>', 0)
            ->limit(4)
            ->get();
        
        return view('home.katalog.show', compact('produk', 'relatedProducts'));
    }

    // Keranjang Functions
    public function addToCart(Request $request)
    {
        $produk = Produk::findOrFail($request->id_produk);
        $qty = $request->qty ?? 1;

        if ($produk->stok < $qty) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cart = session()->get('keranjang', []);
        
        if (isset($cart[$produk->id_produk])) {
            $cart[$produk->id_produk]['qty'] += $qty;
        } else {
            $cart[$produk->id_produk] = [
                'id_produk' => $produk->id_produk,
                'nama_produk' => $produk->nama_produk,
                'harga' => $produk->harga,
                'qty' => $qty,
                'gambar' => $produk->gambar,
                'barcode' => $produk->barcode
            ];
        }

        session()->put('keranjang', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function viewCart()
    {
        $cart = session()->get('keranjang', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }
        
        return view('home.katalog.keranjang', compact('cart', 'total'));
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('keranjang', []);
        
        if (isset($cart[$request->id_produk])) {
            $cart[$request->id_produk]['qty'] = $request->qty;
            session()->put('keranjang', $cart);
        }

        return back()->with('success', 'Keranjang diupdate!');
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('keranjang', []);
        
        if (isset($cart[$request->id_produk])) {
            unset($cart[$request->id_produk]);
            session()->put('keranjang', $cart);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function checkout()
    {
        $cart = session()->get('keranjang', []);
        
        if (empty($cart)) {
            return redirect()->route('katalog.index')->with('error', 'Keranjang kosong!');
        }

        // Redirect to penjualan create with cart data
        return redirect()->route('penjualan.create')->with('cart_data', $cart);
    }
}
