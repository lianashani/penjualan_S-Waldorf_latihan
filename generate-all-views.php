<?php
/**
 * Script untuk generate SEMUA CRUD views sekaligus
 * Jalankan: php generate-all-views.php
 */

echo "🚀 Generating all CRUD views...\n\n";

$basePath = __DIR__ . '/resources/views/home';

// ========== PELANGGAN VIEWS ==========
$pelangganIndex = <<<'BLADE'
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
BLADE;

$pelangganCreate = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Tambah Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Pelanggan</h4>
                <h6 class="card-subtitle mb-4">Isi form untuk menambah pelanggan baru</h6>

                <form action="{{ route('pelanggan.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                               name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required>
                        @error('nama_pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Kosongkan jika tidak ingin set password</small>
                    </div>

                    <div class="form-group">
                        <label>Membership</label>
                        <select class="form-control @error('id_membership') is-invalid @enderror" name="id_membership">
                            <option value="">Tanpa Membership</option>
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id_membership }}">{{ $membership->nama_membership }}</option>
                            @endforeach
                        </select>
                        @error('id_membership')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

$pelangganEdit = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Edit Pelanggan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Pelanggan</h4>
                <h6 class="card-subtitle mb-4">Ubah data pelanggan</h6>

                <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                               name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                        @error('nama_pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $pelanggan->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>

                    <div class="form-group">
                        <label>Membership</label>
                        <select class="form-control @error('id_membership') is-invalid @enderror" name="id_membership">
                            <option value="">Tanpa Membership</option>
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id_membership }}" 
                                        {{ old('id_membership', $pelanggan->id_membership) == $membership->id_membership ? 'selected' : '' }}>
                                    {{ $membership->nama_membership }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_membership')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="aktif" {{ old('status', $pelanggan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $pelanggan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

// Write Pelanggan files
file_put_contents($basePath . '/pelanggan/index.blade.php', $pelangganIndex);
file_put_contents($basePath . '/pelanggan/create.blade.php', $pelangganCreate);
file_put_contents($basePath . '/pelanggan/edit.blade.php', $pelangganEdit);

echo "✓ Pelanggan views created (index, create, edit)\n";

// ========== PROMO VIEWS ==========
$promoIndex = <<<'BLADE'
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
BLADE;

$promoCreate = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Tambah Promo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('promo.index') }}">Promo</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Promo</h4>
                <h6 class="card-subtitle mb-4">Isi form untuk menambah promo baru</h6>

                <form action="{{ route('promo.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Kode Promo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_promo') is-invalid @enderror" 
                               name="kode_promo" value="{{ old('kode_promo') }}" 
                               placeholder="Contoh: GRAND10" required>
                        @error('kode_promo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Persentase Diskon (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('persen') is-invalid @enderror" 
                               name="persen" value="{{ old('persen') }}" 
                               min="0" max="100" step="0.01" required>
                        @error('persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Maksimal 100%</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('promo.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

$promoEdit = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Edit Promo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('promo.index') }}">Promo</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Promo</h4>
                <h6 class="card-subtitle mb-4">Ubah data promo</h6>

                <form action="{{ route('promo.update', $promo->id_promo) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Kode Promo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('kode_promo') is-invalid @enderror" 
                               name="kode_promo" value="{{ old('kode_promo', $promo->kode_promo) }}" required>
                        @error('kode_promo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Persentase Diskon (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('persen') is-invalid @enderror" 
                               name="persen" value="{{ old('persen', $promo->persen) }}" 
                               min="0" max="100" step="0.01" required>
                        @error('persen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       name="tanggal_mulai" value="{{ old('tanggal_mulai', $promo->tanggal_mulai->format('Y-m-d')) }}" required>
                                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       name="tanggal_selesai" value="{{ old('tanggal_selesai', $promo->tanggal_selesai->format('Y-m-d')) }}" required>
                                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                            <option value="aktif" {{ old('status', $promo->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $promo->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                        <a href="{{ route('promo.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

// Write Promo files
file_put_contents($basePath . '/promo/index.blade.php', $promoIndex);
file_put_contents($basePath . '/promo/create.blade.php', $promoCreate);
file_put_contents($basePath . '/promo/edit.blade.php', $promoEdit);

echo "✓ Promo views created (index, create, edit)\n";

echo "\n✅ All CRUD views generated successfully!\n";
echo "\nFiles created:\n";
echo "- Pelanggan: index, create, edit\n";
echo "- Promo: index, create, edit\n";
echo "\nYou can now access all CRUD features!\n";
