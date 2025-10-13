@extends('member.layout')
@section('title','Profil Member')
@push('styles')
<style>
    .container { max-width: 900px }
    .card-plain { background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:20px }
    .btn-dark { background:#111; border-color:#111 }
</style>
@endpush

@section('content')
<div class="py-2">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Profil Member</h4>
        <a href="{{ route('member.dashboard') }}" class="btn btn-sm btn-outline-dark"><i class="mdi mdi-arrow-left"></i> Dashboard</a>
    </div>

    <div class="card-plain mb-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div><strong>Nama</strong><br>{{ $member->nama_member }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Email</strong><br>{{ $member->email }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>No. HP</strong><br>{{ $member->no_hp }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Status</strong><br><span class="badge bg-dark">{{ ucfirst($member->status) }}</span></div>
            </div>
            <div class="col-12">
                <div><strong>Alamat</strong><br>{{ $member->alamat ?? '-' }}</div>
            </div>
        </div>
    </div>

    <form class="card-plain">
        <h6 class="mb-3">Ubah Kontak</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" class="form-control" value="{{ $member->no_hp }}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label">Alamat</label>
                <input type="text" class="form-control" value="{{ $member->alamat }}" disabled>
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-dark" disabled>Simpan (coming soon)</button>
        </div>
    </form>
</div>
@endsection
