@extends('layouts.master')
@section('title', 'Daftar Transaksi Penjualan')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Daftar Transaksi Penjualan</h4>
                        <h6 class="card-subtitle">Riwayat semua transaksi penjualan S&Waldorf</h6>
                    </div>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Transaksi Baru
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tablePenjualan" class="table table-striped table-bordered display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>User</th>
                                <th>Pelanggan</th>
                                <th>Total Bayar</th>
                                <th>Kembalian</th>
                                <th>Promo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualans as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>#{{ str_pad($penjualan->id_penjualan, 5, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $penjualan->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                <td>{{ $penjualan->user->nama_user ?? '-' }}</td>
                                <td>{{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</td>
                                <td>Rp. {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
                                <td>
                                    @if($penjualan->promo)
                                        <span class="badge badge-success">
                                            {{ $penjualan->promo->kode_promo }}<br>
                                            ({{ $penjualan->promo->persen }}%)
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($penjualan->status_transaksi == 'selesai')
                                        <span class="label label-success">Selesai</span>
                                    @elseif ($penjualan->status_transaksi == 'pending')
                                        <span class="label label-warning">Pending</span>
                                    @else
                                        <span class="label label-danger">Batal</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('penjualan.show', $penjualan->id_penjualan) }}" 
                                       class="btn btn-sm btn-info" title="Detail">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $penjualans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tablePenjualan').DataTable({
        "pageLength": 10,
        "ordering": true,
        "order": [[2, "desc"]], // Sort by date descending
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
