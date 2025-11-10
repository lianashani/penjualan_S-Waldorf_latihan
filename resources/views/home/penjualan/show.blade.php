@extends('layouts.master')
@section('title', 'Detail Transaksi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title">Detail Transaksi #{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</h4>
                        <h6 class="card-subtitle">{{ $penjualan->tanggal_transaksi->format('d F Y, H:i') }} WIB</h6>
                    </div>
                    <div>
                        @if ($penjualan->status_transaksi == 'selesai')
                            <span class="badge badge-success badge-lg">Selesai</span>
                        @elseif ($penjualan->status_transaksi == 'pending')
                            <span class="badge badge-warning badge-lg">Pending</span>
                        @else
                            <span class="badge badge-danger badge-lg">Batal</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <!-- Transaction Info -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">Informasi Transaksi</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>ID Transaksi:</strong></td>
                                        <td>#{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kasir:</strong></td>
                                        <td>{{ $penjualan->user->nama_user ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pelanggan:</strong></td>
                                        <td>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</td>
                                    </tr>
                                    @if($penjualan->pelanggan)
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $penjualan->pelanggan->email }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Tanggal:</strong></td>
                                        <td>{{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Discount Info -->
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Informasi Diskon</h5>
                            </div>
                            <div class="card-body">
                                @if($penjualan->promo)
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>Kode Promo:</strong></td>
                                        <td><span class="badge badge-success">{{ $penjualan->promo->kode_promo }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Persentase:</strong></td>
                                        <td><strong class="text-danger">{{ $penjualan->promo->persen }}%</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nilai Diskon:</strong></td>
                                        <td><strong class="text-danger">Rp. {{ number_format($diskon, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Periode Promo:</strong></td>
                                        <td>{{ $penjualan->promo->tanggal_mulai->format('d/m/Y') }} - {{ $penjualan->promo->tanggal_selesai->format('d/m/Y') }}</td>
                                    </tr>
                                </table>
                                @else
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information"></i> Transaksi ini tidak menggunakan promo/diskon
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="card border mt-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Detail Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Varian</th>
                                        <th>Kategori</th>
                                        <th>Harga Satuan</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualan->detailPenjualans as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                                        <td>
                                            @if($detail->ukuran || $detail->warna)
                                                Ukuran: {{ $detail->ukuran ?? '-' }}, Warna: {{ $detail->warna ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $detail->produk->kategori->nama_kategori ?? '-' }}</td>
                                        <td>Rp. {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                        <td>{{ $detail->qty }}</td>
                                        <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="card border mt-3">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Ringkasan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Subtotal:</strong></td>
                                        <td class="text-right"><h5>Rp. {{ number_format($subtotal, 0, ',', '.') }}</h5></td>
                                    </tr>
                                    @if($diskon > 0)
                                    <tr>
                                        <td><strong>Diskon ({{ $penjualan->promo->persen }}%):</strong></td>
                                        <td class="text-right"><h5 class="text-danger">- Rp. {{ number_format($diskon, 0, ',', '.') }}</h5></td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td><strong>Total:</strong></td>
                                        <td class="text-right"><h4 class="text-success">Rp. {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</h4></td>
                                    </tr>
                                    @if($penjualan->payment_method === 'cash')
                                    <tr>
                                        <td><strong>Bayar:</strong></td>
                                        <td class="text-right"><h5>Rp. {{ number_format($penjualan->total_bayar + $penjualan->kembalian, 0, ',', '.') }}</h5></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kembalian:</strong></td>
                                        <td class="text-right"><h5>Rp. {{ number_format($penjualan->kembalian, 0, ',', '.') }}</h5></td>
                                    </tr>
                                    @endif
                                </table>

                                @if($diskon > 0)
                                <div class="alert alert-success">
                                    <i class="mdi mdi-check-circle"></i>
                                    <strong>Hemat Rp. {{ number_format($diskon, 0, ',', '.') }}</strong> dengan promo {{ $penjualan->promo->kode_promo }}!
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('penjualan.print', $penjualan->id_penjualan) }}" class="btn btn-primary" target="_blank">
                        <i class="mdi mdi-printer"></i> Cetak Struk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    /* Hide all non-essential elements */
    .sidebar,
    .left-sidebar,
    .topbar,
    .navbar,
    .page-breadcrumb,
    .footer,
    .btn,
    .no-print,
    .card-header,
    body > *:not(.page-wrapper),
    .page-wrapper > *:not(.page-content),
    .page-content > *:not(.container-fluid),
    nav,
    .breadcrumb {
        display: none !important;
    }

    /* Reset page wrapper and content */
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }

    .page-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }

    .page-content {
        margin: 0 !important;
        padding: 0 !important;
    }

    .container-fluid {
        margin: 0 !important;
        padding: 15px !important;
        max-width: 100% !important;
    }

    .row {
        margin: 0 !important;
    }

    .col-12 {
        padding: 0 !important;
    }

    /* Clean up card */
    .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
    }

    .card-body {
        padding: 10px !important;
    }

    /* Make receipt look clean */
    table {
        page-break-inside: avoid;
    }

    /* Show only printable content */
    .printable-area {
        display: block !important;
        margin: 0 auto;
        max-width: 80mm; /* Thermal printer width */
        font-size: 12px;
    }
}

/* Add printable area class wrapper */
.print-content {
    padding: 20px;
}
</style>
@endpush
