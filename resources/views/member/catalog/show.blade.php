@extends('member.layout')
@section('title', $produk->nama_produk)
@push('styles')
<style>
.card-plain{ background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:20px }
.product-img{ min-height:360px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; overflow:hidden; border-radius:12px }
.product-img img{ max-width:100%; max-height:500px; object-fit:contain }
</style>
@endpush

@section('content')
<div class="row g-3">
  <div class="col-md-5">
    <div class="product-img">
      @if($produk->gambar)
        <img src="{{ asset('storage/'.$produk->gambar) }}" alt="{{ $produk->nama_produk }}">
      @else
        <i class="mdi mdi-image-off" style="font-size:72px;color:#999"></i>
      @endif
    </div>
  </div>
  <div class="col-md-7">
    <div class="card-plain h-100">
      <div class="text-muted small mb-1">{{ $produk->kategori->nama_kategori ?? '-' }}</div>
      <h3 class="mb-2">{{ $produk->nama_produk }}</h3>
      <div class="mb-3"><span class="badge bg-dark">{{ $produk->ukuran }}</span> <span class="badge bg-secondary">{{ $produk->warna }}</span></div>
      <h2 class="text-primary">Rp {{ number_format($produk->harga,0,',','.') }}</h2>
      <div class="text-muted mb-3">Stok: {{ $produk->stok }}</div>
      @if($produk->deskripsi)
      <p class="mb-3">{{ $produk->deskripsi }}</p>
      @endif
      <form action="{{ route('member.cart.add') }}" method="POST" class="d-flex gap-2 align-items-center">
        @csrf
        <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
        <input type="number" name="qty" class="form-control" value="1" min="1" style="max-width:120px">
        <button class="btn btn-dark"><i class="mdi mdi-cart-plus"></i> Tambah ke Keranjang</button>
      </form>
    </div>
  </div>
</div>
@endsection
