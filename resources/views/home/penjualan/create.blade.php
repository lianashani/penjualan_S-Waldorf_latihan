@extends('layouts.master')
@section('title', 'Transaksi Penjualan Baru')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Transaksi Baru</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Transaksi Penjualan S&Waldorf</h4>
                <h6 class="card-subtitle mb-4">Sistem Perhitungan Diskon Otomatis</h6>

                <form id="formPenjualan" action="{{ route('penjualan.store') }}" method="POST">
                    @csrf

                    <!-- Barcode Scanner Section -->
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="mdi mdi-barcode-scan"></i> Scan Barcode / QR Code</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <label>Scan atau Ketik Barcode Produk</label>
                                    <input type="text" id="barcodeInput" class="form-control form-control-lg" 
                                           placeholder="Scan barcode atau ketik manual (contoh: PRD000001)" 
                                           autofocus>
                                    <small class="text-muted">
                                        <i class="mdi mdi-information"></i> 
                                        Gunakan scanner barcode atau ketik manual lalu tekan Enter
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <label>Upload QR Code</label>
                                    <input type="file" id="qrUpload" class="form-control" accept="image/*">
                                    <small class="text-muted">Upload gambar QR code</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pelanggan <small class="text-muted">(Opsional)</small></label>
                                <select name="id_pelanggan" class="form-control">
                                    <option value="">-- Guest / Tanpa Pelanggan --</option>
                                    @foreach($pelanggans as $pelanggan)
                                        <option value="{{ $pelanggan->id_pelanggan }}">
                                            {{ $pelanggan->nama_pelanggan }} - {{ $pelanggan->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Promo Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kode Promo <small class="text-muted">(Opsional)</small></label>
                                <select name="id_promo" id="promoSelect" class="form-control">
                                    <option value="" data-persen="0">-- Tanpa Promo --</option>
                                    @foreach($promos as $promo)
                                        <option value="{{ $promo->id_promo }}" data-persen="{{ $promo->persen }}">
                                            {{ $promo->kode_promo }} - Diskon {{ $promo->persen }}%
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Product Selection -->
                    <div class="card border">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Pilih Produk</h5>
                        </div>
                        <div class="card-body">
                            <div id="itemsContainer">
                                <div class="item-row mb-3" data-index="0">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Produk</label>
                                            <select name="items[0][id_produk]" class="form-control produk-select" required>
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach($produks as $produk)
                                                    <option value="{{ $produk->id_produk }}" 
                                                            data-harga="{{ $produk->harga }}"
                                                            data-stok="{{ $produk->stok }}"
                                                            data-barcode="{{ $produk->barcode }}">
                                                        {{ $produk->nama_produk }} - {{ $produk->kategori->nama_kategori ?? '' }} 
                                                        ({{ $produk->barcode }}) (Stok: {{ $produk->stok }}) - Rp. {{ number_format($produk->harga, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Qty</label>
                                            <input type="number" name="items[0][qty]" class="form-control qty-input" 
                                                   min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Subtotal</label>
                                            <input type="text" class="form-control subtotal-display" readonly value="Rp. 0">
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block remove-item">
                                                <i class="mdi mdi-delete"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addItem" class="btn btn-success mt-2">
                                <i class="mdi mdi-plus"></i> Tambah Produk
                            </button>
                        </div>
                    </div>

                    <!-- Calculation Summary -->
                    <div class="card border mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Ringkasan Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><strong>Subtotal:</strong></td>
                                    <td><h4 id="subtotalDisplay">Rp. 0</h4></td>
                                </tr>
                                <tr>
                                    <td><strong>Diskon (<span id="diskonPersenDisplay">0</span>%):</strong></td>
                                    <td><h4 class="text-danger" id="diskonDisplay">- Rp. 0</h4></td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Total Setelah Diskon:</strong></td>
                                    <td><h3 class="text-success" id="totalDisplay">Rp. 0</h3></td>
                                </tr>
                            </table>

                            <div class="form-group mt-3">
                                <label><strong>Jumlah Bayar</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="total_bayar" id="bayarInput" class="form-control form-control-lg" 
                                       step="0.01" min="0" required>
                                <small class="text-muted">Masukkan jumlah uang yang dibayarkan pelanggan</small>
                            </div>

                            <div class="alert alert-info" id="kembalianDisplay" style="display: none;">
                                <h5>Kembalian: <strong id="kembalianValue">Rp. 0</strong></h5>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="mdi mdi-content-save"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-lg">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 1;

    // Auto-populate from cart if exists
    @if(!empty($cartItems) && count($cartItems) > 0)
        // Clear default empty row
        $('#itemsContainer').empty();
        
        @php $index = 0; @endphp
        @foreach($cartItems as $item)
            $('#itemsContainer').append(`
                <div class="item-row mb-3" data-index="{{ $index }}">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Produk</label>
                            <select name="items[{{ $index }}][id_produk]" class="form-control produk-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produks as $produk)
                                    <option value="{{ $produk->id_produk }}" 
                                            data-harga="{{ $produk->harga }}"
                                            data-stok="{{ $produk->stok }}"
                                            data-barcode="{{ $produk->barcode }}"
                                            {{ $item['id_produk'] == $produk->id_produk ? 'selected' : '' }}>
                                        {{ $produk->nama_produk }} - {{ $produk->kategori->nama_kategori ?? '' }} 
                                        ({{ $produk->barcode }}) (Stok: {{ $produk->stok }}) - Rp. {{ number_format($produk->harga, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Qty</label>
                            <input type="number" name="items[{{ $index }}][qty]" class="form-control qty-input" 
                                   min="1" value="{{ $item['qty'] }}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Subtotal</label>
                            <input type="text" class="form-control subtotal-display" readonly value="Rp. 0">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-item">
                                <i class="mdi mdi-delete"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            `);
            @php $index++; @endphp
        @endforeach
        
        itemIndex = {{ $index }};
        
        // Trigger calculation
        setTimeout(() => {
            $('.produk-select').trigger('change');
        }, 100);
        
        // Clear cart after loading
        @php session()->forget('keranjang'); @endphp
    @endif

    // Add new item row
    $('#addItem').click(function() {
        const newRow = `
            <div class="item-row mb-3" data-index="${itemIndex}">
                <div class="row">
                    <div class="col-md-5">
                        <label>Produk</label>
                        <select name="items[${itemIndex}][id_produk]" class="form-control produk-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id_produk }}" 
                                        data-harga="{{ $produk->harga }}"
                                        data-stok="{{ $produk->stok }}"
                                        data-barcode="{{ $produk->barcode }}">
                                    {{ $produk->nama_produk }} - {{ $produk->kategori->nama_kategori ?? '' }} 
                                    (Stok: {{ $produk->stok }}) - Rp. {{ number_format($produk->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Qty</label>
                        <input type="number" name="items[${itemIndex}][qty]" class="form-control qty-input" 
                               min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label>Subtotal</label>
                        <input type="text" class="form-control subtotal-display" readonly value="Rp. 0">
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-block remove-item">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#itemsContainer').append(newRow);
        itemIndex++;
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
            calculateTotal();
        } else {
            Swal.fire('Perhatian', 'Minimal harus ada 1 produk!', 'warning');
        }
    });

    // Calculate subtotal when product or qty changes
    $(document).on('change', '.produk-select, .qty-input', function() {
        const row = $(this).closest('.item-row');
        const select = row.find('.produk-select');
        const qtyInput = row.find('.qty-input');
        const subtotalDisplay = row.find('.subtotal-display');

        const harga = parseFloat(select.find(':selected').data('harga')) || 0;
        const stok = parseInt(select.find(':selected').data('stok')) || 0;
        const qty = parseInt(qtyInput.val()) || 0;

        // Validate stock
        if (qty > stok) {
            Swal.fire('Stok Tidak Cukup', `Stok tersedia: ${stok}`, 'error');
            qtyInput.val(stok);
            return;
        }

        const subtotal = harga * qty;
        subtotalDisplay.val('Rp. ' + subtotal.toLocaleString('id-ID'));

        calculateTotal();
    });

    // Calculate when promo changes
    $('#promoSelect').change(function() {
        calculateTotal();
    });

    // Calculate change when payment input changes
    $('#bayarInput').on('input', function() {
        const bayar = parseFloat($(this).val()) || 0;
        const total = parseFloat($('#totalDisplay').data('total')) || 0;

        if (bayar >= total) {
            const kembalian = bayar - total;
            $('#kembalianValue').text('Rp. ' + kembalian.toLocaleString('id-ID'));
            $('#kembalianDisplay').show();
        } else {
            $('#kembalianDisplay').hide();
        }
    });

    // Main calculation function
    function calculateTotal() {
        let subtotal = 0;

        // Sum all item subtotals
        $('.item-row').each(function() {
            const select = $(this).find('.produk-select');
            const qty = parseInt($(this).find('.qty-input').val()) || 0;
            const harga = parseFloat(select.find(':selected').data('harga')) || 0;
            subtotal += harga * qty;
        });

        // Get discount percentage
        const diskonPersen = parseFloat($('#promoSelect').find(':selected').data('persen')) || 0;

        // Validate discount (must be <= 100%)
        if (diskonPersen > 100) {
            Swal.fire('Error', 'Persentase diskon tidak boleh melebihi 100%!', 'error');
            $('#promoSelect').val('');
            return;
        }

        // Calculate discount: nilai_diskon = subtotal * (diskon / 100)
        const nilaiDiskon = subtotal * (diskonPersen / 100);

        // Calculate total after discount: total = subtotal - nilai_diskon
        const total = subtotal - nilaiDiskon;

        // Update displays
        $('#subtotalDisplay').text('Rp. ' + subtotal.toLocaleString('id-ID'));
        $('#diskonPersenDisplay').text(diskonPersen);
        $('#diskonDisplay').text('- Rp. ' + nilaiDiskon.toLocaleString('id-ID'));
        $('#totalDisplay').text('Rp. ' + total.toLocaleString('id-ID')).data('total', total);

        // Reset payment input
        $('#bayarInput').attr('min', total);
        $('#kembalianDisplay').hide();
    }

    // Barcode Scanner Handler
    $('#barcodeInput').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const barcode = $(this).val().trim();
            
            if (barcode) {
                searchProductByBarcode(barcode);
                $(this).val(''); // Clear input
            }
        }
    });

    // Function to search and add product by barcode
    function searchProductByBarcode(barcode) {
        let found = false;
        $('.produk-select option').each(function() {
            const optionVal = $(this).val();
            const optionBarcode = $(this).data('barcode');
            const optionText = $(this).text();
            if (!optionVal) return; 
            if (optionBarcode === barcode || optionText.includes(barcode)) {
                let existingRow = null;
                $('.item-row').each(function() {
                    const selectedVal = $(this).find('.produk-select').val();
                    if (selectedVal == optionVal) {
                        existingRow = $(this);
                        return false;
                    }
                });
                if (existingRow) {
                    const qtyInput = existingRow.find('.qty-input');
                    qtyInput.val((parseInt(qtyInput.val()) || 0) + 1).trigger('change');
                } else {
                    const lastRow = $('.item-row').last();
                    const select = lastRow.find('.produk-select');
                    if (!select.val()) {
                        select.val(optionVal).trigger('change');
                    } else {
                        $('#addItem').click();
                        setTimeout(() => {
                            $('.item-row').last().find('.produk-select').val(optionVal).trigger('change');
                        }, 100);
                    }
                }
                found = true;
                Swal.fire({
                    icon: 'success',
                    title: 'Produk Ditambahkan!',
                    text: optionText,
                    timer: 1500,
                    showConfirmButton: false
                });
                return false;
            }
        });
        if (!found) {
            Swal.fire('Tidak Ditemukan', `Produk dengan barcode "${barcode}" tidak ditemukan!`, 'error');
        }
    }

    // Form validation before submit
    $('#formPenjualan').submit(function(e) {
        const total = parseFloat($('#totalDisplay').data('total')) || 0;
        const bayar = parseFloat($('#bayarInput').val()) || 0;

        if (bayar < total) {
            e.preventDefault();
            Swal.fire('Pembayaran Kurang', 'Jumlah bayar harus lebih besar atau sama dengan total!', 'error');
            return false;
        }

        // Validate at least one item
        if ($('.item-row').length === 0) {
            e.preventDefault();
            Swal.fire('Produk Kosong', 'Minimal harus ada 1 produk!', 'error');
            return false;
        }
    });
});
</script>
@endpush
