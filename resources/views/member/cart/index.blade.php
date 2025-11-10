@extends('member.layout')
@section('title','Keranjang Belanja')
@push('styles')
<style>
.cart-item{background:#fff;border:1px solid #e8e8e8;border-radius:8px;padding:1.5rem;margin-bottom:1rem;display:flex;gap:1.5rem;align-items:center}
.cart-thumb{width:100px;height:100px;border-radius:8px;object-fit:cover;background:#f7f7f7}
.cart-info{flex:1}
.cart-title{font-size:1rem;font-weight:600;margin-bottom:0.25rem}
.cart-price{font-size:1.125rem;font-weight:600;margin-top:0.5rem}
.qty-control{display:flex;align-items:center;gap:0.5rem;margin-top:0.75rem}
.qty-btn{width:32px;height:32px;border:1px solid #e8e8e8;background:#fff;border-radius:4px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s}
.qty-btn:hover{border-color:#000;background:#f7f7f7}
.qty-input{width:60px;text-align:center;border:1px solid #e8e8e8;border-radius:4px;padding:0.25rem}
.remove-btn{color:#d32f2f;background:none;border:none;padding:0.5rem;cursor:pointer;transition:color 0.2s}
.remove-btn:hover{color:#b71c1c}
.cart-summary{background:#f7f7f7;border-radius:8px;padding:1.5rem;position:sticky;top:80px}
.summary-row{display:flex;justify-content:space-between;margin-bottom:0.75rem}
.summary-total{font-size:1.25rem;font-weight:700;padding-top:1rem;border-top:2px solid #e8e8e8}
.checkout-btn{width:100%;background:#000;color:#fff;padding:1rem;border:none;border-radius:4px;font-weight:600;text-transform:uppercase;cursor:pointer;transition:all 0.3s}
.checkout-btn:hover{background:#333;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,0.2)}
.payment-option{border:2px solid #e8e8e8;border-radius:8px;padding:1rem;margin-bottom:0.75rem;cursor:pointer;transition:all 0.2s}
.payment-option:hover{border-color:#000}
.payment-option.active{border-color:#000;background:#f7f7f7}
.payment-option input[type="radio"]{margin-right:0.5rem}
.empty-cart{text-align:center;padding:4rem 2rem;color:#767676}
.empty-cart i{font-size:4rem;margin-bottom:1rem}
</style>
@endpush
@section('content')
<div class="row">
<div class="col-lg-8">
<h3 style="margin-bottom:1.5rem">Keranjang Belanja</h3>
@if(($items ?? collect())->count() === 0)
<div class="empty-cart">
<i class="mdi mdi-cart-outline"></i>
<h5>Keranjang Anda Kosong</h5>
<p>Mulai belanja dan tambahkan produk favorit Anda</p>
<a href="{{ route('member.catalog.index') }}" class="checkout-btn" style="max-width:300px;margin:1rem auto;display:block">Belanja Sekarang</a>
</div>
@else
@foreach($items as $it)
<div class="cart-item">
@php($img = $it['gambar'] ?? null)
@if($img)
<img src="{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}" alt="{{ $it['nama'] }}" class="cart-thumb">
@else
<div class="cart-thumb" style="display:flex;align-items:center;justify-content:center"><i class="mdi mdi-image-outline" style="font-size:2rem;color:#ccc"></i></div>
@endif
<div class="cart-info">
<div class="cart-title">{{ $it['nama'] }}</div>
<div class="cart-price">Rp {{ number_format($it['harga'],0,',','.') }}</div>
<form action="{{ route('member.cart.update') }}" method="POST" class="qty-control">
@csrf
<input type="hidden" name="id_produk" value="{{ $it['id_produk'] }}">
<button type="button" class="qty-btn" onclick="this.nextElementSibling.stepDown();this.parentElement.submit()">-</button>
<input type="number" name="qty" value="{{ $it['qty'] }}" min="1" class="qty-input" readonly>
<button type="button" class="qty-btn" onclick="this.previousElementSibling.stepUp();this.parentElement.submit()">+</button>
</form>
</div>
<div style="margin-left:auto;text-align:right">
<div style="font-size:1.125rem;font-weight:700;margin-bottom:1rem">Rp {{ number_format($it['subtotal'],0,',','.') }}</div>
<form action="{{ route('member.cart.remove') }}" method="POST" style="display:inline">
@csrf
<input type="hidden" name="id_produk" value="{{ (int)$it['id_produk'] }}">
<button type="submit" class="remove-btn"><i class="mdi mdi-delete-outline"></i> Hapus</button>
</form>
</div>
</div>
@endforeach
@endif
</div>
<div class="col-lg-4">
@if(($items ?? collect())->count() > 0)
<div class="cart-summary">
<h5 style="margin-bottom:1.5rem">Ringkasan Belanja</h5>
<div class="summary-row">
<span>Subtotal</span>
<span>Rp {{ number_format($total,0,',','.') }}</span>
</div>
<div class="summary-row">
<span>Biaya Layanan</span>
<span>Rp 0</span>
</div>
<div class="summary-row summary-total">
<span>Total</span>
<span>Rp {{ number_format($total,0,',','.') }}</span>
</div>
<div style="margin-top:1.5rem">
<h6 style="margin-bottom:1rem">Metode Pembayaran</h6>
<div class="payment-option active" onclick="selectPaymentMethod('midtrans',this)">
<input type="radio" name="payment_method" value="midtrans" id="pay_midtrans" checked>
<label for="pay_midtrans" style="cursor:pointer;margin:0">
<strong>Bayar Online</strong><br>
<small style="color:#767676">Transfer Bank, E-wallet, Kartu Kredit</small>
</label>
</div>
<div class="payment-option" onclick="selectPaymentMethod('in_store',this)">
<input type="radio" name="payment_method" value="in_store" id="pay_store">
<label for="pay_store" style="cursor:pointer;margin:0">
<strong>Bayar di Outlet</strong><br>
<small style="color:#767676">Bayar saat pengambilan barang</small>
</label>
</div>
</div>
<form action="{{ route('member.checkout') }}" method="POST" style="margin-top:1.5rem">
@csrf
<input type="hidden" name="payment_method" id="selected_payment_method" value="midtrans">
<button type="submit" class="checkout-btn">Checkout</button>
</form>
</div>
@endif
</div>
</div>
@endsection
@push('scripts')
<script>
function selectPaymentMethod(method,el){
document.querySelectorAll('.payment-option').forEach(opt=>opt.classList.remove('active'));
if(el)el.classList.add('active');
document.getElementById('selected_payment_method').value=method;
document.getElementById('pay_'+method).checked=true;
}
</script>
@endpush