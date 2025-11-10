@extends('member.layout')
@section('title','Pesanan Saya')
@push('styles')
<style>
.filter-tabs{display:flex;gap:0.5rem;margin-bottom:2rem;flex-wrap:wrap;border-bottom:2px solid #e8e8e8;padding-bottom:0}
.filter-tab{background:transparent;border:none;padding:1rem 1.5rem;font-weight:600;color:#767676;cursor:pointer;transition:all 0.3s;border-bottom:2px solid transparent;margin-bottom:-2px;position:relative}
.filter-tab:hover{color:#000}
.filter-tab.active{color:#000;border-bottom-color:#000}
.filter-tab .count{display:inline-block;margin-left:0.5rem;background:#e8e8e8;color:#000;padding:0.125rem 0.5rem;border-radius:10px;font-size:0.75rem;font-weight:700}
.filter-tab.active .count{background:#000;color:#fff}
.order-card{background:#fff;border:1px solid #e8e8e8;border-radius:8px;padding:1.5rem;margin-bottom:1rem;transition:all 0.3s}
.order-card:hover{box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.order-header{display:flex;justify-content:space-between;align-items:start;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid #e8e8e8}
.order-number{font-size:1.125rem;font-weight:700}
.order-date{color:#767676;font-size:0.875rem;margin-top:0.25rem}
.order-status{display:inline-block;padding:0.5rem 1rem;border-radius:20px;font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px}
.status-pending{background:#fef3c7;color:#92400e}
.status-paid{background:#dbeafe;color:#1e40af}
.status-awaiting_preparation{background:#dbeafe;color:#1e40af}
.status-ready_for_pickup{background:#e9d5ff;color:#6b21a8}
.status-completed{background:#d1fae5;color:#065f46}
.status-cancelled{background:#fee2e2;color:#991b1b}
.status-debt{background:#fed7aa;color:#9a3412}
.order-items{margin-bottom:1rem}
.order-item{display:flex;gap:1rem;padding:0.75rem 0}
.item-thumb{width:60px;height:60px;border-radius:6px;object-fit:cover;background:#f7f7f7}
.item-info{flex:1}
.item-name{font-weight:600;margin-bottom:0.25rem}
.item-qty{color:#767676;font-size:0.875rem}
.order-footer{display:flex;justify-content:space-between;align-items:center;padding-top:1rem;border-top:1px solid #e8e8e8}
.order-total{font-size:1.125rem;font-weight:700}
.order-actions{display:flex;gap:0.5rem}
.btn-view{background:#000;color:#fff;padding:0.5rem 1.5rem;border:none;border-radius:4px;text-decoration:none;font-size:0.875rem;font-weight:600;text-transform:uppercase;transition:all 0.3s}
.btn-view:hover{background:#333;color:#fff;transform:translateY(-2px);box-shadow:0 4px 8px rgba(0,0,0,0.2)}
.payment-badge{display:inline-block;padding:0.25rem 0.75rem;border-radius:12px;font-size:0.75rem;font-weight:600;margin-left:0.5rem}
.payment-pending{background:#fef3c7;color:#92400e}
.payment-paid{background:#d1fae5;color:#065f46}
.payment-failed{background:#fee2e2;color:#991b1b}
.payment-expired{background:#e5e7eb;color:#374151}
.payment-cancelled{background:#fee2e2;color:#991b1b}
.empty-state{text-align:center;padding:4rem 2rem;color:#767676}
.empty-state i{font-size:5rem;margin-bottom:1rem;opacity:0.3}
.order-list{min-height:400px}
</style>
@endpush
@section('content')
<div class="d-flex align-items-center justify-content-between" style="margin-bottom:2rem">
<h3 style="margin:0">Pesanan Saya</h3>
<a href="{{ route('member.catalog.index') }}" class="btn-view">Belanja Lagi</a>
</div>

<div class="filter-tabs">
  <button class="filter-tab active" data-filter="all">
    Semua <span class="count">{{ $orders->count() }}</span>
  </button>
  <button class="filter-tab" data-filter="pending">
    Belum Bayar <span class="count">{{ $orders->where('payment_status', 'pending')->count() }}</span>
  </button>
  <button class="filter-tab" data-filter="awaiting_preparation">
    Diproses <span class="count">{{ $orders->where('status', 'awaiting_preparation')->count() }}</span>
  </button>
  <button class="filter-tab" data-filter="ready_for_pickup">
    Siap Diambil <span class="count">{{ $orders->where('status', 'ready_for_pickup')->count() }}</span>
  </button>
  <button class="filter-tab" data-filter="completed">
    Selesai <span class="count">{{ $orders->where('status', 'completed')->count() }}</span>
  </button>
  <button class="filter-tab" data-filter="cancelled">
    Dibatalkan <span class="count">{{ $orders->where('status', 'cancelled')->count() }}</span>
  </button>
</div>

<div class="order-list">
@if(($orders ?? collect())->count() === 0)
<div class="empty-state">
<i class="mdi mdi-package-variant-closed"></i>
<h5>Belum Ada Pesanan</h5>
<p>Mulai berbelanja dan pesanan Anda akan muncul di sini</p>
<a href="{{ route('member.catalog.index') }}" class="btn-view" style="margin-top:1rem;display:inline-block">Jelajahi Produk</a>
</div>
@else
@foreach($orders as $o)
<div class="order-card"
     data-status="{{ $o->status }}"
     data-payment="{{ $o->payment_status }}">
<div class="order-header">
<div>
<div class="order-number">{{ $o->order_number ?? '#'.$o->id_order }}</div>
<div class="order-date">{{ $o->created_at->format('d M Y, H:i') }}</div>
@if($o->payment_method === 'midtrans')
<span class="payment-badge payment-{{ $o->payment_status }}">
<i class="mdi mdi-credit-card"></i> {{ ucfirst($o->payment_status) }}
</span>
@endif
</div>
<span class="order-status status-{{ $o->status }}">{{ ucfirst(str_replace('_', ' ', $o->status)) }}</span>
</div>
<div class="order-items">
@php($items = $o->items ?? [])
@if(count($items) > 0)
@foreach($items->take(2) as $item)
<div class="order-item">
@php($img = $item->produk->gambar ?? null)
@if($img)
<img src="{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}" alt="{{ $item->produk->nama_produk ?? '' }}" class="item-thumb">
@else
<div class="item-thumb" style="display:flex;align-items:center;justify-content:center"><i class="mdi mdi-image-outline" style="font-size:1.5rem;color:#ccc"></i></div>
@endif
<div class="item-info">
<div class="item-name">{{ $item->produk->nama_produk ?? 'Produk' }}</div>
<div class="item-qty">{{ $item->qty }}  Rp {{ number_format($item->harga,0,',','.') }}</div>
</div>
</div>
@endforeach
@if(count($items) > 2)
<div style="color:#767676;font-size:0.875rem;padding:0.5rem 0">+{{ count($items) - 2 }} produk lainnya</div>
@endif
@endif
</div>
<div class="order-footer">
<div>
<div style="color:#767676;font-size:0.875rem;margin-bottom:0.25rem">Total Pembayaran</div>
<div class="order-total">Rp {{ number_format($o->total,0,',','.') }}</div>
@if($o->payment_method === 'midtrans' && $o->payment_type)
<div style="color:#767676;font-size:0.75rem;margin-top:0.25rem">via {{ ucwords(str_replace('_', ' ', $o->payment_type)) }}</div>
@elseif($o->payment_method !== 'midtrans')
<div style="color:#767676;font-size:0.75rem;margin-top:0.25rem">Bayar di Outlet</div>
@endif
</div>
<div class="order-actions">
<a href="{{ route('member.orders.show',$o->id_order) }}" class="btn-view">Lihat Detail</a>
</div>
</div>
</div>
@endforeach
</div>

<div class="empty-state" id="emptyFilter" style="display:none">
  <i class="mdi mdi-filter-outline"></i>
  <h5>Tidak Ada Pesanan</h5>
  <p>Tidak ada pesanan dengan filter yang dipilih</p>
</div>

@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const filterTabs = document.querySelectorAll('.filter-tab');
  const orderCards = document.querySelectorAll('.order-card');
  const emptyFilter = document.getElementById('emptyFilter');
  const orderList = document.querySelector('.order-list');

  filterTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      const filter = this.dataset.filter;

      // Update active tab
      filterTabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      // Filter orders
      let visibleCount = 0;
      orderCards.forEach(card => {
        const status = card.dataset.status;
        const payment = card.dataset.payment;
        let show = false;

        if (filter === 'all') {
          show = true;
        } else if (filter === 'pending') {
          show = payment === 'pending';
        } else if (filter === 'cancelled') {
          show = status === 'cancelled';
        } else {
          show = status === filter;
        }

        if (show) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      // Show/hide empty state
      if (visibleCount === 0) {
        emptyFilter.style.display = 'block';
        orderList.style.minHeight = '0';
      } else {
        emptyFilter.style.display = 'none';
        orderList.style.minHeight = '400px';
      }
    });
  });
});
</script>
@endpush
