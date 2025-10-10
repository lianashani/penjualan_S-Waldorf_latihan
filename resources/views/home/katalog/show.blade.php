@extends('layouts.master')
@section('title', 'Detail Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('katalog.index') }}">Katalog</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $produk->nama_produk }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <div class="product-image-large">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" 
                             class="img-fluid" alt="{{ $produk->nama_produk }}">
                    @else
                        <div class="no-image-large">
                            <i class="mdi mdi-image-off"></i>
                            <p>No Image Available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <span class="badge badge-info mb-2">{{ $produk->kategori->nama_kategori ?? '-' }}</span>
                <h3 class="card-title mb-3">{{ $produk->nama_produk }}</h3>
                
                <h2 class="text-primary mb-4">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</h2>

                <div class="product-info mb-4">
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Kategori:</strong></td>
                            <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ukuran:</strong></td>
                            <td><span class="badge badge-secondary">{{ $produk->ukuran }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Warna:</strong></td>
                            <td><span class="badge badge-secondary">{{ $produk->warna }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Stok Tersedia:</strong></td>
                            <td>
                                @if($produk->stok > 10)
                                    <span class="badge badge-success">{{ $produk->stok }} pcs</span>
                                @elseif($produk->stok > 0)
                                    <span class="badge badge-warning">{{ $produk->stok }} pcs (Terbatas!)</span>
                                @else
                                    <span class="badge badge-danger">Habis</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                @if($produk->deskripsi)
                <div class="product-description mb-4">
                    <h5>Deskripsi Produk</h5>
                    <p>{{ $produk->deskripsi }}</p>
                </div>
                @endif

                <div class="product-actions">
                    <a href="{{ route('katalog.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Kembali ke Katalog
                    </a>
                    @if($produk->stok > 0)
                    <button class="btn btn-success" onclick="alert('Fitur keranjang belum tersedia')">
                        <i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang
                    </button>
                    @else
                    <button class="btn btn-secondary" disabled>
                        <i class="mdi mdi-close-circle"></i> Stok Habis
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Produk Terkait</h4>
                <h6 class="card-subtitle mb-4">Produk lain dari kategori {{ $produk->kategori->nama_kategori ?? '' }}</h6>
                
                <div class="row">
                    @foreach($relatedProducts as $related)
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card product-card-small h-100">
                            <div class="product-image-small">
                                @if($related->gambar)
                                    <img src="{{ asset('storage/' . $related->gambar) }}" 
                                         class="card-img-top" alt="{{ $related->nama_produk }}">
                                @else
                                    <div class="no-image-small">
                                        <i class="mdi mdi-image-off"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{ $related->nama_produk }}</h6>
                                <p class="text-primary mb-2"><strong>Rp. {{ number_format($related->harga, 0, ',', '.') }}</strong></p>
                                <a href="{{ route('katalog.show', $related->id_produk) }}" 
                                   class="btn btn-sm btn-outline-primary btn-block">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.product-image-large {
    min-height: 400px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    overflow: hidden;
}

.product-image-large img {
    max-width: 100%;
    max-height: 500px;
    object-fit: contain;
}

.no-image-large {
    text-align: center;
    color: #999;
}

.no-image-large i {
    font-size: 80px;
    margin-bottom: 20px;
}

.product-info table td {
    padding: 10px 0;
}

.product-actions {
    display: flex;
    gap: 10px;
}

.product-actions .btn {
    flex: 1;
}

.product-card-small {
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.product-card-small:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.product-image-small {
    height: 180px;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image-small img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image-small {
    color: #999;
    text-align: center;
}

.no-image-small i {
    font-size: 40px;
}

.product-card-small .card-title {
    font-size: 14px;
    min-height: 40px;
}
</style>
@endpush
