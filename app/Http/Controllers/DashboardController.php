<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Penjualan;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $userCount = User::count();
            $jenisCount = Kategori::count();
            $produkCount = Produk::count();
            $penjualanCount = Penjualan::where('status_transaksi', 'selesai')->count();

            // Total revenue from completed sales
            $totalRevenue = Penjualan::where('status_transaksi', 'selesai')
                ->sum('total_bayar') ?? 0;

            // Low stock products (stock <= 10)
            $lowStockCount = Produk::where('stok', '<=', 10)->count();

            // Produk stok status
            $produkList = Produk::with('kategori')
                ->orderBy('stok', 'asc')
                ->limit(10)
                ->get();

            // Riwayat penjualan terbaru
            $penjualanList = Penjualan::with(['user', 'pelanggan', 'promo'])
                ->orderBy('tanggal_transaksi', 'desc')
                ->limit(10)
                ->get();

            return view('home.dashboard', compact(
                'userCount', 'jenisCount', 'produkCount', 'penjualanCount', 
                'totalRevenue', 'lowStockCount', 'produkList', 'penjualanList'
            ));
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Dashboard Error: ' . $e->getMessage());
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }
}
