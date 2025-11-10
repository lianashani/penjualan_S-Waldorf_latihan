@extends('layouts.master')
@section('title','Antrian Order Member')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
<li class="breadcrumb-item active" aria-current="page">Antrian Order Member</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h4 class="card-title">Antrian Order Member</h4>
            <h6 class="card-subtitle">Kelola pesanan bayar di outlet</h6>
          </div>
        </div>

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Order</th>
                <th>Member</th>
                <th>Metode Bayar</th>
                <th>Status Bayar</th>
                <th>Status</th>
                <th class="text-right">Total</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                <tr>
                  <td>{{ $loop->iteration + ($orders->currentPage()-1)*$orders->perPage() }}</td>
                  <td><code>{{ $o->order_number ?? ('#'.$o->id_order) }}</code></td>
                  <td>{{ $o->member->nama_member ?? '-' }}<br><small class="text-muted">{{ $o->member->email ?? '' }}</small></td>
                  <td>
                    @if($o->payment_method === 'midtrans')
                      <span class="badge badge-primary">Midtrans</span>
                      @if($o->payment_type)
                        <br><small class="text-muted">{{ ucwords(str_replace('_', ' ', $o->payment_type)) }}</small>
                      @endif
                    @else
                      <span class="text-capitalize">{{ str_replace('_', ' ', $o->payment_method) }}</span>
                    @endif
                  </td>
                  <td>
                    @if($o->payment_method === 'midtrans')
                      @php($paymentMap=[
                        'pending'=>'badge-warning',
                        'paid'=>'badge-success',
                        'failed'=>'badge-danger',
                        'expired'=>'badge-secondary',
                      ])
                      <span class="badge {{ $paymentMap[$o->payment_status ?? 'pending'] ?? 'badge-secondary' }}">
                        {{ ucfirst($o->payment_status ?? 'pending') }}
                      </span>
                      @if($o->paid_at)
                        <br><small class="text-muted">{{ $o->paid_at->format('d/m H:i') }}</small>
                      @endif
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @php($map=[
                      'pending'=>'badge-secondary',
                      'awaiting_preparation'=>'badge-warning',
                      'ready_for_pickup'=>'badge-info',
                      'completed'=>'badge-success',
                      'cancelled'=>'badge-danger',
                    ])
                    <span class="badge {{ $map[$o->status] ?? 'badge-secondary' }}">{{ str_replace('_',' ', ucfirst($o->status)) }}</span>
                  </td>
                  <td class="text-right">Rp {{ number_format($o->total,0,',','.') }}</td>
                  <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                  <td>
                    @php($isKasir = (auth()->check() && method_exists(auth()->user(),'getAttribute') && (auth()->user()->role ?? null) === 'kasir'))
                    <a href="{{ $isKasir ? route('kasir.member-orders.show', $o->id_order) : route('admin.member-orders.show', $o->id_order) }}" class="btn btn-sm btn-primary">Detail</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center">Belum ada pesanan</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-2">{{ $orders->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
