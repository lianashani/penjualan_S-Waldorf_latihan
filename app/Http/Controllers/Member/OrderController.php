<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MemberOrder;
use App\Models\MemberOrderItem;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $order = MemberOrder::with(['items.produk', 'member'])
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
        $member = Auth::guard('member')->user();
        $cart = session()->get('member_cart', []);
        if (empty($cart)) {
            return redirect()->route('member.cart.index')->with('error', 'Keranjang kosong');
        }

        $paymentMethod = $request->input('payment_method', 'in_store');

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

                // Check stock availability
                if ($p->stok < 1) {
                    throw new \Exception("Produk {$p->nama_produk} stok habis");
                }
                if ($p->stok < $qty) {
                    throw new \Exception("Stok tidak cukup untuk {$p->nama_produk}. Stok tersedia: {$p->stok}");
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

            // Generate order number
            $orderNumber = 'ORD' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid((string)$member->id_member, true)), 0, 6));

            // Set status based on payment method
            $status = ($paymentMethod === 'midtrans') ? 'pending' : 'awaiting_preparation';

            $order = MemberOrder::create([
                'id_member' => $member->id_member,
                'payment_method' => $paymentMethod,
                'payment_status' => ($paymentMethod === 'midtrans') ? 'pending' : null,
                'order_number' => $orderNumber,
                'status' => $status,
                'subtotal' => $total,
                'total' => $total,
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

            // If Midtrans payment, redirect to payment creation
            if ($paymentMethod === 'midtrans') {
                return redirect()->route('member.payment.create', ['orderId' => $order->id_order]);
            }

            // If in-store, go to order detail
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

    public function cancel($id)
    {
        $member = Auth::guard('member')->user();
        $order = MemberOrder::where('id_member', $member->id_member)->findOrFail($id);

        // Debug logging
        Log::info('Cancel order attempt', [
            'order_id' => $order->id_order,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => $order->payment_method
        ]);

        // Only allow cancellation if order is pending or awaiting_preparation
        if (!in_array($order->status, ['pending', 'awaiting_preparation'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan pada status ini. Status saat ini: ' . $order->status
            ]);
        }

        // Don't allow cancellation if already paid
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan yang sudah dibayar tidak dapat dibatalkan. Silakan hubungi kasir.'
            ]);
        }

        DB::beginTransaction();
        try {
            // Restore product stock
            foreach ($order->items as $item) {
                // Check if order item has variant
                if ($item->id_variant) {
                    // Restore variant stock
                    $variant = \App\Models\ProductVariant::find($item->id_variant);
                    if ($variant) {
                        $variant->increment('stok', $item->qty);
                        // Update product total_stok
                        $variant->updateProductTotals();
                    }
                } else {
                    // Restore product stock (for products without variants)
                    $product = Produk::find($item->id_produk);
                    if ($product) {
                        $product->increment('stok', $item->qty);
                    }
                }
            }

            // Update order status only
            $order->update([
                'status' => 'cancelled'
            ]);

            DB::commit();

            Log::info('Order cancelled successfully', ['order_id' => $order->id_order]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan.'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Cancel order failed', [
                'order_id' => $order->id_order,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
            ]);
        }
    }
}
