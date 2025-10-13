@extends('layouts.master')
@section('title','Detail Order Member')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
@php($isKasir = (auth()->check() && (auth()->user()->role ?? null) === 'kasir'))
<li class="breadcrumb-item"><a href="{{ $isKasir ? route('kasir.member-orders.index') : route('admin.member-orders.index') }}">Antrian Order</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 class="card-title">Order {{ $order->order_number ?? ('#'.$order->id_order) }}</h4>
            <h6 class="card-subtitle">{{ $order->created_at->format('d M Y H:i') }}</h6>
          </div>
          <div>
            @php($map=[
              'awaiting_preparation'=>'badge-warning',
              'ready_for_pickup'=>'badge-info',
              'completed'=>'badge-success',
              'cancelled'=>'badge-danger',
            ])
            <span class="badge {{ $map[$order->status] ?? 'badge-secondary' }}">{{ str_replace('_',' ', ucfirst($order->status)) }}</span>
          </div>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-3">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="mb-3">Info Member</h5>
                <div><strong>Nama:</strong> {{ $order->member->nama_member ?? '-' }}</div>
                <div><strong>Email:</strong> {{ $order->member->email ?? '-' }}</div>
                <div><strong>No HP:</strong> {{ $order->member->no_hp ?? '-' }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="mb-3">Ringkasan</h5>
                <div class="d-flex justify-content-between"><span>Subtotal</span><strong>Rp {{ number_format($order->subtotal ?? $order->total,0,',','.') }}</strong></div>
                <div class="d-flex justify-content-between"><span>Total</span><strong>Rp {{ number_format($order->total,0,',','.') }}</strong></div>
                <div class="mt-3">
                  <small class="text-muted">Metode: {{ str_replace('_',' ', $order->payment_method) }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-3">
          <div class="card-body">
            <h5 class="mb-3">Item Pesanan</h5>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th class="text-right" style="width:160px">Harga</th>
                    <th style="width:100px">Qty</th>
                    <th class="text-right" style="width:160px">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->items as $it)
                    <tr>
                      <td>{{ $it->produk->nama_produk ?? ('Produk #'.$it->id_produk) }}</td>
                      <td class="text-right">Rp {{ number_format($it->harga,0,',','.') }}</td>
                      <td>{{ $it->qty }}</td>
                      <td class="text-right">Rp {{ number_format($it->subtotal,0,',','.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <h5 class="mb-3">Aksi Status</h5>
            <div class="d-flex flex-wrap gap-2 mb-2">
              <form method="POST" action="{{ $isKasir ? route('kasir.member-orders.update-status',$order->id_order) : route('admin.member-orders.update-status',$order->id_order) }}">
                @csrf
                <input type="hidden" name="status" value="ready_for_pickup">
                <button class="btn btn-info" {{ $order->status !== 'awaiting_preparation' ? 'disabled' : '' }}>Tandai Siap Diambil</button>
              </form>
              <form method="POST" action="{{ $isKasir ? route('kasir.member-orders.update-status',$order->id_order) : route('admin.member-orders.update-status',$order->id_order) }}">
                @csrf
                <input type="hidden" name="status" value="completed">
                <button class="btn btn-success" {{ !in_array($order->status,['ready_for_pickup']) ? 'disabled' : '' }}>Tandai Selesai</button>
              </form>
              <form method="POST" action="{{ $isKasir ? route('kasir.member-orders.update-status',$order->id_order) : route('admin.member-orders.update-status',$order->id_order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                @csrf
                <input type="hidden" name="status" value="cancelled">
                <button class="btn btn-danger" {{ in_array($order->status,['completed']) ? 'disabled' : '' }}>Batalkan</button>
              </form>
            </div>
            <a class="btn btn-outline-dark" href="{{ $isKasir ? route('kasir.member-orders.print',$order->id_order) : route('admin.member-orders.print',$order->id_order) }}" target="_blank">
              <i class="mdi mdi-printer"></i> Print Struk
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
