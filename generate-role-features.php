<?php
/**
 * Script untuk generate semua fitur role-based
 * Jalankan: php generate-role-features.php
 */

echo "🚀 Generating role-based features...\n\n";

$basePath = __DIR__ . '/resources/views';

// ========== AUTH VIEWS ==========

// Login View (sudah ada, skip)

// Change Password View
$changePasswordView = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Ubah Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ubah Password</h4>
                @if(auth()->user()->must_change_password)
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert"></i> Anda harus mengubah password default terlebih dahulu!
                    </div>
                @endif

                <form action="{{ route('change-password.update') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Password Lama <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               name="current_password" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                               name="new_password" required>
                        @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Minimal 6 karakter</small>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               name="new_password_confirmation" required>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

// Profile View
$profileView = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Profil Saya')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Profil</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Profil Saya</h4>
                <h6 class="card-subtitle mb-4">Informasi akun Anda</h6>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_user') is-invalid @enderror" 
                               name="nama_user" value="{{ old('nama_user', auth()->user()->nama_user) }}" required>
                        @error('nama_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->role) }}" disabled>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update Profil
                        </button>
                        <a href="{{ route('change-password') }}" class="btn btn-warning">
                            <i class="mdi mdi-lock-reset"></i> Ubah Password
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;

// Write auth views
if (!is_dir($basePath . '/auth')) {
    mkdir($basePath . '/auth', 0755, true);
}
file_put_contents($basePath . '/auth/change-password.blade.php', $changePasswordView);
file_put_contents($basePath . '/auth/profile.blade.php', $profileView);

echo "✓ Auth views created (change-password, profile)\n";

// ========== USER CRUD (ADMIN ONLY) ==========
if (!is_dir($basePath . '/home/user')) {
    mkdir($basePath . '/home/user', 0755, true);
}

$userIndex = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Data User')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Data User</h4>
                        <h6 class="card-subtitle">Kelola user sistem</h6>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah User
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tableUser" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status Password</th>
                                <th>Tanggal Dibuat</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $user->nama_user }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge badge-danger">Admin</span>
                                    @else
                                        <span class="badge badge-info">Kasir</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->must_change_password)
                                        <span class="badge badge-warning">Harus Ganti</span>
                                    @else
                                        <span class="badge badge-success">OK</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('user.edit', $user->id_user) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    @if($user->id_user != auth()->id())
                                    <form action="{{ route('user.destroy', $user->id_user) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data user</td>
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
    $('#tableUser').DataTable({
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

$userCreate = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Tambah User')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah User</h4>
                <h6 class="card-subtitle mb-4">User baru akan menggunakan password default: <strong>admin123</strong></h6>

                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_user') is-invalid @enderror" 
                               name="nama_user" value="{{ old('nama_user') }}" required>
                        @error('nama_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">
                            <strong>Admin:</strong> Kelola data master<br>
                            <strong>Kasir:</strong> Transaksi penjualan
                        </small>
                    </div>

                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i> 
                        User baru akan diminta mengubah password saat login pertama kali.
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
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

$userEdit = <<<'BLADE'
@extends('layouts.master')
@section('title', 'Edit User')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit User</h4>
                <h6 class="card-subtitle mb-4">Ubah data user</h6>

                <form action="{{ route('user.update', $user->id_user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_user') is-invalid @enderror" 
                               name="nama_user" value="{{ old('nama_user', $user->nama_user) }}" required>
                        @error('nama_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="reset_password" value="1"> 
                            Reset password ke default (admin123)
                        </label>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">
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

file_put_contents($basePath . '/home/user/index.blade.php', $userIndex);
file_put_contents($basePath . '/home/user/create.blade.php', $userCreate);
file_put_contents($basePath . '/home/user/edit.blade.php', $userEdit);

echo "✓ User CRUD views created (index, create, edit)\n";

echo "\n✅ All role-based views generated successfully!\n";
echo "\nNext steps:\n";
echo "1. Create UserController\n";
echo "2. Create AuthController for profile & change password\n";
echo "3. Update routes with middleware\n";
echo "4. Update sidebar based on roles\n";
echo "5. Create print receipt & report features\n";
