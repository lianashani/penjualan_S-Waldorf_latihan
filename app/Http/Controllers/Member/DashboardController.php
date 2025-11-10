<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\MemberOrder;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();
        // Derive total spending from POS transactions linked to this member's pelanggan
        $totalSpent = 0;
        if (!empty($member->id_pelanggan)) {
            $totalSpent = Penjualan::where('id_pelanggan', $member->id_pelanggan)
                ->where('status_transaksi', 'selesai')
                ->sum('total_bayar');
        }
        $lastOrder = MemberOrder::with(['items.produk'])
            ->where('id_member', $member->id_member)
            ->orderByDesc('created_at')
            ->first();

        // New Arrivals - Latest 8 products with images
        $newArrivals = Produk::with(['images' => function($query) {
                $query->where('is_active', true)->orderBy('is_primary', 'desc')->orderBy('urutan');
            }])
            ->where('is_active', 1)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        // Featured Products - Top rated or best sellers with images
        $featuredProducts = Produk::with(['images' => function($query) {
                $query->where('is_active', true)->orderBy('is_primary', 'desc')->orderBy('urutan');
            }])
            ->where('is_active', 1)
            ->orderByDesc('stok')
            ->take(4)
            ->get();

        return view('member.dashboard', compact('member', 'totalSpent', 'lastOrder', 'newArrivals', 'featuredProducts'));
    }
}
