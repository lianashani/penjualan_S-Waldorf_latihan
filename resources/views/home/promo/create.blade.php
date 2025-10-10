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