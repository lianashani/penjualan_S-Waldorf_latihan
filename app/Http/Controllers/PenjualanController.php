<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use App\Models\ProductVariant;
use App\Models\Pelanggan;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Transaction;

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
            'payment_method' => 'required|in:cash,in_store,qris',
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produks,id_produk',
            'items.*.qty' => 'required|integer|min:1',
            'total_bayar' => 'nullable|numeric|min:0',
            'qris_transaction_id' => 'nullable|string',
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

                // Determine if item refers to a variant
                $variant = null;
                if (!empty($item['ukuran']) || !empty($item['warna'])) {
                    $variant = ProductVariant::where('id_produk', $produk->id_produk)
                        ->when(!empty($item['ukuran']), fn($q) => $q->where('ukuran', $item['ukuran']))
                        ->when(!empty($item['warna']), fn($q) => $q->where('warna', $item['warna']))
                        ->where('is_active', true)
                        ->first();
                } elseif ($produk->has_variants) {
                    // If product has variants but no variant specified, get the first available variant
                    $variant = ProductVariant::where('id_produk', $produk->id_produk)
                        ->where('is_active', true)
                        ->where('stok', '>=', $item['qty'])
                        ->orderBy('stok', 'desc')
                        ->first();

                    if (!$variant) {
                        throw new \Exception("Tidak ada varian tersedia untuk {$produk->nama_produk} dengan stok yang cukup");
                    }
                }

                if ($variant) {
                    // Check variant stock
                    if ($variant->stok < $item['qty']) {
                        throw new \Exception("Stok varian ({$variant->ukuran}/{$variant->warna}) untuk {$produk->nama_produk} tidak mencukupi. Tersedia: {$variant->stok}");
                    }
                    $hargaSatuan = $variant->harga ?? $produk->harga;
                } else {
                    // Non-variant path: check product stock
                    if ($produk->stok < $item['qty']) {
                        throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi. Tersedia: {$produk->stok}");
                    }
                    $hargaSatuan = $produk->harga;
                }

                $subtotal += $hargaSatuan * $item['qty'];
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

            // Validate payment amount based on method
            $paymentMethod = $request->payment_method;
            if ($paymentMethod === 'cash') {
                if (!$request->total_bayar || $request->total_bayar < $total_setelah_diskon) {
                    throw new \Exception("Jumlah pembayaran kurang dari total yang harus dibayar.");
                }
            } elseif ($paymentMethod === 'qris') {
                // For QRIS, must have transaction ID
                if (!$request->qris_transaction_id) {
                    throw new \Exception("QRIS transaction ID tidak ditemukan. Silakan generate QRIS terlebih dahulu.");
                }
                // Auto set to total
                $request->merge(['total_bayar' => $total_setelah_diskon]);
            } else {
                // For in_store, auto set to total
                $request->merge(['total_bayar' => $total_setelah_diskon]);
            }

            // Calculate change
            $kembalian = $request->total_bayar - $total_setelah_diskon;

            // Create penjualan record
            $penjualan = Penjualan::create([
                'id_user' => Auth::id() ?? 1, // Default to 1 if not authenticated
                'id_pelanggan' => $request->id_pelanggan,
                'id_promo' => $request->id_promo,
                'payment_method' => $paymentMethod,
                'total_bayar' => $total_setelah_diskon, // Total transaction amount
                'kembalian' => $kembalian,
                'status_transaksi' => 'selesai',
                'tanggal_transaksi' => now(),
            ]);

            // Create detail penjualan and update stock
            foreach ($items as $item) {
                $produk = Produk::findOrFail($item['id_produk']);

                // Resolve variant if provided
                $variant = null;
                if (!empty($item['ukuran']) || !empty($item['warna'])) {
                    $variant = ProductVariant::where('id_produk', $produk->id_produk)
                        ->when(!empty($item['ukuran']), fn($q) => $q->where('ukuran', $item['ukuran']))
                        ->when(!empty($item['warna']), fn($q) => $q->where('warna', $item['warna']))
                        ->where('is_active', true)
                        ->first();
                } elseif ($produk->has_variants) {
                    // If product has variants but no variant specified, get the first available variant
                    $variant = ProductVariant::where('id_produk', $produk->id_produk)
                        ->where('is_active', true)
                        ->where('stok', '>=', $item['qty'])
                        ->orderBy('stok', 'desc')
                        ->first();
                }

                $hargaSatuan = $variant ? ($variant->harga ?? $produk->harga) : $produk->harga;

                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_produk' => $item['id_produk'],
                    'ukuran' => $variant ? $variant->ukuran : ($item['ukuran'] ?? null),
                    'warna' => $variant ? $variant->warna : ($item['warna'] ?? null),
                    'qty' => $item['qty'],
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $hargaSatuan * $item['qty'],
                ]);

                // Update stock: variant first, else product
                if ($variant) {
                    $variant->decrement('stok', $item['qty']);
                    // Optionally refresh product totals if needed
                    if (method_exists($variant, 'updateProductTotals')) {
                        $variant->refresh();
                        $variant->updateProductTotals();
                    }
                } else {
                    $produk->decrement('stok', $item['qty']);
                }
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

    public function print($id)
    {
        $penjualan = Penjualan::with(['user', 'pelanggan', 'promo', 'detailPenjualans.produk'])
            ->findOrFail($id);

        // Calculate discount info
        $subtotal = $penjualan->detailPenjualans->sum('subtotal');
        $diskon = 0;

        if ($penjualan->promo) {
            $diskon = $subtotal * ($penjualan->promo->persen / 100);
        }

        return view('home.penjualan.print', compact('penjualan', 'subtotal', 'diskon'));
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

    public function generateQris(Request $request)
    {
        try {
            $total = $request->total;

            if (!$total || $total <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran tidak valid'
                ], 400);
            }

            // Set Midtrans configuration
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Generate unique order ID
            $orderId = 'QRIS-' . time() . '-' . rand(1000, 9999);

            // Create transaction
            $params = [
                'payment_type' => 'qris',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $total,
                ]
            ];

            $response = CoreApi::charge($params);

            if (isset($response->actions) && is_array($response->actions)) {
                foreach ($response->actions as $action) {
                    if ($action->name === 'generate-qr-code' && isset($action->url)) {
                        return response()->json([
                            'success' => true,
                            'qr_string' => $action->url,
                            'transaction_id' => $orderId,
                            'status' => $response->transaction_status ?? 'pending'
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak ditemukan dalam response'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkQrisStatus(Request $request)
    {
        try {
            $transactionId = $request->transaction_id;

            if (!$transactionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction ID tidak ditemukan'
                ], 400);
            }

            // Set Midtrans configuration
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Check transaction status
            $status = Transaction::status($transactionId);

            // Convert to array if it's an object
            $statusData = is_object($status) ? (array) $status : $status;

            return response()->json([
                'success' => true,
                'status' => $statusData['transaction_status'] ?? 'unknown',
                'payment_type' => $statusData['payment_type'] ?? null,
                'transaction_time' => $statusData['transaction_time'] ?? null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
