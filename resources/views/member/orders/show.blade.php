@extends('member.layout')
@section('title','Detail Pesanan')
@push('styles')
<style>
.card-plain{ background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:16px }
.timeline{ display:flex; gap:10px; flex-wrap:wrap }
.timeline .step{ padding:8px 12px; border-radius:999px; border:1px solid #e5e5e5 }
.timeline .done{ background:#111; color:#fff; border-color:#111 }
.thumb{ width:48px; height:48px; border-radius:8px; object-fit:cover; background:#f5f5f5 }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Detail Pesanan #{{ $order->id_order }}</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('member.orders') }}" class="btn btn-outline-dark"><i class="mdi mdi-arrow-left"></i> Kembali</a>
    <a href="{{ route('member.orders.receipt', $order->id_order) }}" target="_blank" class="btn btn-dark"><i class="mdi mdi-printer"></i> Tampilkan Struk</a>
  </div>
</div>
<div class="card-plain mb-3">
  <div class="row">
    <div class="col-md-6">
      <div><strong>Tanggal</strong><br>{{ $order->created_at->format('d M Y H:i') }}</div>
      <div><strong>Metode</strong><br class="d-md-none"/><span class="text-capitalize">{{ $order->payment_method }}</span></div>
    </div>
    <div class="col-md-6">
      <div><strong>Status</strong><br>{{ ucfirst($order->status) }}</div>
      <div><strong>Total</strong><br>Rp {{ number_format($order->total,0,',','.') }}</div>
    </div>
  </div>
</div>
<div class="card-plain mb-3">
  <h6 class="mb-2">Timeline Status</h6>
  <div id="timeline" class="timeline"></div>
</div>
<div class="card-plain">
  <h6 class="mb-2">Item</h6>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th style="width:64px"></th>
          <th>Produk</th>
          <th class="text-end" style="width:160px">Harga</th>
          <th style="width:100px">Qty</th>
          <th class="text-end" style="width:160px">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $it)
        <tr>
          <td>
            @php($img = $it->produk->gambar ?? null)
            @if($img)
              <img src="{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}" alt="{{ $it->produk->nama_produk ?? ('Produk #'.$it->id_produk) }}" class="thumb">
            @else
              <div class="thumb d-flex align-items-center justify-content-center"><i class="mdi mdi-image-outline"></i></div>
            @endif
          </td>
          <td>{{ $it->produk->nama_produk ?? ('Produk #'.$it->id_produk) }}</td>
          <td class="text-end">Rp {{ number_format($it->harga,0,',','.') }}</td>
          <td>{{ $it->qty }}</td>
          <td class="text-end">Rp {{ number_format($it->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@push('scripts')
<script>
(async function(){
  try{
    const res = await fetch("{{ route('member.orders.track',$order->id_order) }}");
    const data = await res.json();
    const timeline = document.getElementById('timeline');
    const steps = data.timeline || [];
    steps.forEach(s=>{
      const span = document.createElement('span');
      span.className = 'step' + (s.reached ? ' done' : '');
      span.innerText = s.status.toUpperCase();
      timeline.appendChild(span);
    });
  }catch(e){/* ignore */}
})();
</script>
@endpush
@endsection
