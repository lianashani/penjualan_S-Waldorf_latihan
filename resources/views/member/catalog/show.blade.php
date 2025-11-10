@extends('member.layout')
@section('title', $produk->nama_produk)
@push('styles')
<style>
.product-container{max-width:1400px;margin:0 auto;padding:2rem 0}
.product-images{position:relative}
.main-image{width:100%;height:500px;background:#f7f7f7;border-radius:12px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:1rem}
.main-image img{max-width:100%;max-height:100%;object-fit:contain}
.thumbnail-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem}
.thumbnail{height:100px;background:#f7f7f7;border:2px solid #e8e8e8;border-radius:8px;cursor:pointer;overflow:hidden;display:flex;align-items:center;justify-content:center;transition:all 0.3s}
.thumbnail:hover,.thumbnail.active{border-color:#000}
.thumbnail img{max-width:100%;max-height:100%;object-fit:cover}
.product-info{background:#fff;border:1px solid #e8e8e8;border-radius:12px;padding:2rem}
.product-title{font-size:1.75rem;font-weight:700;margin-bottom:0.5rem}
.product-category{color:#767676;font-size:0.875rem;margin-bottom:1rem}
.product-price{font-size:2rem;font-weight:700;color:#000;margin-bottom:1rem}
.product-stock{display:inline-block;padding:0.5rem 1rem;border-radius:20px;font-size:0.875rem;font-weight:600;margin-bottom:1rem}
.stock-available{background:#d1fae5;color:#065f46}
.stock-low{background:#fef3c7;color:#92400e}
.stock-out{background:#fee2e2;color:#991b1b}
.product-desc{color:#4b5563;line-height:1.6;margin-bottom:2rem;padding:1rem;background:#f9fafb;border-radius:8px}
.add-to-cart-form{display:flex;gap:1rem;align-items:center;margin-bottom:1.5rem}
.qty-input{width:100px;padding:0.75rem;border:1px solid #e8e8e8;border-radius:6px;text-align:center}
.add-btn{flex:1;background:#000;color:#fff;padding:0.875rem;border:none;border-radius:6px;font-weight:600;text-transform:uppercase;cursor:pointer;transition:all 0.3s}
.add-btn:hover:not(:disabled){background:#333;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.2)}
.add-btn:disabled{background:#d1d5db;cursor:not-allowed}
.chat-btn{width:100%;background:#000;color:#fff;padding:0.875rem;border:none;border-radius:6px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:0.5rem;cursor:pointer;transition:all 0.3s;border:1px solid #000}
.chat-btn:hover{background:#333;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.3)}
.badge-tag{display:inline-block;padding:0.25rem 0.75rem;border-radius:4px;font-size:0.75rem;font-weight:600;margin-right:0.5rem}
</style>
@endpush
@section('content')
<div class="product-container">
<div class="row g-4">
<div class="col-lg-6">
<div class="product-images">
<div class="main-image" id="mainImage">
@php
$images = [];
if($produk->images && $produk->images->count() > 0) {
foreach($produk->images as $img) {
$images[] = $img->gambar;
}
} elseif($produk->gambar) {
$images[] = $produk->gambar;
}
@endphp
@if(count($images) > 0)
<img src="{{ \Illuminate\Support\Str::startsWith($images[0], ['http://','https://','/']) ? $images[0] : asset('storage/'.$images[0]) }}" alt="{{ $produk->nama_produk }}" id="mainImg">
@else
<i class="mdi mdi-image-off" style="font-size:4rem;color:#ccc"></i>
@endif
</div>
@if(count($images) > 1)
<div class="thumbnail-grid">
@foreach($images as $index => $img)
<div class="thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}')">
<img src="{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}" alt="Image {{ $index + 1 }}">
</div>
@endforeach
</div>
@endif
</div>
</div>
<div class="col-lg-6">
<div class="product-info">
<div class="product-category">{{ $produk->kategori->nama_kategori ?? 'Uncategorized' }}</div>
<h1 class="product-title">{{ $produk->nama_produk }}</h1>
<div style="margin-bottom:1rem">
@if($produk->ukuran)<span class="badge-tag" style="background:#f3f4f6;color:#374151">{{ $produk->ukuran }}</span>@endif
@if($produk->warna)<span class="badge-tag" style="background:#f3f4f6;color:#374151">{{ $produk->warna }}</span>@endif
</div>
<div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
@if($produk->stok > 0)
<span class="product-stock {{ $produk->stok > 10 ? 'stock-available' : 'stock-low' }}">
<i class="mdi mdi-package-variant"></i> Stok: {{ $produk->stok }} tersedia
</span>
@else
<span class="product-stock stock-out">
<i class="mdi mdi-package-variant-closed"></i> Stok Habis
</span>
@endif
@if($produk->deskripsi)
<div class="product-desc">{{ $produk->deskripsi }}</div>
@endif
<form action="{{ route('member.cart.add') }}" method="POST" class="add-to-cart-form">
@csrf
<input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
<input type="number" name="qty" class="qty-input" value="1" min="1" max="{{ $produk->stok }}" {{ $produk->stok < 1 ? 'disabled' : '' }}>
<button type="submit" class="add-btn" {{ $produk->stok < 1 ? 'disabled' : '' }}>
<i class="mdi mdi-cart-plus"></i> {{ $produk->stok < 1 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
</button>
</form>
<button type="button" class="chat-btn" onclick="openChat()">
<i class="mdi mdi-message-text"></i> Chat dengan Kasir
</button>
</div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
function changeImage(thumbnail, src) {
document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
thumbnail.classList.add('active');
document.getElementById('mainImg').src = src;
}
function openChat() {
window.location.href = '{{ route("member.chat") }}';
}
</script>
@endpush
