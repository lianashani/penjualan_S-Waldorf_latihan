@extends('layouts.master')
@section('title', $produk->nama_produk)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('katalog.elegant') }}">Katalog</a></li>
<li class="breadcrumb-item"><a href="#">{{ $produk->kategori->nama_kategori }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $produk->nama_produk }}</li>
@endsection

@section('content')
<div class="container-fluid bw-theme">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('katalog.elegant') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Katalog
            </a>
        </div>
    </div>
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <!-- Main Image -->
                    <div class="main-image-container position-relative">
                        <img id="mainImage"
                             src="{{ $produk->activeImages->first() ? $produk->activeImages->first()->image_url : $produk->main_image }}"
                             class="img-fluid w-100"
                             alt="{{ $produk->nama_produk }}"
                             style="height: 500px; object-fit: cover;">

                        <!-- Image Navigation -->
                        <button class="btn btn-light rounded-circle position-absolute top-50 start-0 translate-middle-y ms-2"
                                onclick="previousImage()" id="prevBtn" style="display: none;">
                            <i class="mdi mdi-chevron-left"></i>
                        </button>
                        <button class="btn btn-light rounded-circle position-absolute top-50 end-0 translate-middle-y me-2"
                                onclick="nextImage()" id="nextBtn" style="display: none;">
                            <i class="mdi mdi-chevron-right"></i>
                        </button>
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="thumbnail-container p-3 bg-light">
                        @if($produk->activeImages->count() > 1)
                        <div class="row g-2">
                            @foreach($produk->activeImages as $index => $image)
                            <div class="col-3">
                                <img src="{{ $image->thumbnail_url }}"
                                     class="img-fluid rounded thumbnail-image {{ $index === 0 ? 'active' : '' }}"
                                     alt="{{ $image->alt_text }}"
                                     style="height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                                     onclick="changeMainImage('{{ $image->image_url }}', {{ $index }})">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <!-- Product Title -->
                    <div class="mb-3">
                        <span class="badge bg-info">{{ $produk->kategori->nama_kategori }}</span>
                        @if($produk->is_featured)
                            <span class="badge bg-warning text-dark ms-2">Featured</span>
                        @endif
                    </div>

                    <h2 class="mb-3">{{ $produk->nama_produk }}</h2>

                    <!-- Role Information -->
                    @if(Auth::user()->role == 'admin')
                        <div class="alert alert-warning mb-3">
                            <i class="mdi mdi-shield-account me-2"></i>
                            <strong>Mode Admin:</strong> Anda hanya dapat melihat detail produk. Untuk transaksi, gunakan akun kasir.
                        </div>
                    @else
                        <div class="alert alert-success mb-3">
                            <i class="mdi mdi-cart me-2"></i>
                            <strong>Mode Kasir:</strong> Anda dapat melakukan transaksi dan menambah produk ke keranjang.
                        </div>
                    @endif

                    <!-- Rating -->
                    @if($produk->rating_count > 0)
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <span class="text-warning fs-4 me-2">{!! $produk->rating_stars !!}</span>
                            <span class="text-muted">({{ $produk->rating_count }} ulasan)</span>
                        </div>
                    </div>
                    @endif

                    <!-- Price -->
                    <div class="mb-4">
                        <h3 class="text-primary mb-0">{{ $produk->formatted_price }}</h3>
                        @if($produk->has_variants)
                            <small class="text-muted">Harga bervariasi sesuai varian</small>
                        @endif
                    </div>

                    <!-- Variant Selection -->
                    @if($produk->has_variants && $produk->activeVariants->count() > 0)
                    <form id="variantForm" method="POST" action="{{ route('keranjang.add') }}">
                        @csrf
                        <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">

                        <!-- Size Selection -->
                        @if($sizes->count() > 1)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ukuran</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($sizes as $size)
                                <input type="radio" class="btn-check" name="ukuran" id="size_{{ $size }}" value="{{ $size }}">
                                <label class="btn btn-outline-secondary" for="size_{{ $size }}">{{ $size }}</label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Color Selection -->
                        @if($colors->count() > 1)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Warna</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                <input type="radio" class="btn-check" name="warna" id="color_{{ $loop->index }}" value="{{ $color }}">
                                <label class="btn btn-outline-secondary" for="color_{{ $loop->index }}">
                                    {{ $color }}
                                    @php
                                        $variant = $produk->activeVariants->where('warna', $color)->first();
                                    @endphp
                                    @if($variant && $variant->kode_warna)
                                        <span class="ms-1" style="display: inline-block; width: 16px; height: 16px; background-color: {{ $variant->kode_warna }}; border-radius: 50%; border: 1px solid #ccc;"></span>
                                    @endif
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Selected Variant Info -->
                        <div id="variantInfo" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>SKU:</strong> <span id="variantSku">-</span><br>
                                        <strong>Stok:</strong> <span id="variantStock">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Harga:</strong> <span id="variantPrice" class="text-primary">-</span><br>
                                        <strong>Status:</strong> <span id="variantStatus">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif

                    <!-- Stock Status -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-{{ $produk->stock_status == 'in_stock' ? 'check-circle text-success' : ($produk->stock_status == 'low_stock' ? 'alert-circle text-warning' : 'close-circle text-danger') }} me-2"></i>
                            <span class="text-{{ $produk->stock_status == 'in_stock' ? 'success' : ($produk->stock_status == 'low_stock' ? 'warning' : 'danger') }}">
                                {{ $produk->stock_status_text }}
                            </span>
                        </div>
                    </div>

                    <!-- Quantity and Add to Cart -->
                    <div class="mb-4">
                        <div class="row align-items-center">
                            @if(Auth::user()->role == 'kasir')
                            <div class="col-auto">
                                <label class="form-label fw-bold">Jumlah</label>
                                <div class="input-group" style="width: 120px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" name="qty" value="1" min="1" max="{{ $produk->has_variants ? $produk->total_stok : $produk->stok }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
                                </div>
                            </div>
                            @endif
                            <div class="col">
                                @if(Auth::user()->role == 'kasir')
                                    <button type="submit" form="variantForm" class="btn btn-primary btn-lg w-100" {{ $produk->stock_status == 'out_of_stock' ? 'disabled' : '' }}>
                                        <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="mdi mdi-login"></i> Beli
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Product Actions -->
                    <div class="d-flex gap-2">
                        @if(Auth::user()->role == 'kasir')
                        <button class="btn btn-outline-secondary" onclick="addToWishlist()">
                            <i class="mdi mdi-heart-outline"></i> Wishlist
                        </button>
                        @endif
                        <button class="btn btn-outline-secondary" onclick="shareProduct()">
                            <i class="mdi mdi-share-variant"></i> Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                                Deskripsi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                Ulasan ({{ $produk->approvedRatings->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <p>{{ $produk->deskripsi ?: 'Deskripsi produk tidak tersedia.' }}</p>

                            @if($produk->has_variants)
                            <h6>Varian Tersedia:</h6>
                            <div class="row">
                                @foreach($produk->activeVariants as $variant)
                                <div class="col-md-3 mb-2">
                                    <div class="border rounded p-2">
                                        <strong>{{ $variant->ukuran }} - {{ $variant->warna }}</strong><br>
                                        <small class="text-muted">Stok: {{ $variant->stok }}</small><br>
                                        <small class="text-primary">{{ $variant->formatted_price }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @if($produk->approvedRatings->count() > 0)
                                @foreach($produk->approvedRatings as $rating)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $rating->display_name }}</h6>
                                            <div class="text-warning mb-2">{!! $rating->stars !!}</div>
                                            <p class="mb-0">{{ $rating->komentar }}</p>
                                        </div>
                                        <small class="text-muted">{{ $rating->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
                            @endif

                            <!-- Add Review Form -->
                            <div class="mt-4">
                                <h6>Tulis Ulasan</h6>
                                <form action="{{ route('katalog.add-rating', $produk->id_produk) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="nama_pengguna" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email_pengguna" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-input">
                                            @for($i = 1; $i <= 5; $i++)
                                                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                                <label for="star{{ $i }}" class="star-label">★</label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Komentar</label>
                                        <textarea class="form-control" name="komentar" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <h4>Produk Terkait</h4>
            <div class="row">
                @foreach($relatedProducts as $related)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card h-100">
                        <a href="{{ route('katalog.elegant-detail', $related->slug) }}">
                            <img src="{{ $related->main_image }}" class="card-img-top" alt="{{ $related->nama_produk }}" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h6 class="card-title">
                                <a href="{{ route('katalog.elegant-detail', $related->slug) }}" class="text-decoration-none text-dark">
                                    {{ $related->nama_produk }}
                                </a>
                            </h6>
                            <div class="text-primary fw-bold">{{ $related->formatted_price }}</div>
                            @if($related->rating_count > 0)
                                <div class="text-warning small">{!! $related->rating_stars !!} ({{ $related->rating_count }})</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Black & White Theme Wrapper (Light) */
.bw-theme {
    background-color: #ffffff;
    color: #0f0f10;
}

.bw-theme a,
.bw-theme .text-dark { color: #0f0f10 !important; }
.bw-theme .text-muted { color: #6b7280 !important; }

.bw-theme .card {
    background-color: #ffffff;
    color: #0f0f10;
    border: 1px solid #e5e7eb;
}

.bw-theme .card-header,
.bw-theme .bg-light { background-color: #f8f9fa !important; border-color: #e5e7eb !important; }

.bw-theme .alert { border-color: #e5e7eb; color: #0f0f10; background-color: #fafafa; }

.bw-theme .badge { background-color: #f3f4f6; color: #111111; border: 1px solid #e5e7eb; }
.bw-theme .badge.bg-warning,
.bw-theme .badge.bg-info,
.bw-theme .badge.bg-primary,
.bw-theme .badge.bg-success,
.bw-theme .badge.bg-danger { background-color: #f3f4f6 !important; color: #111111 !important; border-color: #e5e7eb !important; }

.bw-theme .btn-primary,
.bw-theme .btn-success { background-color: #111111; color: #ffffff; border-color: #111111; }
.bw-theme .btn-primary:hover,
.bw-theme .btn-success:hover { background-color: #000000; border-color: #000000; }

.bw-theme .btn-outline-secondary { color: #111111; border-color: #111111; }
.bw-theme .btn-outline-secondary:hover { background-color: #111111; color: #ffffff; }

.bw-theme .form-control { background-color: #ffffff; color: #0f0f10; border-color: #e5e7eb; }
.bw-theme .form-control:focus { border-color: #111111; box-shadow: 0 0 0 0.2rem rgba(0,0,0,0.06); }

.bw-theme .nav-tabs .nav-link { color: #6b7280; }
.bw-theme .nav-tabs .nav-link.active { border-bottom-color: #111111; color: #111111; }

.bw-theme .page-link { color: #111111; background-color: #ffffff; border-color: #e5e7eb; }
.bw-theme .page-item.active .page-link { background-color: #111111; color: #ffffff; border-color: #111111; }

/* Scrollbar - scoped */
.bw-theme ::-webkit-scrollbar { width: 8px; }
.bw-theme ::-webkit-scrollbar-track { background: #f2f2f3; border-radius: 4px; }
.bw-theme ::-webkit-scrollbar-thumb { background: #c9c9cc; border-radius: 4px; }
.bw-theme ::-webkit-scrollbar-thumb:hover { background: #b5b7bb; }
/* Image Gallery */
.main-image-container {
    overflow: hidden;
    border-radius: 12px 12px 0 0;
}

.thumbnail-image {
    transition: all 0.3s ease;
    border-radius: 8px;
}

.thumbnail-image:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.thumbnail-image.active {
    border-color: #111111 !important;
    box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.15);
}

/* Rating Input */
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input .star-label {
    font-size: 28px;
    color: #ddd;
    cursor: pointer;
    transition: all 0.2s ease;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.rating-input .star-label:hover {
    transform: scale(1.1);
}

.rating-input input[type="radio"]:checked ~ .star-label,
.rating-input .star-label:hover,
.rating-input .star-label:hover ~ .star-label {
    color: #ffc107;
    text-shadow: 0 1px 3px rgba(255, 193, 7, 0.3);
}

/* Product Cards */
.product-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

/* Variant Selection (simple) */
.btn-outline-secondary:hover {
    background-color: #f3f4f6;
    border-color: #111111;
}

.btn-check:checked + .btn-outline-secondary {
    background-color: #111111;
    border-color: #111111;
    color: #ffffff;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
}

/* Quantity Input */
.input-group .btn {
    border-radius: 0;
}

.input-group .form-control {
    border-left: none;
    border-right: none;
}

/* Tabs */
.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #111111;
    color: #111111;
    background: none;
}

/* Related Products */
.related-products .card {
    transition: all 0.3s ease;
}

.related-products .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .main-image-container img {
        height: 300px !important;
    }

    .thumbnail-image {
        height: 60px !important;
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

/* Custom Scrollbar moved to .bw-theme scope above */
</style>
@endpush

@push('scripts')
<script>
let currentQuantity = 1;
let maxQuantity = {{ $produk->has_variants ? $produk->total_stok : $produk->stok }};
let currentImageIndex = 0;
let totalImages = {{ $produk->activeImages->count() }};

function changeMainImage(imageUrl, index = 0) {
    document.getElementById('mainImage').src = imageUrl;
    currentImageIndex = index;

    // Update thumbnail active state
    $('.thumbnail-image').removeClass('active');
    $('.thumbnail-image').eq(index).addClass('active');

    // Show/hide navigation buttons
    if (totalImages > 1) {
        $('#prevBtn, #nextBtn').show();
        $('#prevBtn').toggle(currentImageIndex > 0);
        $('#nextBtn').toggle(currentImageIndex < totalImages - 1);
    }
}

function previousImage() {
    if (currentImageIndex > 0) {
        currentImageIndex--;
        const imageUrl = $('.thumbnail-image').eq(currentImageIndex).attr('onclick').match(/'([^']+)'/)[1];
        changeMainImage(imageUrl, currentImageIndex);
    }
}

function nextImage() {
    if (currentImageIndex < totalImages - 1) {
        currentImageIndex++;
        const imageUrl = $('.thumbnail-image').eq(currentImageIndex).attr('onclick').match(/'([^']+)'/)[1];
        changeMainImage(imageUrl, currentImageIndex);
    }
}

// Initialize image navigation
$(document).ready(function() {
    if (totalImages > 1) {
        $('#prevBtn, #nextBtn').show();
        $('#prevBtn').hide(); // Hide prev button initially
        $('#nextBtn').toggle(totalImages > 1);
    }
    // Always render a thumbnail container for variant image swapping
    if ($('.thumbnail-container').length === 0) {
        $('.main-image-container').after('<div class="thumbnail-container p-3 bg-light"></div>');
    }
});

function increaseQuantity() {
    if (currentQuantity < maxQuantity) {
        currentQuantity++;
        document.getElementById('quantity').value = currentQuantity;
    }
}

function decreaseQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        document.getElementById('quantity').value = currentQuantity;
    }
}

function addToCart() {
    // Add to cart functionality with loading animation
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

function addToWishlist() {
    const button = event.target.closest('button');
    const icon = button.querySelector('i');

    if (icon.classList.contains('mdi-heart-outline')) {
        icon.classList.remove('mdi-heart-outline');
        icon.classList.add('mdi-heart', 'text-danger');
        button.classList.add('btn-danger');
        button.classList.remove('btn-outline-secondary');

        Swal.fire({
            title: 'Ditambahkan!',
            text: 'Produk berhasil ditambahkan ke wishlist',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    } else {
        icon.classList.remove('mdi-heart', 'text-danger');
        icon.classList.add('mdi-heart-outline');
        button.classList.remove('btn-danger');
        button.classList.add('btn-outline-secondary');

        Swal.fire({
            title: 'Dihapus!',
            text: 'Produk dihapus dari wishlist',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false
        });
    }
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $produk->nama_produk }}',
            text: 'Lihat produk ini: {{ $produk->nama_produk }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        Swal.fire({
            title: 'Link Disalin!',
            text: 'Link produk telah disalin ke clipboard',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }
}

// Variant selection handling
@if($produk->has_variants)
$(document).ready(function() {
    $('input[name="ukuran"], input[name="warna"]').change(function() {
        const ukuran = $('input[name="ukuran"]:checked').val();
        const warna = $('input[name="warna"]:checked').val();

        if (ukuran && warna) {
            loadVariantInfo(ukuran, warna);
        }
    });
});

function loadVariantInfo(ukuran, warna) {
    $.ajax({
        url: '{{ route("katalog.variant-images") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            produk_id: {{ $produk->id_produk }},
            ukuran: ukuran,
            warna: warna
        },
        success: function(response) {
            if (response.success) {
                // Update variant info
                $('#variantSku').text(response.variant.sku);
                $('#variantStock').text(response.variant.stok);
                $('#variantPrice').text(response.variant.harga);
                $('#variantStatus').text(response.variant.stock_status_text);
                $('#variantInfo').show();

                // Update images if available
                if (response.images.length > 0) {
                    changeMainImage(response.images[0].url);

                    // Update thumbnails
                    if ($('.thumbnail-container').length === 0) {
                        $('.main-image-container').after('<div class="thumbnail-container p-3 bg-light"></div>');
                    }
                    $('.thumbnail-container').html('');
                    let thumbnailsHtml = '';
                    if (response.images.length > 1) {
                        thumbnailsHtml += '<div class="row g-2">';
                        response.images.forEach(function(image, idx) {
                            thumbnailsHtml += `
                                <div class="col-3">
                                    <img src="${image.thumbnail}"
                                         class="img-fluid rounded thumbnail-image ${idx === 0 ? 'active' : ''}"
                                         alt="${image.alt}"
                                         style="height: 80px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                                         onclick="changeMainImage('${image.url}', ${idx})">
                                </div>
                            `;
                        });
                        thumbnailsHtml += '</div>';
                    }
                    $('.thumbnail-container').html(thumbnailsHtml);
                    // Update navigation counts
                    totalImages = response.images.length;
                    currentImageIndex = 0;
                    if (totalImages > 1) {
                        $('#prevBtn, #nextBtn').show();
                        $('#prevBtn').hide();
                        $('#nextBtn').toggle(true);
                    } else {
                        $('#prevBtn, #nextBtn').hide();
                    }
                }

                // Update max quantity
                maxQuantity = response.variant.stok;
                if (currentQuantity > maxQuantity) {
                    currentQuantity = maxQuantity;
                    $('#quantity').val(currentQuantity);
                }
                $('#quantity').attr('max', maxQuantity);
            }
        },
        error: function() {
            Swal.fire('Error', 'Gagal memuat informasi varian', 'error');
        }
    });
}
@endif
</script>
@endpush
