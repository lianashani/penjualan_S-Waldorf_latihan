<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\CatalogController as MemberCatalogController;
use App\Http\Controllers\Member\CartController as MemberCartController;
use App\Http\Controllers\Member\ChatController as MemberChatController;
use App\Http\Controllers\Member\OrderController as MemberOrderController;
use App\Http\Controllers\Member\PaymentController as MemberPaymentController;
use App\Http\Controllers\Admin\MemberOrderController as AdminMemberOrderController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProductRatingController;

// Public Landing Page
Route::get('/welcome', function () {
    $allProduks = \App\Models\Produk::with('kategori')->where('stok', '>', 0)->get();
    $newArrivals = \App\Models\Produk::with('kategori')->where('stok', '>', 0)->latest()->take(4)->get();
    $featured = \App\Models\Produk::with('kategori')->where('stok', '>', 0)->inRandomOrder()->take(3)->get();
    return view('welcome', compact('allProduks', 'newArrivals', 'featured'));
});

// Login Routes (Guest Only)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');

// Auth Routes (Authenticated Users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('change-password.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Protected Routes (Must Change Password First)
Route::middleware(['auth', App\Http\Middleware\MustChangePassword::class])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // Katalog Produk (Accessible by all authenticated users)
    Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
    Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

    // Elegant Catalog with Variants
    Route::get('/katalog-elegant', [ProdukController::class, 'catalog'])->name('katalog.elegant');
    Route::get('/katalog-elegant/{slug}', [ProdukController::class, 'catalogDetail'])->name('katalog.elegant-detail');
    Route::post('/katalog-elegant/variant-images', [ProdukController::class, 'getVariantImages'])->name('katalog.variant-images');
    Route::post('/katalog-elegant/{id}/rating', [ProdukController::class, 'addRating'])->name('katalog.add-rating');

    // Keranjang (Shopping Cart) - For All
    Route::get('/keranjang', [KatalogController::class, 'viewCart'])->name('keranjang.index');
    Route::get('/keranjang/view', [KatalogController::class, 'viewCart'])->name('keranjang.view');
    Route::post('/keranjang/add', [KatalogController::class, 'addToCart'])->name('keranjang.add');
    Route::post('/keranjang/update', [KatalogController::class, 'updateCart'])->name('keranjang.update');
    Route::post('/keranjang/remove', [KatalogController::class, 'removeFromCart'])->name('keranjang.remove');
    Route::post('/keranjang/checkout', [KatalogController::class, 'checkout'])->name('keranjang.checkout');

    // ADMIN ONLY Routes
    Route::middleware([App\Http\Middleware\CheckRole::class . ':admin'])->group(function () {
        // User Management
        Route::resource('user', UserController::class);

        // Kategori
        Route::resource('kategori', KategoriController::class);

        // Produk
        Route::resource('produk', ProdukController::class);
        Route::get('/produk/{id}/barcode', [ProdukController::class, 'generateBarcode'])->name('produk.barcode');
        Route::get('/produk/{id}/qrcode', [ProdukController::class, 'generateQRCode'])->name('produk.qrcode');
        Route::get('/produk/{id}/print-barcode', [ProdukController::class, 'printBarcode'])->name('produk.print-barcode');
        Route::get('/produk/{id}/download-barcode', [ProdukController::class, 'downloadBarcode'])->name('produk.download-barcode');
        Route::get('/produk/{id}/download-qrcode', [ProdukController::class, 'downloadQRCode'])->name('produk.download-qrcode');
        Route::post('/produk/{id}/update-stok', [ProdukController::class, 'updateStok'])->name('produk.update-stok');
        Route::get('/produk/{id}/variants', [ProdukController::class, 'getVariants'])->name('produk.variants');

        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);

        // Promo
        Route::resource('promo', PromoController::class);

        // Laporan
        Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/print', [App\Http\Controllers\LaporanController::class, 'print'])->name('laporan.print');

        // Rating Management
        Route::get('/admin/ratings', [ProductRatingController::class, 'index'])->name('ratings.index');
        Route::get('/admin/ratings/{id}', [ProductRatingController::class, 'show'])->name('ratings.show');
        Route::post('/admin/ratings/{id}/approve', [ProductRatingController::class, 'approve'])->name('ratings.approve');
        Route::post('/admin/ratings/{id}/reject', [ProductRatingController::class, 'reject'])->name('ratings.reject');
        Route::delete('/admin/ratings/{id}', [ProductRatingController::class, 'destroy'])->name('ratings.destroy');
        Route::post('/admin/ratings/bulk-action', [ProductRatingController::class, 'bulkAction'])->name('ratings.bulk-action');

        // Member Orders (Queue for Kasir/Admin)
        Route::get('/member-orders', [AdminMemberOrderController::class, 'index'])->name('admin.member-orders.index');
        Route::get('/member-orders/{id}', [AdminMemberOrderController::class, 'show'])->name('admin.member-orders.show');
        Route::post('/member-orders/{id}/status', [AdminMemberOrderController::class, 'updateStatus'])->name('admin.member-orders.update-status');
        Route::get('/member-orders/{id}/print', [AdminMemberOrderController::class, 'print'])->name('admin.member-orders.print');
    });

    // KASIR ONLY Routes
    Route::middleware([App\Http\Middleware\CheckRole::class . ':kasir'])->group(function () {
        // Penjualan (Sales Transaction)
        Route::resource('penjualan', PenjualanController::class);
        Route::post('penjualan/calculate-discount', [PenjualanController::class, 'calculateDiscount'])->name('penjualan.calculate-discount');

        // QRIS Payment for Kasir
        Route::post('/kasir/generate-qris', [PenjualanController::class, 'generateQris'])->name('kasir.generate-qris');
        Route::post('/kasir/check-qris-status', [PenjualanController::class, 'checkQrisStatus'])->name('kasir.check-qris-status');

        // Print Receipt
        Route::get('/penjualan/{id}/print', [PenjualanController::class, 'print'])->name('penjualan.print');

        // Keranjang (Shopping Cart)
        Route::get('/keranjang', function() {
            return redirect()->route('penjualan.create');
        })->name('keranjang.index');

        // Member Orders Queue (Kasir access)
        Route::get('/member-orders', [AdminMemberOrderController::class, 'index'])->name('kasir.member-orders.index');
        Route::get('/member-orders/{id}', [AdminMemberOrderController::class, 'show'])->name('kasir.member-orders.show');
        Route::post('/member-orders/{id}/status', [AdminMemberOrderController::class, 'updateStatus'])->name('kasir.member-orders.update-status');
        Route::get('/member-orders/{id}/print', [AdminMemberOrderController::class, 'print'])->name('kasir.member-orders.print');

        // Chat with Members (Kasir access)
        Route::get('/chat', [App\Http\Controllers\Admin\ChatController::class, 'index'])->name('kasir.chat.index');
        Route::get('/chat/{memberId}', [App\Http\Controllers\Admin\ChatController::class, 'show'])->name('kasir.chat.show');
        Route::post('/chat/send', [App\Http\Controllers\Admin\ChatController::class, 'send'])->name('kasir.chat.send');
        Route::get('/chat/{memberId}/messages', [App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('kasir.chat.messages');
    });


});

// Member Routes
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [MemberAuthController::class, 'login']);

    Route::middleware('auth:member')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [MemberAuthController::class, 'logout'])->name('logout');
        // Member Catalog
        Route::get('/catalog', [MemberCatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{id}', [MemberCatalogController::class, 'show'])->name('catalog.show');

        // Member elegant catalog removed; use member.catalog.* within member sidebar

        // Member Cart
        Route::get('/cart', [MemberCartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [MemberCartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [MemberCartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove', [MemberCartController::class, 'remove'])->name('cart.remove');

        // Member Orders / Checkout
        Route::post('/checkout', [MemberOrderController::class, 'checkout'])->name('checkout');
        Route::get('/orders', [MemberOrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [MemberOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{id}/track', [MemberOrderController::class, 'track'])->name('orders.track');
        Route::get('/orders/{id}/receipt', [MemberOrderController::class, 'receipt'])->name('orders.receipt');
        Route::post('/orders/{id}/cancel', [MemberOrderController::class, 'cancel'])->name('orders.cancel');

        // Midtrans Payment Routes
        Route::get('/payment/create/{orderId}', [MemberPaymentController::class, 'showPayment'])->name('payment.create');
        Route::post('/payment/create', [MemberPaymentController::class, 'createPayment'])->name('payment.create.post');
        Route::get('/payment/finish', [MemberPaymentController::class, 'finish'])->name('payment.finish');
        Route::get('/payment/status/{orderId}', [MemberPaymentController::class, 'checkStatus'])->name('payment.status');
        Route::post('/payment/check-status/{orderId}', [MemberPaymentController::class, 'manualCheckStatus'])->name('payment.check-status');

        // Chat Routes
        Route::get('/chat', [MemberChatController::class, 'index'])->name('chat');
        Route::post('/chat/send', [MemberChatController::class, 'send'])->name('chat.send');
        Route::get('/chat/messages', [MemberChatController::class, 'getMessages'])->name('chat.messages');

        Route::get('/profile', function() {
            $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
            $totalSpent = \App\Models\MemberOrder::where('id_member', $member->id_member)
                ->whereIn('status', ['paid', 'shipped', 'completed'])
                ->sum('total');
            $totalOrders = \App\Models\MemberOrder::where('id_member', $member->id_member)->count();
            return view('member.profile', compact('member', 'totalSpent', 'totalOrders'));
        })->name('profile');
        Route::post('/profile/update', function(\Illuminate\Http\Request $request) {
            $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
            $member->nama_member = $request->nama_member;
            $member->email = $request->email;
            $member->no_hp = $request->no_hp;
            $member->alamat = $request->alamat;
            $member->save();
            return redirect()->route('member.profile')->with('success', 'Profil berhasil diperbarui');
        })->name('profile.update');
        Route::post('/profile/password', function(\Illuminate\Http\Request $request) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed',
            ]);

            $member = \Illuminate\Support\Facades\Auth::guard('member')->user();

            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $member->password)) {
                return back()->with('error', 'Password lama tidak sesuai');
            }

            $member->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $member->save();

            return redirect()->route('member.profile')->with('success', 'Password berhasil diubah');
        })->name('profile.password');
        Route::get('/rewards', function() {
            $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
            return view('member.redeem', compact('member'));
        })->name('rewards');
    });
});

// Midtrans Notification Handler (No Auth Required - Called by Midtrans Server)
Route::post('/midtrans/notification', [App\Http\Controllers\Member\PaymentController::class, 'notification'])->name('midtrans.notification');
