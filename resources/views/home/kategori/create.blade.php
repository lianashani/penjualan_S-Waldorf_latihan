@extends('layouts.master')
@section('title', 'Tambah Kategori')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Kategori</h4>
                <h6 class="card-subtitle mb-4">Isi form untuk menambah kategori baru</h6>

                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_kategori') is-invalid @enderror" 
                               id="nama_kategori" 
                               name="nama_kategori" 
                               value="{{ old('nama_kategori') }}"
                               placeholder="Contoh: Pakaian Wanita"
                               required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Masukkan nama kategori produk</small>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
