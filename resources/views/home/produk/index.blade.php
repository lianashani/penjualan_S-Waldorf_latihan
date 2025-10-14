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
                    <div class="d-flex gap-2">
                        <a href="{{ route('katalog.elegant') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-store"></i> Katalog Elegan
                        </a>
                        <a href="{{ route('produk.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Tambah Produk
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableProduk" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Produk</th>
                                <th>Gambar</th>
                                <th>Kategori</th>
                                <th>Barcode</th>
                                <th>Varian</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $produk->nama_produk }}</strong>
                                    @if($produk->is_featured)
                                        <br><span class="badge badge-warning badge-sm">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    @if($produk->main_image)
                                        <img src="{{ $produk->main_image }}" alt="{{ $produk->nama_produk }}" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                                        @if($produk->images->count() > 1)
                                            <br><small class="text-muted">{{ $produk->images->count() }} gambar</small>
                                        @endif
                                    @else
                                        <div class="text-muted" style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;background:#f5f5f5;border-radius:4px;">
                                            <i class="mdi mdi-image-off"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><span class="badge badge-info">{{ $produk->kategori->nama_kategori ?? '-' }}</span></td>
                                <td>
                                    <code>{{ $produk->barcode }}</code><br>
                                    <button class="btn btn-xs btn-info mt-1" onclick="showBarcode({{ $produk->id_produk }}, '{{ $produk->nama_produk }}')">
                                        <i class="mdi mdi-barcode"></i> Preview
                                    </button>
                                </td>
                                <td>
                                    @if($produk->has_variants)
                                        <div class="text-center">
                                            <span class="badge badge-primary">{{ $produk->variants->count() }} varian</span>
                                            <br>
                                            <small class="text-muted">
                                                {{ $produk->variants->pluck('ukuran')->unique()->count() }} ukuran<br>
                                                {{ $produk->variants->pluck('warna')->unique()->count() }} warna
                                            </small>
                                        </div>
                                    @else
                                        <div>
                                            <strong>Ukuran:</strong> {{ $produk->ukuran }}<br>
                                            <strong>Warna:</strong> {{ $produk->warna }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($produk->has_variants)
                                        <span class="badge badge-{{ $produk->total_stok <= 0 ? 'danger' : ($produk->total_stok <= 10 ? 'warning' : 'success') }}">
                                            {{ $produk->total_stok }}
                                        </span>
                                    @else
                                        <span class="badge badge-{{ $produk->stok <= 0 ? 'danger' : ($produk->stok <= 10 ? 'warning' : 'success') }}">
                                            {{ $produk->stok }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($produk->has_variants)
                                        <div class="text-center">
                                            <strong>{{ $produk->formatted_price }}</strong>
                                            <br><small class="text-muted">Bervariasi</small>
                                        </div>
                                    @else
                                        <strong>Rp. {{ number_format($produk->harga, 0, ',', '.') }}</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($produk->rating_count > 0)
                                        <div class="text-center">
                                            <span class="text-warning">{!! $produk->rating_stars !!}</span>
                                            <br><small class="text-muted">({{ $produk->rating_count }})</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Belum ada rating</span>
                                    @endif
                                </td>
                                <td>
                                    @if($produk->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
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
                                <td colspan="11" class="text-center">Belum ada data produk</td>
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
