<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with(['user', 'pelanggan', 'promo', 'detailPenjualans.produk'])
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(10);
        
        return view('home.penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $produks = Produk::with('kategori')->where('stok', '>', 0)->get();
        $pelanggans = Pelanggan::where('status', 'aktif')->get();
        $promos = Promo::where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->get();
        
        // Get cart data from session if coming from checkout
        $cartItems = session()->get('keranjang', []);
        
        return view('home.penjualan.create', compact('produks', 'pelanggans', 'promos', 'cartItems'));
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'id_pelanggan' => 'nullable|exists:pelanggans,id_pelanggan',
            'id_promo' => 'nullable|exists:promos,id_promo',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produks,id_produk',
            'items.*.qty' => 'required|integer|min:1',
            'total_bayar' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Periksa input Anda.');
        }

        DB::beginTransaction();
        try {
            // Calculate subtotal from items
            $subtotal = 0;
            $items = $request->items;
            
            foreach ($items as $item) {
                $produk = Produk::findOrFail($item['id_produk']);
                
                // Check stock availability
                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi. Tersedia: {$produk->stok}");
                }
                
                $subtotal += $produk->harga * $item['qty'];
            }

            // Apply discount if promo exists
            $diskon = 0;
            $total_setelah_diskon = $subtotal;
            
            if ($request->id_promo) {
                $promo = Promo::findOrFail($request->id_promo);
                
                // Validate promo is active and valid
                if (!$promo->isValid()) {
                    throw new \Exception("Promo tidak valid atau sudah kadaluarsa.");
                }
                
                // Validate discount percentage (must be <= 100%)
                if ($promo->persen > 100) {
                    throw new \Exception("Persentase diskon tidak boleh melebihi 100%.");
                }
                
                // Calculate discount: diskon = subtotal * (persen / 100)
                $diskon = $subtotal * ($promo->persen / 100);
                $total_setelah_diskon = $subtotal - $diskon;
            }

            // Validate payment amount
            if ($request->total_bayar < $total_setelah_diskon) {
                throw new \Exception("Jumlah pembayaran kurang dari total yang harus dibayar.");
            }

            // Calculate change
            $kembalian = $request->total_bayar - $total_setelah_diskon;

            // Create penjualan record
            $penjualan = Penjualan::create([
                'id_user' => Auth::id() ?? 1, // Default to 1 if not authenticated
                'id_pelanggan' => $request->id_pelanggan,
                'id_promo' => $request->id_promo,
                'total_bayar' => $total_setelah_diskon,
                'kembalian' => $kembalian,
                'status_transaksi' => 'selesai',
                'tanggal_transaksi' => now(),
            ]);

            // Create detail penjualan and update stock
            foreach ($items as $item) {
                $produk = Produk::findOrFail($item['id_produk']);
                
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_produk' => $item['id_produk'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $produk->harga * $item['qty'],
                ]);

                // Update product stock
                $produk->decrement('stok', $item['qty']);
            }

            DB::commit();

            return redirect()->route('penjualan.show', $penjualan->id_penjualan)
                ->with('success', 'Transaksi berhasil disimpan!')
                ->with('diskon', $diskon)
                ->with('total_setelah_diskon', $total_setelah_diskon);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['user', 'pelanggan', 'promo', 'detailPenjualans.produk'])
            ->findOrFail($id);
        
        // Calculate discount info
        $subtotal = $penjualan->detailPenjualans->sum('subtotal');
        $diskon = 0;
        
        if ($penjualan->promo) {
            $diskon = $subtotal * ($penjualan->promo->persen / 100);
        }
        
        return view('home.penjualan.show', compact('penjualan', 'subtotal', 'diskon'));
    }

    public function calculateDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'harga' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Diskon harus antara 0-100% dan harga harus lebih dari 0.',
                'errors' => $validator->errors()
            ], 422);
        }

        $harga = $request->harga;
        $diskon_persen = $request->diskon;

        // Calculate discount amount: nilai_diskon = harga * (diskon / 100)
        $nilai_diskon = $harga * ($diskon_persen / 100);
        
        // Calculate total after discount: total_harga = harga - nilai_diskon
        $total_harga = $harga - $nilai_diskon;

        return response()->json([
            'success' => true,
            'data' => [
                'harga_awal' => number_format($harga, 2, ',', '.'),
                'diskon_persen' => $diskon_persen,
                'nilai_diskon' => number_format($nilai_diskon, 2, ',', '.'),
                'total_harga' => number_format($total_harga, 2, ',', '.'),
                'nilai_diskon_raw' => $nilai_diskon,
                'total_harga_raw' => $total_harga,
            ]
        ]);
    }
}
