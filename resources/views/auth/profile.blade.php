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