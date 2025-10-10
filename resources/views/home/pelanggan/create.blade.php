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