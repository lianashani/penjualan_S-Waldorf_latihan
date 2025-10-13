@extends('member.layout')
@section('title','Pesanan Saya')
@push('styles')
<style>
.card-plain{ background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:16px }
.badge-status{ border-radius:999px; padding:6px 10px; font-weight:600 }
.badge-paid{ background:#111; color:#fff }
.badge-pending{ background:#999; color:#fff }
.badge-debt{ background:#b45309; color:#fff }
.badge-shipped{ background:#0ea5e9; color:#fff }
.badge-completed{ background:#16a34a; color:#fff }
.badge-cancelled{ background:#dc2626; color:#fff }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h4 class="mb-0">Pesanan Saya</h4>
  <a href="{{ route('member.catalog.index') }}" class="btn btn-outline-dark"><i class="mdi mdi-store"></i> Belanja Lagi</a>
</div>
<div class="card-plain">
  @if(($orders ?? collect())->count() === 0)
    <div class="text-center text-muted py-4">
      <i class="mdi mdi-history" style="font-size:48px"></i>
      <div class="mt-2">Belum ada pesanan</div>
    </div>
  @else
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:140px">Tanggal</th>
            <th style="width:120px">Order</th>
            <th>Metode</th>
            <th>Status</th>
            <th class="text-end" style="width:160px">Total</th>
            <th style="width:120px"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $o)
          <tr>
            <td>{{ $o->created_at->format('d M Y H:i') }}</td>
            <td>#{{ $o->id_order }}</td>
            <td class="text-capitalize">{{ $o->payment_method }}</td>
            <td>
              @php($b='badge-'.$o->status)
              <span class="badge-status {{ 'badge-'.$o->status }}">{{ ucfirst($o->status) }}</span>
            </td>
            <td class="text-end">Rp {{ number_format($o->total,0,',','.') }}</td>
            <td class="text-end">
              <a href="{{ route('member.orders.show',$o->id_order) }}" class="btn btn-dark btn-sm">Detail</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
