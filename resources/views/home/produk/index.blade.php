@extends('layouts.master')
@section('title', 'Data Produk')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Data Produk</h4>
                        <h6 class="card-subtitle">Kelola produk S&Waldorf</h6>
                    </div>
                    <a href="{{ route('produk.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Produk
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="tableProduk" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Barcode</th>
                                <th>Ukuran</th>
                                <th>Warna</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $produk->nama_produk }}</strong></td>
                                <td><span class="badge badge-info">{{ $produk->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>
                                    <code>{{ $produk->barcode }}</code><br>
                                    <button class="btn btn-xs btn-info mt-1" onclick="showBarcode({{ $produk->id_produk }}, '{{ $produk->nama_produk }}')">
                                        <i class="mdi mdi-barcode"></i> Preview
                                    </button>
                                </td>
                                <td>{{ $produk->ukuran }}</td>
                                <td>{{ $produk->warna }}</td>
                                <td>
                                    @if($produk->stok <= 0)
                                        <span class="badge badge-danger">{{ $produk->stok }}</span>
                                    @elseif($produk->stok <= 10)
                                        <span class="badge badge-warning">{{ $produk->stok }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $produk->stok }}</span>
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="updateStok({{ $produk->id_produk }}, '{{ $produk->nama_produk }}', {{ $produk->stok }})" title="Update Stok">
                                        <i class="mdi mdi-package-variant-closed"></i>
                                    </button>
                                    <a href="{{ route('produk.edit', $produk->id_produk) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <a href="{{ route('produk.print-barcode', $produk->id_produk) }}" 
                                       class="btn btn-sm btn-success" title="Print Barcode" target="_blank">
                                        <i class="mdi mdi-printer"></i>
                                    </a>
                                    <form action="{{ route('produk.destroy', $produk->id_produk) }}" 
                                          method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data produk</td>
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
    $('#tableProduk').DataTable({
        "pageLength": 10,
        "ordering": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function showBarcode(id, nama) {
    Swal.fire({
        title: nama,
        html: `
            <div class="text-center">
                <h5>Barcode</h5>
                <img src="{{ url('/produk') }}/${id}/barcode" alt="Barcode" class="img-fluid mb-3" style="max-width: 300px;">
                <div class="mb-3">
                    <a href="{{ url('/produk') }}/${id}/download-barcode" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-download"></i> Download Barcode
                    </a>
                </div>
                
                <h5>QR Code</h5>
                <img src="{{ url('/produk') }}/${id}/qrcode" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                <div class="mb-3">
                    <a href="{{ url('/produk') }}/${id}/download-qrcode" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-download"></i> Download QR Code
                    </a>
                </div>
                
                <a href="{{ url('/produk') }}/${id}/print-barcode" target="_blank" class="btn btn-success">
                    <i class="mdi mdi-printer"></i> Print Label
                </a>
            </div>
        `,
        width: 600,
        showConfirmButton: false,
        showCloseButton: true
    });
}

function updateStok(id, nama, currentStok) {
    Swal.fire({
        title: 'Update Stok: ' + nama,
        html: `
            <div class="text-left">
                <p><strong>Stok Saat Ini:</strong> <span class="badge badge-info">${currentStok} unit</span></p>
                <hr>
                <form id="formUpdateStok">
                    <div class="form-group">
                        <label>Pilih Aksi:</label>
                        <select id="action" class="form-control" required>
                            <option value="add">Tambah Stok</option>
                            <option value="subtract">Kurangi Stok</option>
                            <option value="set">Set Stok (Ganti Total)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jumlah:</label>
                        <input type="number" id="jumlah" class="form-control" min="1" value="1" required>
                    </div>
                </form>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update Stok',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const action = document.getElementById('action').value;
            const jumlah = document.getElementById('jumlah').value;
            
            if (!jumlah || jumlah < 1) {
                Swal.showValidationMessage('Jumlah harus lebih dari 0');
                return false;
            }
            
            return { action: action, jumlah: jumlah };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url('/produk') }}/' + id + '/update-stok';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = result.value.action;
            form.appendChild(actionInput);
            
            const jumlahInput = document.createElement('input');
            jumlahInput.type = 'hidden';
            jumlahInput.name = 'jumlah';
            jumlahInput.value = result.value.jumlah;
            form.appendChild(jumlahInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush