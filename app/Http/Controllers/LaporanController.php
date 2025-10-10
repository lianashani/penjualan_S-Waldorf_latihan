<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $penjualans = Penjualan::with(['user', 'pelanggan', 'promo', 'detailPenjualans.produk'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalPendapatan = $penjualans->sum('total_bayar');
        $totalTransaksi = $penjualans->count();

        return view('home.laporan.index', compact('penjualans', 'totalPendapatan', 'totalTransaksi', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $penjualans = Penjualan::with(['user', 'pelanggan', 'promo', 'detailPenjualans.produk'])
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalPendapatan = $penjualans->sum('total_bayar');
        $totalTransaksi = $penjualans->count();

        return view('home.laporan.print', compact('penjualans', 'totalPendapatan', 'totalTransaksi', 'startDate', 'endDate'));
    }
}
