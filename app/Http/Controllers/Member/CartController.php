<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('member_cart', []);
        // Enrich with product data
        $productIds = collect($cart)->pluck('id_produk')->all();
        $products = Produk::whereIn('id_produk', $productIds)->get()->keyBy('id_produk');
        $items = collect($cart)->map(function ($item) use ($products) {
            $p = $products[$item['id_produk']] ?? null;
            return [
                'id_produk' => $item['id_produk'],
                'qty' => $item['qty'],
                'nama' => $p?->nama_produk,
                'gambar' => $p?->gambar,
                'harga' => $p?->harga ?? 0,
                'subtotal' => ($p?->harga ?? 0) * $item['qty'],
            ];
        });
        $total = $items->sum('subtotal');
        return view('member.cart.index', compact('items','total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'qty' => 'nullable|integer|min:1'
        ]);
        $qty = (int)($request->qty ?? 1);
        $cart = session()->get('member_cart', []);
        $idx = collect($cart)->search(fn($i) => $i['id_produk'] == $request->id_produk);
        if ($idx !== false) {
            $cart[$idx]['qty'] += $qty;
        } else {
            $cart[] = ['id_produk' => (int)$request->id_produk, 'qty' => $qty];
        }
        session()->put('member_cart', $cart);
        return redirect()->route('member.cart.index')->with('success','Produk ditambahkan ke keranjang');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'qty' => 'required|integer|min:1'
        ]);
        $cart = session()->get('member_cart', []);
        foreach ($cart as &$i) {
            if ($i['id_produk'] == $request->id_produk) {
                $i['qty'] = (int)$request->qty;
                break;
            }
        }
        session()->put('member_cart', $cart);
        return back()->with('success','Keranjang diperbarui');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|integer'
        ]);
        $cart = collect(session()->get('member_cart', []))
            ->reject(fn($i) => $i['id_produk'] == (int)$request->id_produk)
            ->values()->all();
        session()->put('member_cart', $cart);
        return back()->with('success','Produk dihapus dari keranjang');
    }
}
