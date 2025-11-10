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
      <div class="mb-2"><strong>Order Number</strong><br>{{ $order->order_number ?? '#'.$order->id_order }}</div>
      <div class="mb-2"><strong>Nama Pembeli</strong><br>{{ $order->member->nama_member ?? 'N/A' }}</div>
      @if($order->member->email)
        <div class="mb-2"><strong>Email</strong><br>{{ $order->member->email }}</div>
      @endif
      @if($order->member->no_hp)
        <div class="mb-2"><strong>No. HP</strong><br>{{ $order->member->no_hp }}</div>
      @endif
      <div class="mb-2"><strong>Tanggal</strong><br>{{ $order->created_at->format('d M Y H:i') }}</div>
      <div class="mb-2"><strong>Metode Pembayaran</strong><br>
        @if($order->payment_method === 'midtrans')
          <span class="badge bg-primary">Midtrans</span>
          @if($order->payment_type)
            <small class="text-muted">({{ ucwords(str_replace('_', ' ', $order->payment_type)) }})</small>
          @endif
        @else
          <span class="text-capitalize">{{ $order->payment_method }}</span>
        @endif
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-2"><strong>Status Pesanan</strong><br>
        @php
          $statusBadges = [
            'pending' => 'warning',
            'awaiting_preparation' => 'info',
            'ready_for_pickup' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
          ];
          $badge = $statusBadges[$order->status] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
      </div>

      @if($order->payment_method === 'midtrans')
        <div class="mb-2"><strong>Status Pembayaran</strong><br>
          @php
            $paymentBadges = [
              'pending' => 'warning',
              'paid' => 'success',
              'failed' => 'danger',
              'expired' => 'secondary',
            ];
            $paymentBadge = $paymentBadges[$order->payment_status] ?? 'secondary';
          @endphp
          <span class="badge bg-{{ $paymentBadge }}">{{ ucfirst($order->payment_status) }}</span>
          @if($order->paid_at)
            <br><small class="text-muted">Dibayar: {{ $order->paid_at->format('d M Y H:i') }}</small>
          @endif
        </div>

        @if($order->transaction_id)
          <div class="mb-2"><strong>Transaction ID</strong><br>
            <small class="font-monospace">{{ $order->transaction_id }}</small>
          </div>
        @endif
      @endif

      <div class="mb-2"><strong>Total</strong><br>
        <span class="h5 text-primary">Rp {{ number_format($order->total,0,',','.') }}</span>
      </div>
    </div>
  </div>

  @if($order->payment_method === 'midtrans' && $order->payment_status === 'pending')
    <div class="alert alert-warning mt-3 mb-0">
      <i class="mdi mdi-clock-outline"></i> Menunggu konfirmasi pembayaran.
      <div class="mt-2">
        @if($order->snap_token)
          <button class="btn btn-sm btn-primary" id="pay-again-button">Bayar Sekarang</button>
        @endif
        <button class="btn btn-sm btn-success ms-2" id="check-status-button">
          <i class="mdi mdi-refresh"></i> Cek Status Pembayaran
        </button>
      </div>
    </div>
  @endif

  {{-- Debug Info --}}
  {{-- Status: {{ $order->status }} | Payment Status: {{ $order->payment_status ?? 'null' }} --}}

  @php
    $canCancel = in_array($order->status, ['pending', 'awaiting_preparation']) &&
                 (!$order->payment_status || $order->payment_status !== 'paid');
  @endphp

  @if($canCancel)
    <div class="mt-3">
      <button class="btn btn-danger w-100" id="cancel-order-button">
        <i class="mdi mdi-close-circle"></i> Batalkan Pesanan
      </button>
    </div>
  @else
    {{-- Debug: Tidak bisa dibatalkan. Status: {{ $order->status }}, Payment: {{ $order->payment_status ?? 'null' }} --}}
  @endif
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
@if($order->payment_method === 'midtrans' && $order->snap_token)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const payAgainBtn = document.getElementById('pay-again-button');
  if(payAgainBtn) {
    payAgainBtn.addEventListener('click', function() {
      snap.pay('{{ $order->snap_token }}', {
        onSuccess: function(result) {
          window.location.reload();
        },
        onPending: function(result) {
          window.location.reload();
        },
        onError: function(result) {
          alert('Pembayaran gagal: ' + result.status_message);
        }
      });
    });
  }

  // Check Status Button
  const checkStatusBtn = document.getElementById('check-status-button');
  if(checkStatusBtn) {
    checkStatusBtn.addEventListener('click', function() {
      checkStatusBtn.disabled = true;
      checkStatusBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengecek...';

      fetch('{{ route("member.payment.check-status", $order->order_number) }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          if(data.payment_status === 'paid') {
            alert('Pembayaran berhasil dikonfirmasi! Halaman akan di-refresh.');
            window.location.reload();
          } else if(data.transaction_status === 'pending') {
            alert('Pembayaran masih pending. Silakan selesaikan pembayaran terlebih dahulu.');
            checkStatusBtn.disabled = false;
            checkStatusBtn.innerHTML = '<i class="mdi mdi-refresh"></i> Cek Status Pembayaran';
          } else {
            alert('Status: ' + data.transaction_status);
            window.location.reload();
          }
        } else {
          alert('Gagal mengecek status: ' + data.message);
          checkStatusBtn.disabled = false;
          checkStatusBtn.innerHTML = '<i class="mdi mdi-refresh"></i> Cek Status Pembayaran';
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengecek status pembayaran');
        checkStatusBtn.disabled = false;
        checkStatusBtn.innerHTML = '<i class="mdi mdi-refresh"></i> Cek Status Pembayaran';
      });
    });
  }

  // Cancel Order Button
  const cancelBtn = document.getElementById('cancel-order-button');
  if(cancelBtn) {
    cancelBtn.addEventListener('click', function() {
      if(confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        cancelBtn.disabled = true;
        cancelBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Membatalkan...';

        fetch("{{ route('member.orders.cancel', $order->id_order) }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(response => response.json())
        .then(data => {
          if(data.success) {
            alert('Pesanan berhasil dibatalkan');
            window.location.reload();
          } else {
            alert('Gagal membatalkan pesanan: ' + data.message);
            cancelBtn.disabled = false;
            cancelBtn.innerHTML = '<i class="mdi mdi-close-circle"></i> Batalkan Pesanan';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat membatalkan pesanan');
          cancelBtn.disabled = false;
          cancelBtn.innerHTML = '<i class="mdi mdi-close-circle"></i> Batalkan Pesanan';
        });
      }
    });
  }
});
</script>
@endif

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
      span.innerText = s.status.replace(/_/g, ' ').toUpperCase();
      timeline.appendChild(span);
    });
  }catch(e){/* ignore */}
})();
</script>
@endpush
@endsection
