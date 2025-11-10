@extends('member.layout')
@section('title','Pembayaran')
@push('styles')
<style>
.payment-container{max-width:800px;margin:2rem auto;padding:2rem;background:#fff;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.1)}
.payment-header{text-align:center;margin-bottom:2rem}
.payment-header h2{font-size:1.75rem;font-weight:700;margin-bottom:0.5rem}
.payment-info{background:#f7f7f7;padding:1.5rem;border-radius:8px;margin-bottom:2rem}
.info-row{display:flex;justify-content:space-between;margin-bottom:0.75rem}
.info-label{color:#767676;font-size:0.875rem}
.info-value{font-weight:600}
.total-row{border-top:2px solid #e8e8e8;padding-top:1rem;margin-top:1rem}
.total-amount{font-size:1.5rem;font-weight:700;color:#000}
#pay-button{background:#00aa13;color:#fff;padding:1rem 3rem;border:none;border-radius:8px;font-size:1.125rem;font-weight:700;cursor:pointer;width:100%;transition:all 0.3s}
#pay-button:hover{background:#009610;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,170,19,0.3)}
.loading{text-align:center;padding:2rem;color:#767676}
</style>
@endpush

@section('content')
<div class="payment-container">
    <div class="payment-header">
        <h2>Pembayaran Order</h2>
        <p style="color:#767676">{{ $order->order_number }}</p>
    </div>

    <div class="payment-info">
        <div class="info-row">
            <span class="info-label">Tanggal Order:</span>
            <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Item:</span>
            <span class="info-value">{{ $order->items->count() }} produk</span>
        </div>
        <div class="info-row total-row">
            <span class="info-label" style="font-size:1.125rem">Total Pembayaran:</span>
            <span class="total-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    <button id="pay-button">Bayar Sekarang</button>

    <div id="loading" class="loading" style="display:none">
        <i class="mdi mdi-loading mdi-spin" style="font-size:2rem"></i>
        <p>Memuat halaman pembayaran...</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').onclick = function(){
    document.getElementById('pay-button').style.display = 'none';
    document.getElementById('loading').style.display = 'block';

    snap.pay('{{ $order->snap_token }}', {
        onSuccess: function(result){
            window.location.href = '{{ route("member.payment.finish") }}?order_id={{ $order->id_order }}&status=success';
        },
        onPending: function(result){
            window.location.href = '{{ route("member.payment.finish") }}?order_id={{ $order->id_order }}&status=pending';
        },
        onError: function(result){
            window.location.href = '{{ route("member.payment.finish") }}?order_id={{ $order->id_order }}&status=error';
        },
        onClose: function(){
            document.getElementById('pay-button').style.display = 'block';
            document.getElementById('loading').style.display = 'none';
        }
    });
};
</script>
@endpush
