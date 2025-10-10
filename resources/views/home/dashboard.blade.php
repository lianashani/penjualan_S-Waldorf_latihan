@extends('layouts.master')
@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')

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
                            {{-- <h1 class="font-light text-right">{{ $userCount }}</h1> --}}
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
                            {{-- <h1 class="font-light text-right">{{ $jenisCount }}</h1> --}}
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
                            {{-- <h1 class="font-light text-right">{{ $produkCount }}</h1> --}}
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
                            {{-- <h1 class="font-light text-right">{{ $penjualanCount }}</h1> --}}
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
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        {{-- </thead>
                        <tbody>
                            @foreach($produkList as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td>
                                    @if ($produk->stok <= 0)
                                        <span class="label label-danger">Kosong</span>
                                    @elseif ($produk->stok <= 50)
                                        <span class="label label-warning">Sedikit</span>
                                    @else
                                        <span class="label label-success">Ada</span>
                                    @endif
                                </td>
                            </tr> --}}
                            {{-- @endforeach --}}
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
                                <th>Atas Nama</th>
                                <th>Total Harga</th>
                                <th>Bayar</th>
                                <th>Kembalian</th>
                                <th>Opsi Makanan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach($penjualanList as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->user->name }}</td>
                                <td>{{ $penjualan->atas_nama }}</td>
                                <td>Rp. {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($penjualan->kembali, 0, ',', '.') }}</td>
                                <td>{{ $penjualan->opsi_makanan }}</td>
                                <td>{{ $penjualan->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if ($penjualan->total_harga == 0)
                                        <span class="label label-danger">Belum Selesai</span>
                                    @else
                                        <span class="label label-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody> --}}
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
