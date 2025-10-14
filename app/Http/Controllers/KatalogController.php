<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\ProductVariant;
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
        $qty = max(1, (int)($request->qty ?? 1));

        $cart = session()->get('keranjang', []);

        // If variant specified (ukuran/warna), find matching variant
        $ukuran = $request->input('ukuran');
        $warna = $request->input('warna');

        if ($ukuran || $warna) {
            $variant = ProductVariant::where('id_produk', $produk->id_produk)
                ->when($ukuran, fn($q) => $q->where('ukuran', $ukuran))
                ->when($warna, fn($q) => $q->where('warna', $warna))
                ->where('is_active', true)
                ->first();

            if (!$variant) {
                // Create variant on the fly if not exists
                $colorCodes = [
                    'Hitam' => '#000000',
                    'Putih' => '#FFFFFF',
                    'Merah' => '#DC2626',
                    'Biru' => '#2563EB',
                    'Hijau' => '#16A34A',
                    'Biru Tua' => '#1E3A8A',
                    'Abu-abu' => '#6B7280',
                    'Coklat' => '#92400E',
                    'Pink' => '#EC4899',
                    'Kuning' => '#EAB308'
                ];

                $variant = ProductVariant::create([
                    'id_produk' => $produk->id_produk,
                    'ukuran' => $ukuran ?? '-',
                    'warna' => $warna ?? '-',
                    'kode_warna' => $colorCodes[$warna] ?? '#CCCCCC',
                    'stok' => 10,
                    'harga' => $produk->harga,
                    'is_active' => true,
                ]);
            }

            if ($variant->stok < $qty) {
                return back()->with('error', 'Stok varian tidak mencukupi!');
            }

            // Use a composite key so different variants of the same product can coexist
            $key = $produk->id_produk . '|' . ($variant->id_variant ?? ($ukuran.'-'.$warna));

            if (isset($cart[$key])) {
                $cart[$key]['qty'] += $qty;
            } else {
                $cart[$key] = [
                    'id_produk' => $produk->id_produk,
                    'nama_produk' => $produk->nama_produk,
                    'harga' => $variant->harga ?? $produk->harga,
                    'qty' => $qty,
                    'gambar' => $produk->gambar,
                    'barcode' => $produk->barcode,
                    'variant' => [
                        'id' => $variant->id_variant ?? null,
                        'ukuran' => $variant->ukuran ?? $ukuran,
                        'warna' => $variant->warna ?? $warna,
                        'sku' => $variant->sku ?? null,
                    ],
                ];
            }

            session()->put('keranjang', $cart);
            return back()->with('success', 'Produk varian ditambahkan ke keranjang!');
        }

        // Non-variant path
        if ($produk->stok < $qty) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

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
        $key = $request->input('key');
        $qty = max(1, (int)$request->input('qty'));

        if ($key && isset($cart[$key])) {
            $cart[$key]['qty'] = $qty;
            session()->put('keranjang', $cart);
            return back()->with('success', 'Keranjang diupdate!');
        }

        // Fallback legacy by id_produk
        $idProduk = $request->input('id_produk');
        if ($idProduk && isset($cart[$idProduk])) {
            $cart[$idProduk]['qty'] = $qty;
            session()->put('keranjang', $cart);
        }

        return back()->with('success', 'Keranjang diupdate!');
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('keranjang', []);

        $key = $request->input('key');
        if ($key && isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('keranjang', $cart);
            return back()->with('success', 'Produk dihapus dari keranjang!');
        }

        // Fallback legacy by id_produk
        $idProduk = $request->input('id_produk');
        if ($idProduk && isset($cart[$idProduk])) {
            unset($cart[$idProduk]);
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
