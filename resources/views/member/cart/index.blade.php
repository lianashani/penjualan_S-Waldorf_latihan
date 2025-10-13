@extends('member.layout')
@section('title','Keranjang')
@push('styles')
<style>
.card-plain{ background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:16px }
.qty{ width:90px }
.thumb{ width:56px; height:56px; border-radius:8px; object-fit:cover; background:#f5f5f5 }
.pay-note{ font-size:12px; color:#555 }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Keranjang</h4>
  <a href="{{ route('member.catalog.index') }}" class="btn btn-outline-dark"><i class="mdi mdi-store"></i> Katalog</a>
</div>
<div class="card-plain">
  @if(($items ?? collect())->count() === 0)
    <div class="text-center text-muted py-4">
      <i class="mdi mdi-cart-outline" style="font-size:48px"></i>
      <div class="mt-2">Keranjang masih kosong</div>
    </div>
  @else
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:72px"></th>
            <th>Produk</th>
            <th class="text-end" style="width:160px">Harga</th>
            <th style="width:160px">Qty</th>
            <th class="text-end" style="width:180px">Subtotal</th>
            <th style="width:80px"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $it)
          <tr>
            <td>
              @php($img = $it['gambar'] ?? null)
              @if($img)
                <img src="{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}" alt="{{ $it['nama'] }}" class="thumb">
              @else
                <div class="thumb d-flex align-items-center justify-content-center"><i class="mdi mdi-image-outline"></i></div>
              @endif
            </td>
            <td>{{ $it['nama'] }}</td>
            <td class="text-end">Rp {{ number_format($it['harga'],0,',','.') }}</td>
            <td>
              <form action="{{ route('member.cart.update') }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="hidden" name="id_produk" value="{{ $it['id_produk'] }}">
                <input type="number" name="qty" value="{{ $it['qty'] }}" min="1" class="form-control qty">
                <button class="btn btn-dark">Update</button>
              </form>
            </td>
            <td class="text-end">Rp {{ number_format($it['subtotal'],0,',','.') }}</td>
            <td>
              <form action="{{ route('member.cart.remove') }}" method="POST">
                @csrf
                <input type="hidden" name="id_produk" value="{{ (int)$it['id_produk'] }}">
                <button class="btn btn-outline-danger"><i class="mdi mdi-delete"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="d-flex flex-column align-items-end mt-3">
      <div class="text-end w-100" style="max-width:420px">
        <div class="fw-bold">Total: <span class="text-primary">Rp {{ number_format($total,0,',','.') }}</span></div>
        <div class="pay-note"><i class="mdi mdi-store"></i> Pembayaran dilakukan saat pengambilan di outlet (Bayar di Outlet)</div>
        <form action="{{ route('member.checkout') }}" method="POST" class="d-flex gap-2 align-items-center mt-2">
          @csrf
          <button class="btn btn-dark"><i class="mdi mdi-check"></i> Buat Pesanan (Bayar di Outlet)</button>
        </form>
      </div>
    </div>
  @endif
</div>
@endsection
