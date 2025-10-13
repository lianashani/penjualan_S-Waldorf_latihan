<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberOrder;
use App\Models\MemberOrderItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();
        $orders = MemberOrder::with('items.produk')
            ->where('id_member', $member->id_member)
            ->orderByDesc('created_at')
            ->get();
        return view('member.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $member = Auth::guard('member')->user();
        $order = MemberOrder::with('items.produk')
            ->where('id_member', $member->id_member)
            ->findOrFail($id);
        return view('member.orders.show', compact('order'));
    }

    public function track($id)
    {
        $member = Auth::guard('member')->user();
        $order = MemberOrder::where('id_member', $member->id_member)->findOrFail($id);
        $timeline = [];
        // New in-store pickup flow statuses
        $statusOrder = ['awaiting_preparation','ready_for_pickup','completed','cancelled'];
        foreach ($statusOrder as $s) {
            $timeline[] = [
                'status' => $s,
                'reached' => $order->status === $s || array_search($s, $statusOrder) <= array_search($order->status, $statusOrder),
            ];
        }
        return response()->json(['order_id' => $order->id_order, 'status' => $order->status, 'timeline' => $timeline]);
    }

    public function checkout(Request $request)
    {
        // In-store payment flow: no online payment selection

        $member = Auth::guard('member')->user();
        $cart = session()->get('member_cart', []);
        if (empty($cart)) {
            return redirect()->route('member.cart.index')->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();
        try {
            // Load products
            $productIds = collect($cart)->pluck('id_produk')->all();
            $products = Produk::whereIn('id_produk', $productIds)->get()->keyBy('id_produk');

            $total = 0;
            $itemsData = [];
            foreach ($cart as $c) {
                $p = $products[$c['id_produk']] ?? null;
                if (!$p) continue;
                $qty = (int)$c['qty'];
                if ($p->stok < $qty) {
                    throw new \Exception("Stok tidak cukup untuk {$p->nama_produk}");
                }
                $subtotal = $p->harga * $qty;
                $total += $subtotal;
                $itemsData[] = [
                    'id_produk' => $p->id_produk,
                    'qty' => $qty,
                    'harga' => $p->harga,
                    'subtotal' => $subtotal,
                ];
            }

            // Set in-store pickup flow defaults
            $status = 'awaiting_preparation';
            $debtDue = null;

            // Generate order number e.g. ORD20251013-ABC123
            $orderNumber = 'ORD' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid((string)$member->id_member, true)), 0, 6));

            $order = MemberOrder::create([
                'id_member' => $member->id_member,
                'payment_method' => 'in_store',
                'order_number' => $orderNumber,
                'status' => $status,
                'subtotal' => $total,
                'total' => $total,
                'debt_due_at' => $debtDue,
            ]);

            foreach ($itemsData as $it) {
                MemberOrderItem::create([
                    'id_order' => $order->id_order,
                    'id_produk' => $it['id_produk'],
                    'qty' => $it['qty'],
                    'harga' => $it['harga'],
                    'subtotal' => $it['subtotal'],
                ]);
                // reduce stock
                $products[$it['id_produk']]->decrement('stok', $it['qty']);
            }

            // Clear cart
            session()->forget('member_cart');

            DB::commit();
            return redirect()->route('member.orders.show', $order->id_order)->with('success', 'Checkout berhasil');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('member.cart.index')->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function receipt($id)
    {
        $member = Auth::guard('member')->user();
        $order = MemberOrder::with(['items.produk','member'])
            ->where('id_member', $member->id_member)
            ->findOrFail($id);
        return view('member.orders.receipt', compact('order'));
    }
}
