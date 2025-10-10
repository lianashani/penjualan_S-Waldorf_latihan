@extends('layouts.master')
@section('title', 'Data Kategori')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Kategori</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Data Kategori Produk</h4>
                        <h6 class="card-subtitle">Kelola kategori produk S&Waldorf</h6>
                    </div>
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Kategori
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tableKategori" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Produk</th>
                                <th>Tanggal Dibuat</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $kategori)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                                <td>
                                    <span class="badge badge-info">{{ $kategori->produks_count ?? 0 }} produk</span>
                                </td>
                                <td>{{ $kategori->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('kategori.edit', $kategori->id_kategori) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin hapus kategori ini?')">
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
                                <td colspan="5" class="text-center">Belum ada data kategori</td>
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
    $('#tableKategori').DataTable({
        "pageLength": 10,
        "ordering": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush