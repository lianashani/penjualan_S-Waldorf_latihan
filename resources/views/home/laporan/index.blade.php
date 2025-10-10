@extends('layouts.master')
@section('title', 'Laporan Penjualan')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Laporan Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Laporan Penjualan</h4>
                <h6 class="card-subtitle mb-4">Filter dan cetak laporan penjualan</h6>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="{{ request('start_date', date('Y-m-01')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control" 
                                       value="{{ request('end_date', date('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label><br>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-filter"></i> Filter
                                </button>
                                <a href="{{ route('laporan.print', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                                   class="btn btn-success" target="_blank">
                                    <i class="mdi mdi-printer"></i> Cetak
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $totalTransaksi }}</h3>
                                <p class="mb-0">Total Transaksi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h3 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                                <p class="mb-0">Total Pendapatan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="tableLaporan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kasir</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Promo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualans as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                <td>{{ $penjualan->user->nama_user ?? '-' }}</td>
                                <td>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</td>
                                <td>Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    @if($penjualan->promo)
                                        {{ $penjualan->promo->kode_promo }} ({{ $penjualan->promo->persen }}%)
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $penjualan->status_transaksi == 'selesai' ? 'success' : 'warning' }}">
                                        {{ ucfirst($penjualan->status_transaksi) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tableLaporan').DataTable({
        "pageLength": 25,
        "ordering": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
