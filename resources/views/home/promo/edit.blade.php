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