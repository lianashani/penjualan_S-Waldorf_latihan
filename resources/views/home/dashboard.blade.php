@extends('layouts.master')
@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')

<h1 style="color: black !important;">Dashboard S&Waldorf</h1>
<p style="color: black !important;">Selamat datang di sistem manajemen S&Waldorf</p>

<!-- Statistics Cards Start -->
<div class="card-group">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <i class="mdi mdi-account-multiple font-20 text-muted"></i>
                            <p class="font-16 m-b-5">Jumlah User</p>
                        </div>
                        <div class="ml-auto">
                            <h1 class="font-light text-right">{{ $userCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 75%; height: 6px;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <i class="mdi mdi-shape font-20 text-muted"></i>
                            <p class="font-16 m-b-5">Jumlah Jenis</p>
                        </div>
                        <div class="ml-auto">
                            <h1 class="font-light text-right">{{ $jenisCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 60%; height: 6px;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <i class="mdi mdi-package-variant font-20 text-muted"></i>
                            <p class="font-16 m-b-5">Jumlah Produk</p>
                        </div>
                        <div class="ml-auto">
                            <h1 class="font-light text-right">{{ $produkCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-purple" role="progressbar" style="width: 65%; height: 6px;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <i class="mdi mdi-cart font-20 text-muted"></i>
                            <p class="font-16 m-b-5">Jumlah Transaksi</p>
                        </div>
                        <div class="ml-auto">
                            <h1 class="font-light text-right">{{ $penjualanCount }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 70%; height: 6px;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Statistics Cards End -->

<!-- Additional KPI Cards -->
<div class="row">
    <div class="col-md-6">
        <div class="card" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="m-r-20">
                        <i class="mdi mdi-cash-multiple font-40"></i>
                    </div>
                    <div>
                        <h3 class="m-b-0">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <span>Total Pendapatan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="background: linear-gradient(135deg, #dc2626, #f87171); color: white;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="m-r-20">
                        <i class="mdi mdi-alert-circle font-40"></i>
                    </div>
                    <div>
                        <h3 class="m-b-0">{{ $lowStockCount }}</h3>
                        <span>Produk Stok Rendah (≤10)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Stok Barang Start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Status Stok Barang</h4>
                <h6 class="card-subtitle">Monitoring stok produk yang tersedia</h6>
                <div class="table-responsive">
                    <table id="table1" class="table table-striped table-bordered display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produkList as $produk)
                            @php
                                $actualStok = $produk->has_variants ? $produk->total_stok : $produk->stok;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ $actualStok }}</td>
                                <td>
                                    @if ($actualStok <= 0)
                                        <span class="label label-danger">Kosong</span>
                                    @elseif ($actualStok <= 10)
                                        <span class="label label-warning">Stok Rendah</span>
                                    @elseif ($actualStok <= 50)
                                        <span class="label label-info">Stok Sedang</span>
                                    @else
                                        <span class="label label-success">Stok Aman</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data produk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Status Stok Barang End -->

<!-- Riwayat Penjualan Start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Riwayat Penjualan</h4>
                <h6 class="card-subtitle">Daftar transaksi penjualan terbaru</h6>
                <div class="table-responsive">
                    <table id="table3" class="table table-striped table-bordered display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Pelanggan</th>
                                <th>Total Bayar</th>
                                <th>Kembalian</th>
                                <th>Promo</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualanList as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->user->nama_user ?? '-' }}</td>
                                <td>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</td>
                                <td>Rp. {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                                <td>
                                    @if($penjualan->promo)
                                        <span class="badge badge-success">{{ $penjualan->promo->kode_promo }} ({{ $penjualan->promo->persen }}%)</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($penjualan->status_transaksi == 'selesai')
                                        <span class="label label-success">Selesai</span>
                                    @elseif ($penjualan->status_transaksi == 'pending')
                                        <span class="label label-warning">Pending</span>
                                    @else
                                        <span class="label label-danger">Batal</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Riwayat Penjualan End -->

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables untuk tabel stok barang
        $('#table1').DataTable({
            "pageLength": 10,
            "ordering": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Inisialisasi DataTables untuk tabel riwayat penjualan
        $('#table3').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [[7, "desc"]], // Urutkan berdasarkan tanggal terbaru
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush
