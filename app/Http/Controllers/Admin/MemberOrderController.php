<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberOrder;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberOrderController extends Controller
{
    public function index()
    {
        $orders = MemberOrder::with('member')
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('admin.member_orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = MemberOrder::with(['member','items.produk'])->findOrFail($id);
        return view('admin.member_orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:awaiting_preparation,ready_for_pickup,completed,cancelled'
        ]);
        $order = MemberOrder::findOrFail($id);
        $prevStatus = $order->status;
        try {
            DB::beginTransaction();

            $order->status = $request->status;
            $order->save();

            // When completed, materialize a POS transaction if not already created
            if ($request->status === 'completed') {
                // Check existing linkage to avoid duplicate postings
                $existing = Penjualan::where('id_member_order', $order->id_order)->first();
                if (!$existing) {
                    // Ensure pelanggan linkage
                    $pelangganId = $order->member?->id_pelanggan;
                    if (!$pelangganId && $order->member) {
                        $pel = Pelanggan::where('email', $order->member->email)->first();
                        if (!$pel) {
                            $pel = Pelanggan::create([
                                'nama_pelanggan' => $order->member->nama_member,
                                'email' => $order->member->email,
                                'status' => 'aktif',
                                'tanggal_daftar' => now(),
                            ]);
                        }
                        $order->member->id_pelanggan = $pel->id_pelanggan;
                        $order->member->save();
                        $pelangganId = $pel->id_pelanggan;
                    }

                    $penjualan = Penjualan::create([
                        'id_user' => Auth::id(),
                        'id_pelanggan' => $pelangganId,
                        'id_promo' => null,
                        'total_bayar' => $order->total,
                        'kembalian' => 0,
                        'status_transaksi' => 'selesai',
                        'tanggal_transaksi' => now(),
                        'id_member_order' => $order->id_order,
                    ]);

                    // Create detail lines
                    $order->loadMissing('items');
                    foreach ($order->items as $it) {
                        DetailPenjualan::create([
                            'id_penjualan' => $penjualan->id_penjualan,
                            'id_produk' => $it->id_produk,
                            'qty' => $it->qty,
                            'harga_satuan' => $it->harga,
                            'subtotal' => $it->subtotal,
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Status pesanan diperbarui ke: ' . str_replace('_',' ', $request->status));
        } catch (\Throwable $e) {
            DB::rollBack();
            // revert status silently
            $order->status = $prevStatus;
            $order->save();
            return back()->with('error', 'Gagal memperbarui status/pos: ' . $e->getMessage());
        }
    }

    public function print($id)
    {
        $order = MemberOrder::with(['member','items.produk'])->findOrFail($id);
        return view('admin.member_orders.print', compact('order'));
    }
}
