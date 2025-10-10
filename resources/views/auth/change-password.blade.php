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