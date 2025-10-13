@extends('layouts.master')
@section('title', 'Katalog Produk S&Waldorf')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Katalog Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Katalog Produk S&Waldorf Retail Fashion</h4>
                <h6 class="card-subtitle mb-4">Koleksi fashion terlengkap dengan harga terbaik</h6>

                <!-- Filter & Search -->
                <form method="GET" action="{{ route('katalog.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Cari Produk</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Cari nama produk..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id_kategori }}"
                                                {{ request('kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }} ({{ $kategori->produks_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Urutkan</label>
                                <select name="sort" class="form-control">
                                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
                                    <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
                                    <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="mdi mdi-magnify"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Product Grid -->
                <div class="row">
                    @forelse($produks as $produk)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card product-card h-100">
                            <div class="product-image">
                                @if($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}"
                                         class="card-img-top" alt="{{ $produk->nama_produk }}">
                                @else
                                    <div class="no-image">
                                        <i class="mdi mdi-image-off"></i>
                                        <p>No Image</p>
                                    </div>
                                @endif

                                <!-- Stock Badge -->
                                @if($produk->stok <= 10)
                                    <span class="badge badge-warning stock-badge">Stok Terbatas!</span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <span class="badge badge-info mb-2">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                                <h5 class="card-title">{{ $produk->nama_produk }}</h5>

                                <div class="product-details mb-2">
                                    <small class="text-muted">
                                        <i class="mdi mdi-ruler"></i> {{ $produk->ukuran }} |
                                        <i class="mdi mdi-palette"></i> {{ $produk->warna }}
                                    </small>
                                </div>

                                <div class="product-stock mb-2">
                                    <small>
                                        <i class="mdi mdi-package-variant"></i>
                                        Stok: <strong>{{ $produk->stok }}</strong>
                                    </small>
                                </div>

                                <div class="mt-auto">
                                    <h4 class="text-primary mb-3">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</h4>

                                    @if(Auth::user()->role == 'kasir')
                                    <!-- Add to Cart Button for Kasir -->
                                    <form action="{{ route('keranjang.add') }}" method="POST" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('katalog.show', $produk->id_produk) }}"
                                       class="btn btn-outline-primary btn-block">
                                        <i class="mdi mdi-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="mdi mdi-information"></i>
                            Tidak ada produk yang ditemukan. Silakan coba filter lain.
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                {{-- <div class="mt-4">
                    {{ $produks->appends(request()->query())->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
    overflow: hidden;
}

.product-card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    height: 250px;
    overflow: hidden;
    background: #f5f5f5;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #999;
}

.no-image i {
    font-size: 48px;
    margin-bottom: 10px;
}

.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 11px;
    padding: 5px 10px;
}

.product-card .card-title {
    font-size: 16px;
    font-weight: 600;
    min-height: 40px;
    margin-bottom: 10px;
}

.product-details {
    font-size: 13px;
}

.product-stock {
    font-size: 13px;
    color: #666;
}
</style>
@endpush
