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
                        <label>Nama Lengkap <span class="text-danger"></span></label>
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
