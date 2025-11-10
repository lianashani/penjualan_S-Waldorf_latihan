@extends('layouts.master')
@section('title', 'Katalog Elegan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('katalog.index') }}">Katalog</a></li>
<li class="breadcrumb-item active" aria-current="page">Katalog Elegan</li>
@endsection

@section('content')
<div class="container-fluid bw-theme">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 text-white">
                                <i class="mdi mdi-store me-2"></i>Katalog S&Waldorf
                            </h2>
                            <p class="mb-0 opacity-75">Temukan koleksi fashion terbaik dengan varian lengkap dan kualitas premium</p>
                            @if(Auth::user()->role == 'admin')
                                <div class="mt-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="mdi mdi-shield-account me-1"></i>Mode Admin - Lihat Detail Saja
                                    </span>
                                </div>
                            @else
                                <div class="mt-2">
                                    <span class="badge bg-success">
                                        <i class="mdi mdi-cart me-1"></i>Mode Kasir - Dapat Melakukan Transaksi
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button class="btn btn-light btn-sm d-inline-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#filterCol" aria-expanded="true" aria-controls="filterCol" id="filterToggleBtn">
                                    <i class="mdi mdi-filter-variant me-1"></i>
                                    <span class="d-none d-sm-inline">Filter</span>
                                </button>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                                    <label class="btn btn-light btn-sm" for="gridView" title="Grid View">
                                        <i class="mdi mdi-view-grid"></i>
                                    </label>
                                    <input type="radio" class="btn-check" name="viewMode" id="listView">
                                    <label class="btn btn-light btn-sm" for="listView" title="List View">
                                        <i class="mdi mdi-view-list"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-12 col-lg-3 mb-4 collapse show" id="filterCol">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex align-items-center justify-content-between">
                        <strong><i class="mdi mdi-filter-variant me-1"></i>Filter</strong>
                        <button class="btn btn-sm btn-light d-none d-lg-inline-flex" type="button" data-bs-toggle="collapse" data-bs-target="#filterCol" aria-controls="filterCol" id="filterHideBtn">
                            Sembunyikan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('katalog.elegant') }}">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-control form-control-sm">
                                <option value="">Semua</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }} ({{ $kategori->produks_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rating Minimal</label>
                            <select name="rating_min" class="form-control form-control-sm">
                                <option value="">Semua</option>
                                <option value="4" {{ request('rating_min') == '4' ? 'selected' : '' }}>4+ bintang</option>
                                <option value="3" {{ request('rating_min') == '3' ? 'selected' : '' }}>3+ bintang</option>
                                <option value="2" {{ request('rating_min') == '2' ? 'selected' : '' }}>2+ bintang</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Urutkan</label>
                            <select name="sort" class="form-control form-control-sm">
                                <option value="featured" {{ request('sort', 'featured') == 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="mdi mdi-magnify me-1"></i>Terapkan</button>
                            <a href="{{ route('katalog.elegant') }}" class="btn btn-light btn-sm">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-12 col-lg-9" id="gridCol">
            <div class="row g-4" id="productGrid">
                @forelse($produks as $produk)
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 product-item"
                     data-category="{{ $produk->id_kategori }}"
                     data-price="{{ $produk->harga_min ?? $produk->harga }}"
                     data-rating="{{ $produk->rating_average }}"
                     data-stock="{{ $produk->has_variants ? $produk->total_stok : $produk->stok }}"
                     data-featured="{{ $produk->is_featured ? 1 : 0 }}">

                    <div class="card product-card h-100 border-0 shadow-sm">
                        <!-- Product Image -->
                        <div class="position-relative overflow-hidden">
                            <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="text-decoration-none">
                                <img src="{{ $produk->main_image }}"
                                     class="card-img-top product-image"
                                     alt="{{ $produk->nama_produk }}"
                                     style="height: 280px; object-fit: cover; transition: transform 0.3s ease;">
                            </a>

                            <!-- Category Badge on Image -->
                            <!-- <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                    <i class="mdi mdi-tag me-1"></i>{{ $produk->kategori->nama_kategori }}
                                </span>
                            </div> -->

                            <!-- Quick Actions -->
                            @if(Auth::user()->role == 'kasir')
                            <div class="position-absolute top-0 end-0 p-2">
                                <button class="btn btn-sm btn-light rounded-circle shadow-sm quick-action-btn" title="Tambah ke Wishlist">
                                    <i class="mdi mdi-heart-outline"></i>
                                </button>
                            </div>
                            @endif

                            <!-- Variant Preview -->
                            @if($produk->has_variants && $produk->activeVariants->count() > 0)
                            <div class="position-absolute bottom-0 start-0 end-0 p-2 variant-bar">
                                <div class="d-flex justify-content-center align-items-center gap-3">
                                    @foreach($produk->activeVariants->take(4) as $variant)
                                        <div class="variant-preview"
                                             style="background-color: {{ $variant->kode_warna ?? '#ccc' }};"
                                             title="{{ $variant->warna }} - {{ $variant->ukuran }}"></div>
                                    @endforeach
                                    @if($produk->activeVariants->count() > 4)
                                        <span class="variant-count">+{{ $produk->activeVariants->count() - 4 }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="card-body d-flex flex-column p-3 flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                    <i class="mdi mdi-tag me-1"></i>{{ $produk->kategori->nama_kategori }}
                                </span>
                                @if($produk->is_featured)
                                    <span class="badge bg-warning text-dark">
                                        <i class="mdi mdi-star me-1"></i>Featured
                                    </span>
                                @endif
                            </div>

                            <h6 class="card-title mb-2">
                                <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="text-decoration-none text-dark fw-bold">
                                    {{ $produk->nama_produk }}
                                </a>
                            </h6>

                            <!-- Rating -->
                            @if($produk->rating_count > 0)
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="text-warning me-1">{!! $produk->rating_stars !!}</span>
                                    <small class="text-muted">({{ $produk->rating_count }} ulasan)</small>
                                </div>
                            </div>
                            @endif

                            <!-- Price -->
                            <div class="mb-3">
                                <h5 class="text-primary mb-0 fw-bold">{{ $produk->formatted_price }}</h5>
                                @if($produk->has_variants)
                                    <small class="text-muted">
                                        <i class="mdi mdi-information-outline me-1"></i>Mulai dari
                                    </small>
                                @endif
                            </div>

                            <!-- Variant Info -->
                            @if($produk->has_variants)
                            <div class="mb-3">
                                <div class="d-flex align-items-center text-muted small">
                                    <span class="me-3">
                                        <i class="mdi mdi-palette me-1"></i>{{ $produk->activeVariants->pluck('warna')->unique()->count() }} warna
                                    </span>
                                    <span>
                                        <i class="mdi mdi-ruler me-1"></i>{{ $produk->activeVariants->pluck('ukuran')->unique()->count() }} ukuran
                                    </span>
                                </div>
                            </div>
                            @endif

                            <!-- Stock Status -->
                            <div class="mb-3">
                                <span class="badge bg-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }} border border-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }} border-opacity-25">
                                    <i class="mdi mdi-{{ $produk->stock_status == 'in_stock' ? 'check-circle' : ($produk->stock_status == 'low_stock' ? 'alert-circle' : 'close-circle') }} me-1"></i>
                                    {{ $produk->stock_status_text }}
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="btn btn-primary btn-sm" style="color: white !important;">
                                        <i class="mdi mdi-eye me-1"></i>Lihat Detail
                                    </a>
                                    @if($produk->stock_status != 'out_of_stock' && Auth::user()->role == 'kasir')
                                        @if($produk->has_variants)
                                            <a href="{{ route('katalog.elegant-detail', $produk->slug) }}" class="btn btn-outline-success btn-sm">
                                                <i class="mdi mdi-tune-variant me-1"></i>Pilih Varian
                                            </a>
                                        @else
                                            <form method="POST" action="{{ route('keranjang.add') }}">
                                                @csrf
                                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                                <input type="hidden" name="qty" value="1">
                                                <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <i class="mdi mdi-cart-plus me-1"></i>Tambah ke Keranjang
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="mdi mdi-package-variant-closed display-1 text-muted opacity-50"></i>
                            </div>
                            <h4 class="text-muted mb-3">Tidak ada produk ditemukan</h4>
                            <p class="text-muted mb-4">Coba ubah filter atau kata kunci pencarian untuk menemukan produk yang Anda cari</p>
                            <a class="btn btn-outline-primary" href="{{ route('katalog.elegant') }}">
                                <i class="mdi mdi-refresh me-1"></i>Reset Filter
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            @if($produks->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <div class="pagination-sm">
                    {{ $produks->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Hero Section */
.bg-gradient-primary {
    background: linear-gradient(135deg, #f6f7f8 0%, #ffffff 100%);
}

/* Black & White Theme Wrapper */
.bw-theme {
    background-color: #ffffff;
    color: #0f0f10;
}

.bw-theme a,
.bw-theme .text-dark {
    color: #0f0f10 !important;
}

.bw-theme .card {
    background-color: #ffffff;
    color: #0f0f10;
    border: 1px solid #e5e7eb;
}

.bw-theme .card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #e5e7eb !important;
    color: #0f0f10;
}

.bw-theme .text-muted { color: #6b7280 !important; }

.bw-theme .btn { border-radius: 10px; }

.bw-theme .btn-primary,
.bw-theme .btn-success {
    background-color: #111111;
    color: #ffffff;
    border-color: #111111;
}

.bw-theme .btn-primary:hover,
.bw-theme .btn-success:hover {
    background-color: #000000;
    color: #ffffff;
    border-color: #000000;
}

.bw-theme .btn-light {
    background-color: #ffffff;
    color: #111111;
    border-color: #e5e7eb;
}

.bw-theme .btn-light:hover {
    background-color: #f3f4f6;
}

.bw-theme .btn-outline-success,
.bw-theme .btn-outline-primary {
    color: #111111;
    border-color: #111111;
}

.bw-theme .btn-outline-success:hover,
.bw-theme .btn-outline-primary:hover {
    background-color: #111111;
    color: #ffffff;
    border-color: #111111;
}

/* Neutralize brand colors to grayscale inside theme */
.bw-theme .bg-primary,
.bw-theme .border-primary {
    background-color: #111111 !important;
    color: #ffffff !important;
    border-color: #111111 !important;
}

/* Ensure primary text is black text without any background fill */
.bw-theme .text-primary {
    color: #111111 !important;
    background-color: transparent !important;
}

.bw-theme .badge {
    background-color: #f3f4f6;
    color: #111111;
    border: 1px solid #e5e7eb;
}

.bw-theme .badge.bg-success,
.bw-theme .badge.bg-warning,
.bw-theme .badge.bg-danger,
.bw-theme .badge.bg-primary,
.bw-theme .badge.bg-warning.text-dark {
    background-color: #f3f4f6 !important;
    color: #111111 !important;
    border-color: #e5e7eb !important;
}

.bw-theme .list-group-item {
    background-color: transparent;
    color: #0f0f10;
}

.bw-theme .list-group-item-action:hover {
    background-color: #f7f7f8;
}

.bw-theme .category-filter:hover {
    background-color: #f3f4f6;
}

.bw-theme .category-filter.active {
    background-color: #e5e7eb;
    color: #111111;
}

.bw-theme .dropdown-menu {
    background-color: #ffffff;
    border-color: #e5e7eb;
}

.bw-theme .dropdown-item { color: #0f0f10; }
.bw-theme .dropdown-item:hover { background-color: #f3f4f6; }

.bw-theme .form-control {
    background-color: #ffffff;
    color: #0f0f10;
    border-color: #e5e7eb;
}

.bw-theme .form-control:focus {
    background-color: #ffffff;
    color: #0f0f10;
    border-color: #111111;
    box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.06);
}

.bw-theme .page-link { color: #111111; background-color: #ffffff; border-color: #e5e7eb; }
.bw-theme .page-item.active .page-link { background-color: #111111; color: #ffffff; border-color: #111111; }

.bg-gradient-dark {
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%);
}

/* Product Cards */
.product-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    margin-bottom: 0;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    border-color: #d1d5db;
}

/* Ensure consistent card heights */
.product-item {
    display: flex;
    margin-bottom: 1.5rem;
}

.product-item .product-card {
    width: 100%;
}

.product-image {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

/* Variant Preview */
.variant-preview {
    cursor: pointer;
    transition: all 0.2s ease;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.12);
    flex: 0 0 22px;
    background-clip: padding-box;
}

.variant-preview:hover {
    transform: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.18);
}

/* Variant Bar (light theme) */
.variant-bar {
    background: #ffffff;
    border-top: 1px solid #e5e7eb;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
    z-index: 2;
    display: flex;
    align-items: center;
}

.variant-count {
    color: #111111;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 999px;
    padding: 0 8px;
    font-size: 12px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    margin-left: 6px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
}

/* Quick Action Buttons */
.quick-action-btn {
    opacity: 0;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.product-card:hover .quick-action-btn {
    opacity: 1;
}

.quick-action-btn:hover {
    background-color: #ffffff !important;
    color: #0f0f10 !important;
    transform: scale(1.1);
}

/* Filter Sidebar */
.category-filter {
    transition: all 0.2s ease;
    border-radius: 8px;
    margin-bottom: 2px;
}

.category-filter:hover {
    background-color: #f3f4f6;
    transform: translateX(5px);
}

.category-filter.active {
    background-color: #e5e7eb;
    color: #111111;
    font-weight: 600;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
}

/* Featured badge positioning */
.badge.bg-warning {
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Category badge styling */
.badge.bg-primary.bg-opacity-10 {
    font-weight: 500;
}

/* Badge positioning on image */
.position-absolute.top-0.start-0 .d-flex.flex-column.gap-1 {
    gap: 0.25rem !important;
}

.position-absolute.top-0.start-0 .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    white-space: nowrap;
}

/* Badge container styling */
.d-flex.align-items-center.gap-2 {
    gap: 0.5rem !important;
    flex-wrap: wrap;
}

.d-flex.align-items-center.gap-2 .badge {
    white-space: nowrap;
    flex-shrink: 0;
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* Pagination styling */
.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.pagination-sm .page-item:first-child .page-link {
    border-top-left-radius: 0.2rem;
    border-bottom-left-radius: 0.2rem;
}

.pagination-sm .page-item:last-child .page-link {
    border-top-right-radius: 0.2rem;
    border-bottom-right-radius: 0.2rem;
}

/* Global smaller pagination on this page */
.bw-theme .pagination .page-link {
    padding: 0.2rem 0.45rem;
    font-size: 0.8rem;
}
.bw-theme .pagination {
    gap: 0.2rem;
}

/* List View */
.list-view .product-card {
    flex-direction: row;
    max-width: 100%;
}

.list-view .product-card .card-img-top {
    width: 250px;
    height: 200px;
    object-fit: cover;
    flex-shrink: 0;
}

.list-view .product-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Responsive */
@media (max-width: 1200px) {
    .col-xl-4 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

/* Ensure 3 columns per row on large screens */
@media (min-width: 1200px) {
    #productGrid .product-item {
        flex: 0 0 33.3333%;
        max-width: 33.3333%;
    }
}

@media (max-width: 768px) {
    .col-xl-4, .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .product-card {
        margin-bottom: 1rem;
    }

    .list-view .product-card {
        flex-direction: column;
    }

    .list-view .product-card .card-img-top {
        width: 100%;
        height: 200px;
    }
}

@media (max-width: 576px) {
    .row.g-4 {
        --bs-gutter-x: 1rem;
        --bs-gutter-y: 1rem;
    }
}

/* Loading Animation */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Smooth Transitions */
* {
    transition: all 0.2s ease;
}

/* Custom Scrollbar */
.bw-theme ::-webkit-scrollbar {
    width: 8px;
}

.bw-theme ::-webkit-scrollbar-track {
    background: #f2f2f3;
    border-radius: 4px;
}

.bw-theme ::-webkit-scrollbar-thumb {
    background: #c9c9cc;
    border-radius: 4px;
}

.bw-theme ::-webkit-scrollbar-thumb:hover {
    background: #b5b7bb;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // View mode toggle with animation
    $('input[name="viewMode"]').change(function() {
        $('#productGrid').addClass('loading');

        setTimeout(() => {
            if ($(this).attr('id') === 'listView') {
                $('#productGrid').addClass('list-view');
            } else {
                $('#productGrid').removeClass('list-view');
            }
            $('#productGrid').removeClass('loading');
        }, 200);
    });

    // Handle filter sidebar collapse/expand to adjust grid width
    const updateGridForFilter = () => {
        const isShown = $('#filterCol').hasClass('show');
        if (isShown) {
            $('#gridCol').removeClass('col-lg-12').addClass('col-lg-9');
        } else {
            $('#gridCol').removeClass('col-lg-9').addClass('col-lg-12');
        }
    };

    // Initialize and bind to Bootstrap collapse events
    const delayedUpdate = () => setTimeout(updateGridForFilter, 400);
    updateGridForFilter();
    $('#filterCol')
        .on('shown.bs.collapse', updateGridForFilter)
        .on('hidden.bs.collapse', updateGridForFilter)
        .on('show.bs.collapse', delayedUpdate)
        .on('hide.bs.collapse', delayedUpdate);

    // Header Filter button controls collapse explicitly and updates grid width
    $('#filterToggleBtn').on('click', function(e) {
        e.preventDefault();
        const willShow = !$('#filterCol').hasClass('show');
        $('#filterCol').collapse('toggle');
        if (willShow) {
            $('#gridCol').removeClass('col-lg-12').addClass('col-lg-9');
        } else {
            $('#gridCol').removeClass('col-lg-9').addClass('col-lg-12');
        }
        delayedUpdate();
    });

    // Ensure inner 'Sembunyikan' button toggles the sidebar reliably
    $('#filterHideBtn').on('click', function(e){
        e.preventDefault();
        $('#filterCol').collapse('toggle');
        delayedUpdate();
    });
});

function quickAddToCart(productId) {
    // Quick add to cart functionality with animation
    const button = event.target.closest('button');
    const originalText = button.innerHTML;

    button.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i>Menambah...';
    button.disabled = true;

    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;

        Swal.fire({
            title: 'Berhasil!',
            text: 'Produk berhasil ditambahkan ke keranjang',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }, 1500);
}

// Smooth scroll to top when filtering
function scrollToTop() {
    $('html, body').animate({
        scrollTop: $('#productGrid').offset().top - 100
    }, 500);
}
</script>
@endpush
