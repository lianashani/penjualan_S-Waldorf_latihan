@extends('member.layout')
@section('title','Katalog Produk')
@push('styles')
<style>
/* Sephora-Inspired Premium E-Commerce Design */
:root {
    --primary-black: #000000;
    --secondary-black: #1a1a1a;
    --text-primary: #262626;
    --text-secondary: #666666;
    --border-light: #e5e5e5;
    --accent-gold: #d4af37;
    --bg-light: #fafafa;
    --white: #ffffff;
}

body { font-family: 'Helvetica Neue', Arial, sans-serif; color: var(--text-primary); background: var(--white); }

/* Hero Banner */
.catalog-hero {
    background: linear-gradient(135deg, #000000 0%, #2d2d2d 100%);
    color: var(--white);
    padding: 4rem 0 3rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}
.catalog-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.5;
}
.catalog-hero h1 {
    font-size: 2.5rem;
    font-weight: 300;
    letter-spacing: 2px;
    margin-bottom: 0.5rem;
    position: relative;
}
.catalog-hero p {
    font-size: 1rem;
    opacity: 0.9;
    letter-spacing: 1px;
    position: relative;
}

/* Filters Sidebar - Premium Style */
.filter-sidebar {
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 0;
    overflow: hidden;
}
.filter-header {
    background: var(--primary-black);
    color: var(--white);
    padding: 1.25rem 1.5rem;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 0.875rem;
    border: none;
}
.filter-body {
    padding: 1.5rem;
}
.filter-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-light);
}
.filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.filter-label {
    font-size: 0.813rem;
    font-weight: 600;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
    display: block;
}
.filter-select, .filter-input {
    border: 1px solid var(--border-light);
    border-radius: 0;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    transition: all 0.2s;
    background: var(--white);
    color: var(--text-primary);
}
.filter-select:focus, .filter-input:focus {
    border-color: var(--primary-black);
    box-shadow: 0 0 0 1px var(--primary-black);
    outline: none;
}
.btn-apply-filter {
    background: var(--primary-black);
    color: var(--white);
    border: none;
    padding: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.813rem;
    font-weight: 600;
    transition: all 0.3s;
    border-radius: 0;
}
.btn-apply-filter:hover {
    background: var(--secondary-black);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.btn-reset-filter {
    background: transparent;
    color: var(--text-primary);
    border: 1px solid var(--border-light);
    padding: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.813rem;
    transition: all 0.2s;
    border-radius: 0;
}
.btn-reset-filter:hover {
    border-color: var(--primary-black);
    color: var(--primary-black);
}

/* Product Cards - Sephora Style */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}
@media (min-width: 992px) {
    .product-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 991px) {
    .product-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 576px) {
    .product-grid { grid-template-columns: 1fr; }
}

.product-card {
    background: var(--white);
    border: 1px solid var(--border-light);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.product-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    border-color: var(--text-secondary);
    transform: translateY(-2px);
}

.product-image-wrapper {
    position: relative;
    overflow: hidden;
    background: var(--bg-light);
    aspect-ratio: 1/1;
}
.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}
.product-card:hover .product-image {
    transform: scale(1.08);
}

/* Badges */
.product-badges {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    z-index: 10;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.badge-new, .badge-featured {
    background: var(--primary-black);
    color: var(--white);
    padding: 0.25rem 0.75rem;
    font-size: 0.688rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-radius: 0;
}
.badge-featured {
    background: var(--accent-gold);
    color: var(--primary-black);
}

/* Wishlist Button */
.btn-wishlist {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 2.5rem;
    height: 2.5rem;
    background: var(--white);
    border: 1px solid var(--border-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    z-index: 10;
}
.btn-wishlist:hover {
    background: var(--primary-black);
    border-color: var(--primary-black);
    color: var(--white);
    transform: scale(1.1);
}

/* Variants Bar */
.variants-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    padding: 0.75rem;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    border-top: 1px solid rgba(0,0,0,0.05);
}
.variant-color {
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    border: 2px solid var(--white);
    box-shadow: 0 0 0 1px rgba(0,0,0,0.1), 0 2px 4px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all 0.2s;
}
.variant-color:hover {
    transform: scale(1.15);
    box-shadow: 0 0 0 2px var(--primary-black);
}
.variant-more {
    font-size: 0.75rem;
    color: var(--text-primary);
    font-weight: 500;
    background: var(--white);
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    border: 1px solid var(--border-light);
}

/* Product Info */
.product-info {
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.product-category {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
    margin-bottom: 0.5rem;
}
.product-name {
    font-size: 0.938rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-decoration: none;
    transition: color 0.2s;
}
.product-name:hover {
    color: var(--primary-black);
}

/* Rating */
.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}
.rating-stars {
    color: var(--accent-gold);
    font-size: 0.875rem;
}
.rating-count {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* Price */
.product-price {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--primary-black);
    margin-bottom: 0.25rem;
}
.price-note {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 0.75rem;
}

/* Stock Badge */
.stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.625rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 2px;
    margin-bottom: 1rem;
}
.stock-badge.in-stock {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #d1fae5;
}
.stock-badge.low-stock {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
}
.stock-badge.out-stock {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* CTA Button */
.btn-view-product {
    background: var(--primary-black);
    color: var(--white);
    border: none;
    padding: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.813rem;
    font-weight: 600;
    transition: all 0.3s;
    text-decoration: none;
    text-align: center;
    display: block;
    margin-top: auto;
}
.btn-view-product:hover {
    background: var(--secondary-black);
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--white);
    border: 1px solid var(--border-light);
}
.empty-state-icon {
    font-size: 4rem;
    color: var(--text-secondary);
    opacity: 0.3;
    margin-bottom: 1.5rem;
}
.empty-state-title {
    font-size: 1.5rem;
    font-weight: 300;
    color: var(--text-primary);
    margin-bottom: 1rem;
}
.btn-reset-empty {
    background: var(--primary-black);
    color: var(--white);
    border: none;
    padding: 0.75rem 2rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.813rem;
    transition: all 0.3s;
}
.btn-reset-empty:hover {
    background: var(--secondary-black);
    transform: translateY(-2px);
}

/* Pagination - Premium */
.pagination {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    margin: 3rem 0 2rem;
}
.pagination .page-link {
    border: 1px solid var(--border-light);
    color: var(--text-primary);
    background: var(--white);
    padding: 0.5rem 0.875rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    text-decoration: none;
    min-width: 2.5rem;
    text-align: center;
    border-radius: 0;
}
.pagination .page-link:hover {
    background: var(--primary-black);
    color: var(--white);
    border-color: var(--primary-black);
}
.pagination .page-item.active .page-link {
    background: var(--primary-black);
    color: var(--white);
    border-color: var(--primary-black);
}
.pagination .page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
}

/* Responsive */
@media (max-width: 991px) {
    .catalog-hero { padding: 2.5rem 0 2rem; }
    .catalog-hero h1 { font-size: 2rem; }
    .filter-sidebar { margin-bottom: 2rem; }
}
</style>
@endpush

@section('content')
<!-- Hero Banner -->
<div class="catalog-hero">
  <div class="container">
    <h1 class="text-center">KATALOG PRODUK</h1>
    <p class="text-center mb-0">Temukan Produk Favorit Anda</p>
  </div>
</div>

<div class="container">
  <div class="row">
    <!-- Filters Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="filter-sidebar">
        <div class="filter-header">
          <i class="mdi mdi-filter-variant me-2"></i>Filter Produk
        </div>
        <div class="filter-body">
          <form method="GET" action="{{ route('member.catalog.index') }}">
            <!-- Kategori -->
            <div class="filter-section">
              <label class="filter-label">Kategori</label>
              <select name="kategori" class="form-select filter-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kategori)
                  <option value="{{ $kategori->id_kategori }}" {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }} ({{ $kategori->produks_count }})
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Harga -->
            <div class="filter-section">
              <label class="filter-label">Rentang Harga</label>
              <div class="row g-2">
                <div class="col-6">
                  <input type="number" name="min_price" class="form-control filter-input" placeholder="Min" value="{{ request('min_price') }}">
                </div>
                <div class="col-6">
                  <input type="number" name="max_price" class="form-control filter-input" placeholder="Max" value="{{ request('max_price') }}">
                </div>
              </div>
            </div>

            <!-- Urutkan -->
            <div class="filter-section">
              <label class="filter-label">Urutkan</label>
              <select name="sort" class="form-select filter-select">
                <option value="featured" {{ request('sort', 'featured') == 'featured' ? 'selected' : '' }}>Featured</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-apply-filter">
                <i class="mdi mdi-magnify me-2"></i>Terapkan Filter
              </button>
              <a href="{{ route('member.catalog.index') }}" class="btn btn-reset-filter">
                <i class="mdi mdi-refresh me-2"></i>Reset
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="col-lg-9">
      @if($produks->count() > 0)
        <div class="product-grid">
          @foreach($produks as $produk)
          <div class="product-card">
            <!-- Image Container -->
            <div class="product-image-wrapper">
              <!-- Badges -->
              <div class="product-badges">
                @if($produk->is_featured)
                  <span class="badge-featured">
                    <i class="mdi mdi-star"></i> Featured
                  </span>
                @endif
                @if($produk->created_at->diffInDays() < 7)
                  <span class="badge-new">New</span>
                @endif
              </div>

              <!-- Wishlist Button -->
              <button class="btn-wishlist" title="Add to Wishlist">
                <i class="mdi mdi-heart-outline"></i>
              </button>

              <!-- Product Image -->
              <a href="{{ route('member.catalog.show', $produk->id_produk) }}">
                <img src="{{ $produk->main_image ?? ( $produk->gambar ? asset('storage/'.$produk->gambar) : asset('assets/images/no_image.jpg') ) }}"
                     alt="{{ $produk->nama_produk }}"
                     class="product-image">
              </a>

              <!-- Variants Bar -->
              @if($produk->has_variants && $produk->activeVariants->count() > 0)
              <div class="variants-bar">
                @foreach($produk->activeVariants->take(5) as $variant)
                  <div class="variant-color"
                       style="background-color: {{ $variant->kode_warna ?? '#cccccc' }};"
                       title="{{ $variant->warna }} - {{ $variant->ukuran }}">
                  </div>
                @endforeach
                @if($produk->activeVariants->count() > 5)
                  <span class="variant-more">+{{ $produk->activeVariants->count() - 5 }}</span>
                @endif
              </div>
              @endif
            </div>

            <!-- Product Info -->
            <div class="product-info">
              <div class="product-category">
                {{ $produk->kategori->nama_kategori ?? 'Uncategorized' }}
              </div>

              <a href="{{ route('member.catalog.show', $produk->id_produk) }}" class="product-name">
                {{ $produk->nama_produk }}
              </a>

              @if($produk->rating_count > 0)
              <div class="product-rating">
                <div class="rating-stars">{!! $produk->rating_stars !!}</div>
                <span class="rating-count">({{ $produk->rating_count }})</span>
              </div>
              @endif

              <div class="product-price">{{ $produk->formatted_price }}</div>
              @if($produk->has_variants)
                <div class="price-note">Mulai dari harga ini</div>
              @endif

              <span class="stock-badge {{ $produk->stock_status == 'in_stock' ? 'in-stock' : ($produk->stock_status == 'low_stock' ? 'low-stock' : 'out-stock') }}">
                <i class="mdi mdi-{{ $produk->stock_status == 'in_stock' ? 'check-circle' : ($produk->stock_status == 'low_stock' ? 'alert-circle' : 'close-circle') }}"></i>
                {{ $produk->stock_status_text }}
              </span>

              <a href="{{ route('member.catalog.show', $produk->id_produk) }}" class="btn-view-product">
                <i class="mdi mdi-eye me-2"></i>Lihat Detail
              </a>
            </div>
          </div>
          @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($produks, 'hasPages') && $produks->hasPages())
        <div class="d-flex justify-content-center">
          {{ $produks->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
        @endif
      @else
        <!-- Empty State -->
        <div class="empty-state">
          <i class="mdi mdi-package-variant-closed empty-state-icon"></i>
          <h2 class="empty-state-title">Tidak ada produk ditemukan</h2>
          <p class="text-muted mb-4">Coba ubah filter atau kata kunci pencarian Anda</p>
          <a href="{{ route('member.catalog.index') }}" class="btn btn-reset-empty">
            <i class="mdi mdi-refresh me-2"></i>Reset Filter
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
