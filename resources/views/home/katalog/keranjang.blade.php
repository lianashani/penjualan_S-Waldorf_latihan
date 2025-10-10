@extends('layouts.master')
@section('title', 'Keranjang Belanja')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('katalog.index') }}">Katalog</a></li>
<li class="breadcrumb-item active" aria-current="page">Keranjang</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Keranjang Belanja</h4>
                <h6 class="card-subtitle mb-4">Review produk sebelum checkout</h6>

                @if(empty($cart) || count($cart) == 0)
                <div class="alert alert-info text-center">
                    <i class="mdi mdi-cart-off" style="font-size: 48px;"></i>
                    <h5 class="mt-3">Keranjang Kosong</h5>
                    <p>Belum ada produk di keranjang Anda</p>
                    <a href="{{ route('katalog.index') }}" class="btn btn-primary">
                        <i class="mdi mdi-shopping"></i> Mulai Belanja
                    </a>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th width="80">Gambar</th>
                                <th>Produk</th>
                                <th width="150">Harga</th>
                                <th width="120">Qty</th>
                                <th width="150">Subtotal</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                            <tr>
                                <td>
                                    @if($item['gambar'])
                                    <img src="{{ asset('storage/' . $item['gambar']) }}" 
                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="mdi mdi-image-off"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item['nama_produk'] }}</strong><br>
                                    <small class="text-muted">Barcode: {{ $item['barcode'] }}</small>
                                </td>
                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('keranjang.update') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id_produk" value="{{ $item['id_produk'] }}">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="qty" value="{{ $item['qty'] }}" 
                                                   min="1" class="form-control" style="width: 60px;">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <strong>Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <form action="{{ route('keranjang.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_produk" value="{{ $item['id_produk'] }}">
                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Hapus produk ini?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td colspan="2"><h4 class="mb-0 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</h4></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('katalog.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Lanjut Belanja
                    </a>
                    <form action="{{ route('keranjang.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="mdi mdi-cart-arrow-right"></i> Checkout
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
