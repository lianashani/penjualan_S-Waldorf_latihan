@extends('member.layout')
@section('title','Dashboard')
@push('styles')
<style>
/* Hero Slider Section */
.hero-slider-wrapper {
    margin-bottom: 3rem;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}
.hero-slider {
    position: relative;
    height: 450px;
    overflow: hidden;
}
.hero-slide {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
}
.hero-slide.active {
    opacity: 1;
}
.hero-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    padding: 3rem 2rem;
    color: #fff;
}
.hero-overlay h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    text-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.hero-overlay p {
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    opacity: 0.95;
}
.hero-btn {
    display: inline-block;
    background: #fff;
    color: #000;
    padding: 0.875rem 2.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}
.hero-btn:hover {
    background: #f5f5f5;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}
.slider-dots {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 10;
}
.slider-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}
.slider-dot.active {
    background: #fff;
    width: 24px;
    border-radius: 5px;
}
.slider-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.9);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    z-index: 10;
    transition: all 0.3s ease;
}
.slider-arrow:hover {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.slider-arrow.prev {
    left: 20px;
}
.slider-arrow.next {
    right: 20px;
}

/* Welcome Banner */
.welcome-banner {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: #fff;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}
.welcome-banner h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.welcome-banner p {
    opacity: 0.9;
    margin: 0;
}

/* Stats Section */
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}
.stat-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    border-color: #1a1a1a;
}
.stat-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #f7f7f7 0%, #e8e8e8 100%);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2.25rem;
    margin-bottom: 1.25rem;
    color: #1a1a1a;
}
.stat-label {
    font-size: 0.813rem;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.8px;
}
.stat-value {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1a1a1a;
}

/* Quick Actions */
.actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 3rem;
}
.action-card {
    background: #fff;
    border: 2px solid #e8e8e8;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    text-decoration: none;
    color: #000;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(0,0,0,0.03) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.action-card:hover {
    border-color: #1a1a1a;
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    color: #000;
}
.action-card:hover::before {
    opacity: 1;
}
.action-card.chat-card {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: #fff;
    border: 2px solid #1a1a1a;
}
.action-card.chat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    color: #fff;
}
.action-icon {
    font-size: 2.75rem;
    margin-bottom: 0.75rem;
}
.action-card .action-title {
    font-weight: 600;
    font-size: 0.95rem;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}
.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    color: #1a1a1a;
}
.view-all {
    color: #1a1a1a;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.view-all:hover {
    color: #666;
    gap: 0.75rem;
}

/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}
.product-card {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #000;
    display: block;
}
.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    border-color: #1a1a1a;
}
.product-image {
    width: 100%;
    height: 280px;
    object-fit: cover;
    background: #f7f7f7;
}
.product-info {
    padding: 1.25rem;
}
.product-name {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    color: #1a1a1a;
    line-height: 1.4;
}
.product-price {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1a1a1a;
}
.badge-new {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #1a1a1a;
    color: #fff;
    padding: 0.35rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    z-index: 5;
}
.featured-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}
</style>
@endpush
@section('content')
<!-- Hero Image Slider -->
<div class="hero-slider-wrapper">
    <div class="hero-slider">
        <button class="slider-arrow prev" onclick="changeSlide(-1)">
            <i class="mdi mdi-chevron-left"></i>
        </button>
        <button class="slider-arrow next" onclick="changeSlide(1)">
            <i class="mdi mdi-chevron-right"></i>
        </button>

        <div class="hero-slide active">
            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1200&h=450&fit=crop" alt="Fashion Collection">
            <div class="hero-overlay">
                <h2>Selamat Datang, {{ $member->nama_member }}</h2>
                <p>Temukan koleksi fashion terbaru untuk gaya Anda</p>
                <a href="{{ route('member.catalog.index') }}" class="hero-btn">Belanja Sekarang</a>
            </div>
        </div>

        <div class="hero-slide">
            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=1200&h=450&fit=crop" alt="New Arrivals">
            <div class="hero-overlay">
                <h2>Koleksi Terbaru</h2>
                <p>Dapatkan diskon spesial untuk member setia</p>
                <a href="{{ route('member.catalog.index') }}" class="hero-btn">Lihat Koleksi</a>
            </div>
        </div>

        <div class="hero-slide">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=1200&h=450&fit=crop" alt="Premium Quality">
            <div class="hero-overlay">
                <h2>Kualitas Premium</h2>
                <p>Produk berkualitas dengan harga terbaik</p>
                <a href="{{ route('member.catalog.index') }}" class="hero-btn">Jelajahi Sekarang</a>
            </div>
        </div>

        <div class="hero-slide">
            <img src="https://images.unsplash.com/photo-1492707892479-7bc8d5a4ee93?w=1200&h=450&fit=crop" alt="Exclusive Deals">
            <div class="hero-overlay">
                <h2>Penawaran Eksklusif</h2>
                <p>Nikmati pengalaman berbelanja terbaik bersama kami</p>
                <a href="{{ route('member.catalog.index') }}" class="hero-btn">Mulai Belanja</a>
            </div>
        </div>

        <div class="slider-dots">
            <span class="slider-dot active" onclick="goToSlide(0)"></span>
            <span class="slider-dot" onclick="goToSlide(1)"></span>
            <span class="slider-dot" onclick="goToSlide(2)"></span>
            <span class="slider-dot" onclick="goToSlide(3)"></span>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats">
    <div class="stat-card">
        <div class="stat-icon"><i class="mdi mdi-star-circle"></i></div>
        <div class="stat-label">Total Poin</div>
        <div class="stat-value">{{ number_format($member->points) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="mdi mdi-cash-multiple"></i></div>
        <div class="stat-label">Total Belanja</div>
        <div class="stat-value">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="mdi mdi-account-star"></i></div>
        <div class="stat-label">Status</div>
        <div class="stat-value">{{ ucfirst($member->status) }}</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="section-header">
    <h3 class="section-title">Quick Actions</h3>
</div>
<div class="actions">
    <a href="{{ route('member.catalog.index') }}" class="action-card">
        <div class="action-icon"><i class="mdi mdi-store"></i></div>
        <div class="action-title">Katalog Produk</div>
    </a>
    <a href="{{ route('member.cart.index') }}" class="action-card">
        <div class="action-icon"><i class="mdi mdi-cart"></i></div>
        <div class="action-title">Keranjang</div>
    </a>
    <a href="{{ route('member.orders') }}" class="action-card">
        <div class="action-icon"><i class="mdi mdi-package-variant"></i></div>
        <div class="action-title">Pesanan Saya</div>
    </a>
    <a href="{{ route('member.profile') }}" class="action-card">
        <div class="action-icon"><i class="mdi mdi-account-circle"></i></div>
        <div class="action-title">Profil Saya</div>
    </a>
    <a href="{{ route('member.chat') }}" class="action-card chat-card">
        <div class="action-icon"><i class="mdi mdi-message-text"></i></div>
        <div class="action-title">Chat Kasir</div>
    </a>
</div>

<!-- New Arrivals -->
<div class="section-header">
<h3 class="section-title">New Arrivals</h3>
<a href="{{ route('member.catalog.index') }}" class="view-all">Lihat Semua <i class="mdi mdi-arrow-right"></i></a>
</div>
<div class="product-grid">
@forelse($newArrivals ?? [] as $product)
<a href="{{ route('member.catalog.show', $product->id_produk) }}" class="product-card">
<div style="position:relative">
<span class="badge-new">New</span>
@php
    $displayImage = null;
    // Try to get image from product_images table first
    if($product->images && $product->images->count() > 0) {
        $displayImage = $product->images->first()->gambar;
    }
    // Fallback to main gambar field
    elseif($product->gambar) {
        $displayImage = $product->gambar;
    }
@endphp
@if($displayImage)
<img src="{{ \Illuminate\Support\Str::startsWith($displayImage, ['http://','https://','/']) ? $displayImage : asset('storage/'.$displayImage) }}" alt="{{ $product->nama_produk }}" class="product-image">
@else
<div class="product-image" style="display:flex;align-items:center;justify-content:center"><i class="mdi mdi-image-outline" style="font-size:3rem;color:#ccc"></i></div>
@endif
</div>
<div class="product-info">
<div class="product-name">{{ $product->nama_produk }}</div>
<div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
</div>
</a>
@empty
<div style="grid-column:1/-1;text-align:center;padding:2rem;color:#767676">
<p>Belum ada produk baru</p>
</div>
@endforelse
</div>
<div class="section-header">
<h3 class="section-title">Featured Products</h3>
<a href="{{ route('member.catalog.index') }}" class="view-all">Lihat Semua <i class="mdi mdi-arrow-right"></i></a>
</div>
<div class="featured-grid">
@forelse($featuredProducts ?? [] as $product)
<a href="{{ route('member.catalog.show', $product->id_produk) }}" class="product-card">
@php
    $displayImage = null;
    // Try to get image from product_images table first
    if($product->images && $product->images->count() > 0) {
        $displayImage = $product->images->first()->gambar;
    }
    // Fallback to main gambar field
    elseif($product->gambar) {
        $displayImage = $product->gambar;
    }
@endphp
@if($displayImage)
<img src="{{ \Illuminate\Support\Str::startsWith($displayImage, ['http://','https://','/']) ? $displayImage : asset('storage/'.$displayImage) }}" alt="{{ $product->nama_produk }}" class="product-image">
@else
<div class="product-image" style="display:flex;align-items:center;justify-content:center"><i class="mdi mdi-image-outline" style="font-size:3rem;color:#ccc"></i></div>
@endif
<div class="product-info">
<div class="product-name">{{ $product->nama_produk }}</div>
<div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
</div>
</a>
@empty
<div style="grid-column:1/-1;text-align:center;padding:2rem;color:#767676">
<p>Belum ada produk unggulan</p>
</div>
@endforelse
</div>
@endsection

@push('scripts')
<script>
let currentSlide = 0;
let slideInterval;

function showSlide(index) {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');

    if (index >= slides.length) currentSlide = 0;
    if (index < 0) currentSlide = slides.length - 1;

    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));

    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
}

function changeSlide(direction) {
    currentSlide += direction;
    showSlide(currentSlide);
    resetInterval();
}

function goToSlide(index) {
    currentSlide = index;
    showSlide(currentSlide);
    resetInterval();
}

function autoSlide() {
    currentSlide++;
    showSlide(currentSlide);
}

function resetInterval() {
    clearInterval(slideInterval);
    slideInterval = setInterval(autoSlide, 5000);
}

// Start auto-sliding
slideInterval = setInterval(autoSlide, 5000);

// Pause on hover
document.querySelector('.hero-slider').addEventListener('mouseenter', () => {
    clearInterval(slideInterval);
});

document.querySelector('.hero-slider').addEventListener('mouseleave', () => {
    slideInterval = setInterval(autoSlide, 5000);
});
</script>
@endpush
