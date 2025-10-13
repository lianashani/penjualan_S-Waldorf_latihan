@extends('member.layout')
@section('title','Member Dashboard')
@push('styles')
    <style>
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0 16px;
            border-bottom: 1px solid #e5e5e5;
            margin-bottom: 20px;
        }
        .card-plain {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 20px;
        }
        .card-invert {
            background: #0a0a0a;
            color: #ffffff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #111111;
            text-align: center;
        }
        .points-value {
            font-size: 44px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .badge-status {
            background: #111111;
            color: #fff;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .feature {
            text-align: center;
        }
        .feature .icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #0a0a0a;
            color: #fff;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="topbar">
        <h4 class="mb-0">Member Dashboard</h4>
        <div class="text-muted small">{{ $member->email }}</div>
    </div>

    <div class="card-plain mb-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h3 class="mb-1">Selamat Datang, {{ $member->nama_member }}!</h3>
                <div class="text-muted">No. HP: {{ $member->no_hp }}</div>
            </div>
            <span class="badge-status">{{ ucfirst($member->status) }}</span>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card-invert h-100">
                <i class="mdi mdi-star-circle" style="font-size: 48px;"></i>
                <h5 class="mt-2 mb-1">Poin Anda</h5>
                <div class="points-value">{{ number_format($member->points) }}</div>
                <div class="mt-1">Setara Rp {{ number_format($member->getPointsValue(), 0, ',', '.') }}</div>
                <small class="text-muted" style="color:#bbb !important;">100 poin = Rp 10.000</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-plain h-100 text-center">
                <div class="icon mb-2" style="display:inline-flex;width:56px;height:56px;align-items:center;justify-content:center;border-radius:12px;background:#f5f5f5;color:#111;">
                    <i class="mdi mdi-cash-multiple" style="font-size: 28px;"></i>
                </div>
                <h5 class="mt-1">Total Belanja</h5>
                <h2 class="mb-0">Rp {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <div class="card-plain mt-3">
        <h5 class="mb-3"><i class="mdi mdi-information-outline"></i> Informasi Member</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <div><strong>Member Sejak:</strong> {{ $member->created_at->format('d F Y') }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Alamat:</strong> {{ $member->alamat ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-4">
            <div class="card-plain feature h-100">
                <div class="icon"><i class="mdi mdi-store"></i></div>
                <h6><a href="{{ route('member.catalog.index') }}" class="text-decoration-none text-dark">Katalog</a></h6>
                <p class="text-muted mb-0">Lihat dan belanja produk</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-plain feature h-100">
                <div class="icon"><i class="mdi mdi-cart"></i></div>
                <h6><a href="{{ route('member.cart.index') }}" class="text-decoration-none text-dark">Keranjang</a></h6>
                <p class="text-muted mb-0">Kelola barang belanjaan</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-plain feature h-100">
                <div class="icon"><i class="mdi mdi-history"></i></div>
                <h6><a href="{{ route('member.orders') }}" class="text-decoration-none text-dark">Pesanan</a></h6>
                <p class="text-muted mb-0">Lacak status pesanan Anda</p>
            </div>
        </div>
    </div>

    @if(!empty($lastOrder))
    <div class="card-plain mt-3">
        <h5 class="mb-3"><i class="mdi mdi-reorder-horizontal"></i> Pesanan Terakhir</h5>
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div>
                    <div class="text-muted small">Order</div>
                    <div><strong>{{ $lastOrder->order_number ?? ('#'.$lastOrder->id_order) }}</strong></div>
                    <div class="small text-muted">{{ $lastOrder->created_at->format('d M Y H:i') }}</div>
                </div>
                <div>
                    <div class="text-muted small">Status</div>
                    <div class="badge-status text-capitalize">{{ str_replace('_',' ', $lastOrder->status) }}</div>
                </div>
                <div>
                    <div class="text-muted small">Total</div>
                    <div><strong>Rp {{ number_format($lastOrder->total,0,',','.') }}</strong></div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                <a href="{{ route('member.orders.show', $lastOrder->id_order) }}" class="btn btn-outline-dark"><i class="mdi mdi-eye"></i> Detail</a>
                <a href="{{ route('member.orders.receipt', $lastOrder->id_order) }}" target="_blank" class="btn btn-dark"><i class="mdi mdi-printer"></i> Struk</a>
            </div>
        </div>
        @if(($lastOrder->items ?? collect())->count())
        <div class="table-responsive mt-3">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width:64px"></th>
                        <th>Produk</th>
                        <th class="text-end" style="width:140px">Harga</th>
                        <th style="width:80px">Qty</th>
                        <th class="text-end" style="width:140px">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lastOrder->items as $it)
                    <tr>
                        <td>
                            @php($img = $it->produk->gambar ?? null)
                            @if($img)
                                <img src="{{ \Illuminate\Support\Str::startsWith($img, ['http://','https://','/']) ? $img : asset('storage/'.$img) }}" alt="{{ $it->produk->nama_produk ?? ('Produk #'.$it->id_produk) }}" style="width:48px;height:48px;border-radius:8px;object-fit:cover;background:#f5f5f5">
                            @else
                                <div style="width:48px;height:48px;border-radius:8px;background:#f5f5f5" class="d-flex align-items-center justify-content-center"><i class="mdi mdi-image-outline"></i></div>
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
        @endif
    </div>
    @endif
@endsection
