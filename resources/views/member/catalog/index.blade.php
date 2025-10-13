@extends('member.layout')
@section('title','Katalog')
@push('styles')
<style>
.card-plain{ background:#fff; border:1px solid #e5e5e5; border-radius:12px; }
.product{ height:100%; display:flex; flex-direction:column }
.product-img{ height:200px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; overflow:hidden }
.product-img img{ width:100%; height:100%; object-fit:cover }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Katalog Produk</h4>
  <a href="{{ route('member.cart.index') }}" class="btn btn-dark"><i class="mdi mdi-cart"></i> Keranjang</a>
</div>
<div class="row g-3">
@forelse($produks as $p)
  <div class="col-lg-3 col-md-4 col-sm-6">
    <div class="card-plain p-2 product">
      <div class="product-img">
        @if($p->gambar)
          <img src="{{ asset('storage/'.$p->gambar) }}" alt="{{ $p->nama_produk }}">
        @else
          <i class="mdi mdi-image-off" style="font-size:48px;color:#999"></i>
        @endif
      </div>
      <div class="p-2">
        <div class="text-muted small">{{ $p->kategori->nama_kategori ?? '-' }}</div>
        <div class="fw-bold">{{ $p->nama_produk }}</div>
        <div class="text-primary">Rp {{ number_format($p->harga,0,',','.') }}</div>
      </div>
      <div class="mt-auto p-2 d-flex gap-2">
        <a href="{{ route('member.catalog.show',$p->id_produk) }}" class="btn btn-outline-dark w-100">Detail</a>
        <form action="{{ route('member.cart.add') }}" method="POST">
          @csrf
          <input type="hidden" name="id_produk" value="{{ $p->id_produk }}">
          <button class="btn btn-dark"><i class="mdi mdi-cart-plus"></i></button>
        </form>
      </div>
    </div>
  </div>
@empty
  <div class="col-12"><div class="alert alert-info">Tidak ada produk tersedia.</div></div>
@endforelse
</div>
@endsection
