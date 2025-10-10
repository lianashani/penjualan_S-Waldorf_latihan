@extends('layouts.master')
@section('title', 'Edit Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Edit Produk</h4>
                <h6 class="card-subtitle mb-4">Ubah data produk</h6>

                <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                                       name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                                @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori <span class="text-danger">*</span></label>
                                <select class="form-control @error('id_kategori') is-invalid @enderror" 
                                        name="id_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id_kategori }}" 
                                                {{ old('id_kategori', $produk->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ukuran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ukuran') is-invalid @enderror" 
                                       name="ukuran" value="{{ old('ukuran', $produk->ukuran) }}" required>
                                @error('ukuran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Warna <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('warna') is-invalid @enderror" 
                                       name="warna" value="{{ old('warna', $produk->warna) }}" required>
                                @error('warna')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                       name="stok" value="{{ old('stok', $produk->stok) }}" min="0" required>
                                @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                       name="harga" value="{{ old('harga', $produk->harga) }}" min="0" step="0.01" required>
                                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                       name="gambar" accept="image/*">
                                @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                                @if($produk->gambar)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $produk->gambar) }}" 
                                             alt="Current" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          name="deskripsi" rows="4">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Update
                        </button>
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
