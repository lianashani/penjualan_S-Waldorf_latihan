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