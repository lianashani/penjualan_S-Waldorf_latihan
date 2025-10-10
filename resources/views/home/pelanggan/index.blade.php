@extends('layouts.master')
@section('title', 'Data Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Data Pelanggan</h4>
                        <h6 class="card-subtitle">Kelola data pelanggan S&Waldorf</h6>
                    </div>
                    <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Pelanggan
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tablePelanggan" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Pelanggan</th>
                                <th>Email</th>
                                <th>Membership</th>
                                <th>Status</th>
                                <th>Tanggal Daftar</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggans as $pelanggan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $pelanggan->nama_pelanggan }}</strong></td>
                                <td>{{ $pelanggan->email }}</td>
                                <td>
                                    @if($pelanggan->membership)
                                        <span class="badge badge-info">{{ $pelanggan->membership->nama_membership ?? '-' }}</span>
                                    @else
                                        <span class="badge badge-secondary">Regular</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pelanggan->status == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $pelanggan->tanggal_daftar->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('pelanggan.edit', $pelanggan->id_pelanggan) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pelanggan.destroy', $pelanggan->id_pelanggan) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin hapus pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pelanggan</td>
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
    $('#tablePelanggan').DataTable({
        "pageLength": 10,
        "ordering": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush