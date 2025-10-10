<?php
/**
 * Script untuk generate semua CRUD views
 * Jalankan: php generate-crud-views.php
 */

$basePath = __DIR__ . '/resources/views/home';

// Template untuk Produk Index
$produkIndex = <<<'BLADE'
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
                                <th>Ukuran</th>
                                <th>Warna</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produks as $produk)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $produk->nama_produk }}</strong></td>
                                <td><span class="badge badge-info">{{ $produk->kategori->nama_kategori ?? '-' }}</span></td>
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
                                    <a href="{{ route('produk.edit', $produk->id_produk) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
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
                                <td colspan="8" class="text-center">Belum ada data produk</td>
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
</script>
@endpush
BLADE;

// Tulis file
file_put_contents($basePath . '/produk/index.blade.php', $produkIndex);

echo "✓ Produk index.blade.php created\n";

// Anda bisa tambahkan template lainnya di sini...
// Untuk sekarang saya akan buat yang paling penting dulu

echo "\nDone! Files generated successfully.\n";
