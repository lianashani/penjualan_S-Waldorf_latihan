@extends('layouts.master')
@section('title', 'Data Promo')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Promo</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Data Promo Diskon</h4>
                        <h6 class="card-subtitle">Kelola promo diskon S&Waldorf</h6>
                    </div>
                    <a href="{{ route('promo.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Promo
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tablePromo" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Kode Promo</th>
                                <th>Diskon (%)</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $promo)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $promo->kode_promo }}</strong></td>
                                <td><span class="badge badge-success">{{ $promo->persen }}%</span></td>
                                <td>
                                    {{ $promo->tanggal_mulai->format('d/m/Y') }} - 
                                    {{ $promo->tanggal_selesai->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($promo->status == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('promo.edit', $promo->id_promo) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('promo.destroy', $promo->id_promo) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin hapus promo ini?')">
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
                                <td colspan="6" class="text-center">Belum ada data promo</td>
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
    $('#tablePromo').DataTable({
        "pageLength": 10,
        "ordering": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush