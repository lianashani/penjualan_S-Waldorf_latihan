<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\OrderController as MemberOrderController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

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
        
        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);
        
        // Promo
        Route::resource('promo', PromoController::class);
        
        // Laporan
        Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/print', [App\Http\Controllers\LaporanController::class, 'print'])->name('laporan.print');
    });

    // KASIR ONLY Routes
    Route::middleware([App\Http\Middleware\CheckRole::class . ':kasir'])->group(function () {
        // Penjualan (Sales Transaction)
        Route::resource('penjualan', PenjualanController::class);
        Route::post('penjualan/calculate-discount', [PenjualanController::class, 'calculateDiscount'])->name('penjualan.calculate-discount');
        
        // Print Receipt
        Route::get('/penjualan/{id}/print', [PenjualanController::class, 'print'])->name('penjualan.print');
        
        // Keranjang (Shopping Cart)
        Route::get('/keranjang', function() {
            return redirect()->route('penjualan.create');
        })->name('keranjang.index');
    });

    
});

// Member Routes
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/login', [MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [MemberAuthController::class, 'login']);
    
    Route::middleware('auth:member')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [MemberAuthController::class, 'logout'])->name('logout');
    });
});