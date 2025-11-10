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
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Show payment page for existing order
     */
    public function showPayment($orderId)
    {
        $member = Auth::guard('member')->user();
        $order = MemberOrder::with('items.produk')
            ->where('id_member', $member->id_member)
            ->findOrFail($orderId);

        // If order already has snap_token, use it
        if ($order->snap_token) {
            return view('member.payment', compact('order'));
        }

        // Generate new snap token
        try {
            $itemDetails = [];
            foreach ($order->items as $item) {
                $itemDetails[] = [
                    'id' => $item->id_produk,
                    'price' => (int)$item->harga,
                    'quantity' => $item->qty,
                    'name' => $item->produk->nama_produk,
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int)$order->total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $member->nama_member,
                    'email' => $member->email ?? 'member@swaldorf.com',
                    'phone' => $member->no_hp ?? '08123456789',
                ],
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va',
                    'permata_va', 'other_va', 'gopay', 'shopeepay'
                ],
                'callbacks' => [
                    'finish' => route('member.payment.finish'),
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return view('member.payment', compact('order'));

        } catch (\Throwable $e) {
            Log::error('Snap token generation failed: ' . $e->getMessage());
            return redirect()->route('member.orders.show', $orderId)
                ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Create order and get Midtrans Snap token
     */
    public function createPayment(Request $request)
    {
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
            $itemDetails = [];

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

                // Midtrans item details
                $itemDetails[] = [
                    'id' => $p->id_produk,
                    'price' => (int)$p->harga,
                    'quantity' => $qty,
                    'name' => $p->nama_produk,
                ];
            }

            // Generate order number
            $orderNumber = 'ORD' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid((string)$member->id_member, true)), 0, 6));

            // Create order
            $order = MemberOrder::create([
                'id_member' => $member->id_member,
                'payment_method' => 'midtrans',
                'order_number' => $orderNumber,
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $total,
                'total' => $total,
            ]);

            // Create order items
            foreach ($itemsData as $it) {
                MemberOrderItem::create([
                    'id_order' => $order->id_order,
                    'id_produk' => $it['id_produk'],
                    'qty' => $it['qty'],
                    'harga' => $it['harga'],
                    'subtotal' => $it['subtotal'],
                ]);
            }

            // Prepare Midtrans transaction parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $orderNumber,
                    'gross_amount' => (int)$total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $member->nama,
                    'email' => $member->email ?? 'member@swaldorf.com',
                    'phone' => $member->no_telp ?? '08123456789',
                ],
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va',
                    'permata_va', 'other_va', 'gopay', 'shopeepay'
                ],
                'callbacks' => [
                    'finish' => route('member.payment.finish'),
                ],
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

            // Save snap token to order
            $order->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->id_order,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status ?? null;

            Log::info('Midtrans notification', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
            ]);

            $order = MemberOrder::where('order_number', $orderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update payment details
            $order->transaction_id = $notification->transaction_id;
            $order->payment_type = $paymentType;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->payment_status = 'paid';
                    $order->status = 'awaiting_preparation';
                    $order->paid_at = now();
                    $this->reduceStock($order);
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'awaiting_preparation';
                $order->paid_at = now();
                $this->reduceStock($order);
            } elseif ($transactionStatus == 'pending') {
                $order->payment_status = 'pending';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
            }

            $order->save();

            return response()->json(['message' => 'Notification processed']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    /**
     * Reduce stock after successful payment
     */
    private function reduceStock($order)
    {
        foreach ($order->items as $item) {
            // Check if order item has variant
            if ($item->id_variant) {
                // Reduce variant stock
                $variant = \App\Models\ProductVariant::find($item->id_variant);
                if ($variant) {
                    $variant->decrement('stok', $item->qty);
                    // Update product total_stok
                    $variant->updateProductTotals();
                }
            } else {
                // Reduce product stock (for products without variants)
                $product = Produk::find($item->id_produk);
                if ($product) {
                    $product->decrement('stok', $item->qty);
                }
            }
        }
    }

    /**
     * Payment finish page (redirect from Midtrans)
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');
        $transactionStatus = $request->get('transaction_status');

        if ($orderId) {
            $order = MemberOrder::where('order_number', $orderId)->first();

            if ($order) {
                // Check real-time status from Midtrans API
                try {
                    Config::$serverKey = config('midtrans.server_key');
                    Config::$isProduction = config('midtrans.is_production');

                    $status = Transaction::status($orderId);
                    $statusObj = (object) $status;

                    $realTransactionStatus = $statusObj->transaction_status ?? '';

                    Log::info('Payment finish - real status check', [
                        'order_id' => $orderId,
                        'url_transaction_status' => $transactionStatus,
                        'real_transaction_status' => $realTransactionStatus
                    ]);

                    // Update order based on real status
                    if (in_array($realTransactionStatus, ['capture', 'settlement'])) {
                        $order->payment_status = 'paid';
                        $order->status = 'awaiting_preparation';
                        $order->paid_at = now();
                        $order->save();

                        // Clear cart on successful payment
                        session()->forget('member_cart');
                        return redirect()->route('member.orders.show', $order->id_order)
                            ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
                    } elseif ($realTransactionStatus == 'pending') {
                        return redirect()->route('member.orders.show', $order->id_order)
                            ->with('info', 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.');
                    } elseif (in_array($realTransactionStatus, ['deny', 'expire', 'cancel'])) {
                        return redirect()->route('member.orders.show', $order->id_order)
                            ->with('error', 'Pembayaran gagal atau dibatalkan.');
                    }

                } catch (\Exception $e) {
                    Log::error('Error checking payment status on finish: ' . $e->getMessage());
                }

                // Fallback to URL parameter status if API check fails
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    session()->forget('member_cart');
                    return redirect()->route('member.orders.show', $order->id_order)
                        ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
                }

                return redirect()->route('member.orders.show', $order->id_order)
                    ->with('info', 'Status pembayaran: ' . $transactionStatus);
            }
        }

        return redirect()->route('member.cart.index')
            ->with('error', 'Terjadi kesalahan pada pembayaran');
    }

    /**
     * Check payment status
     */
    public function checkStatus($orderId)
    {
        $order = MemberOrder::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'order_number' => $order->order_number,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
            'total' => $order->total,
        ]);
    }

    /**
     * Manual check payment status from Midtrans API
     */
    public function manualCheckStatus($orderId)
    {
        try {
            $order = MemberOrder::where('order_number', $orderId)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Check status from Midtrans API
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            $status = Transaction::status($orderId);

            // Convert to object for easier access
            $statusObj = (object) $status;

            Log::info('Manual status check from Midtrans', [
                'order_id' => $orderId,
                'transaction_status' => $statusObj->transaction_status ?? null,
                'payment_type' => $statusObj->payment_type ?? null,
            ]);

            // Update order based on status
            $order->transaction_id = $statusObj->transaction_id ?? $order->transaction_id;
            $order->payment_type = $statusObj->payment_type ?? $order->payment_type;

            $transactionStatus = $statusObj->transaction_status ?? '';
            $fraudStatus = $statusObj->fraud_status ?? null;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $order->payment_status = 'paid';
                    $order->status = 'awaiting_preparation';
                    $order->paid_at = now();
                    $this->reduceStock($order);
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->payment_status = 'paid';
                $order->status = 'awaiting_preparation';
                $order->paid_at = now();
                $this->reduceStock($order);
            } elseif ($transactionStatus == 'pending') {
                $order->payment_status = 'pending';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
            }

            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'payment_status' => $order->payment_status,
                'status' => $order->status,
                'transaction_status' => $transactionStatus,
            ]);

        } catch (\Exception $e) {
            Log::error('Manual status check error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error checking status: ' . $e->getMessage()
            ], 500);
        }
    }
}
