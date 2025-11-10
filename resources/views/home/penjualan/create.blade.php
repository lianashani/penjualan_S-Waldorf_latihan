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
                            <h5 class="mb-0"><i class="mdi mdi-barcode-scan"></i> Scan Barcode Produk</h5>
                        </div>
                        <div class="card-body">
                            <label>Scan atau Ketik Barcode Produk</label>
                            <input type="text" id="barcodeInput" class="form-control form-control-lg"
                                   placeholder="Scan barcode atau ketik manual (contoh: PRD000001)"
                                   autofocus>
                            <small class="text-muted">
                                <i class="mdi mdi-information"></i>
                                Gunakan scanner barcode atau ketik manual lalu tekan Enter
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pelanggan <small class="text-muted">(Opsional)</small></label>
                                <select name="id_pelanggan" id="pelangganSelect" class="form-control select2">
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
                                <select name="id_promo" id="promoSelect" class="form-control select2">
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
                                                    @php
                                                        $displayStok = $produk->has_variants ? $produk->total_stok : $produk->stok;
                                                        $displayHarga = $produk->has_variants && $produk->harga_min && $produk->harga_max && $produk->harga_min != $produk->harga_max
                                                            ? 'Rp. ' . number_format($produk->harga_min, 0, ',', '.') . ' - Rp. ' . number_format($produk->harga_max, 0, ',', '.')
                                                            : 'Rp. ' . number_format($produk->harga, 0, ',', '.');
                                                    @endphp
                                                    <option value="{{ $produk->id_produk }}"
                                                            data-harga="{{ $produk->harga }}"
                                                            data-stok="{{ $displayStok }}"
                                                            data-has-variants="{{ $produk->has_variants ? 1 : 0 }}"
                                                            data-barcode="{{ $produk->barcode }}">
                                                        {{ $produk->nama_produk }} - {{ $produk->kategori->nama_kategori ?? '' }}
                                                        ({{ $produk->barcode }}) (Stok: {{ $displayStok }}) - {{ $displayHarga }}
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
                                <label><strong>Metode Pembayaran</strong> <span class="text-danger">*</span></label>
                                <select name="payment_method" id="paymentMethod" class="form-control form-control-lg" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="cash">Cash / Tunai</option>
                                    <option value="qris">QRIS (Midtrans)</option>
                                </select>
                                <small class="text-muted">Pilih metode pembayaran pelanggan</small>
                            </div>

                            <div class="form-group" id="bayarGroup">
                                <label><strong>Jumlah Bayar</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="total_bayar" id="bayarInput" class="form-control form-control-lg"
                                       step="0.01" min="0" required>
                                <small class="text-muted">Masukkan jumlah uang yang dibayarkan pelanggan</small>
                            </div>

                            <!-- QRIS Action -->
                            <div id="qrisActionGroup" style="display: none;">
                                <button type="button" id="generateQris" class="btn btn-info btn-lg btn-block">
                                    <i class="mdi mdi-qrcode"></i> Generate QRIS Code
                                </button>
                                <small class="text-muted d-block mt-2">
                                    <i class="mdi mdi-information"></i> Klik untuk generate QR code, tunjukkan ke pembeli untuk scan
                                </small>
                            </div>

                            <div class="alert alert-info" id="kembalianDisplay" style="display: none;">
                                <h5>Kembalian: <strong id="kembalianValue">Rp. 0</strong></h5>
                            </div>
                        </div>
                    </div>                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
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

<!-- QRIS Modal -->
<div class="modal fade" id="qrisModal" tabindex="-1" role="dialog" aria-labelledby="qrisModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 25px;">
                <h5 class="modal-title text-white" id="qrisModalLabel" style="font-weight: 600; font-size: 20px;">
                    <i class="mdi mdi-qrcode" style="font-size: 24px;"></i> Pembayaran QRIS
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                    <span aria-hidden="true" style="font-size: 28px;">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 30px;">
                <!-- Loading State -->
                <div id="qrisLoading" style="display: none; padding: 40px 0;">
                    <div class="text-center">
                        <div class="spinner-border" role="status" style="width: 3rem; height: 3rem; color: #667eea;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-3" style="color: #6c757d; font-size: 16px;">Sedang membuat QR Code...</p>
                    </div>
                </div>

                <!-- QR Content -->
                <div id="qrisContent" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- QR Code -->
                            <div class="text-center" style="background: #f8f9fa; padding: 20px; border-radius: 12px; border: 2px dashed #dee2e6;">
                                <div id="qrisImageContainer" style="background: white; padding: 15px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    <img id="qrisImage" src="" alt="QR Code" style="max-width: 250px; height: auto; display: block;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Payment Info -->
                            <div style="padding: 20px;">
                                <h5 style="color: #495057; font-weight: 600; margin-bottom: 20px;">Detail Pembayaran</h5>

                                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                                    <p style="color: rgba(255,255,255,0.9); margin-bottom: 5px; font-size: 14px;">Total Pembayaran</p>
                                    <h2 id="qrisTotal" style="color: white; font-weight: bold; margin: 0; font-size: 32px;">Rp. 0</h2>
                                </div>

                                <div class="alert" style="background-color: #e3f2fd; border-left: 4px solid #2196f3; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                                    <div style="display: flex; align-items: start;">
                                        <i class="mdi mdi-information" style="color: #2196f3; font-size: 24px; margin-right: 12px;"></i>
                                        <div style="flex: 1;">
                                            <p style="color: #1976d2; margin: 0; font-size: 14px; line-height: 1.6;">
                                                <strong>Cara Pembayaran:</strong><br>
                                                1. Buka aplikasi e-wallet (GoPay, OVO, DANA, dll)<br>
                                                2. Pilih menu Scan QR<br>
                                                3. Scan QR Code di sebelah kiri<br>
                                                4. Konfirmasi pembayaran
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div style="background: #f8f9fa; padding: 12px; border-radius: 6px; margin-top: 15px;">
                                    <small style="color: #6c757d; display: block; margin-bottom: 5px;">Transaction ID</small>
                                    <code id="qrisTransId" style="background: white; padding: 6px 10px; border-radius: 4px; font-size: 12px; display: inline-block;"></code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div id="qrisError" class="alert alert-danger" style="display: none; border-radius: 8px;">
                    <i class="mdi mdi-alert-circle"></i> <span id="qrisErrorMsg"></span>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px 30px;">
                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 8px; padding: 10px 24px;">
                    <i class="mdi mdi-close"></i> Tutup
                </button>
                <button type="button" class="btn btn-success" id="confirmQrisPayment" style="display: none; border-radius: 8px; padding: 10px 24px; background: #28a745; border: none; box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);">
                    <i class="mdi mdi-check-circle"></i> Pembayaran Selesai
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* QRIS Modal Animations */
    #qrisModal .modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #qrisContent {
        animation: fadeInScale 0.4s ease-out;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* QR Image hover effect */
    #qrisImageContainer img {
        transition: transform 0.3s ease;
    }

    #qrisImageContainer img:hover {
        transform: scale(1.05);
    }

    /* Button hover effects */
    #confirmQrisPayment {
        transition: all 0.3s ease;
    }

    #confirmQrisPayment:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4) !important;
    }

    /* Loading spinner animation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .mdi-spin {
        animation: spin 1s linear infinite;
    }

    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        color: #495057;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #667eea;
    }

    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('#pelangganSelect').select2({
        placeholder: '-- Guest / Tanpa Pelanggan --',
        allowClear: true,
        width: '100%'
    });

    $('#promoSelect').select2({
        placeholder: '-- Tanpa Promo --',
        allowClear: true,
        width: '100%'
    });

    // Initialize produk select2 for existing rows
    $('.produk-select').select2({
        placeholder: '-- Pilih Produk --',
        allowClear: true,
        width: '100%'
    });

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
                                    @php
                                        $displayStok = $produk->has_variants ? $produk->total_stok : $produk->stok;
                                        $displayHarga = $produk->has_variants && $produk->harga_min && $produk->harga_max && $produk->harga_min != $produk->harga_max
                                            ? 'Rp. ' . number_format($produk->harga_min, 0, ',', '.') . ' - Rp. ' . number_format($produk->harga_max, 0, ',', '.')
                                            : 'Rp. ' . number_format($produk->harga, 0, ',', '.');
                                    @endphp
                                    <option value="{{ $produk->id_produk }}"
                                            data-harga="{{ $produk->harga }}"
                                            data-stok="{{ $displayStok }}"
                                            data-has-variants="{{ $produk->has_variants ? 1 : 0 }}"
                                            data-barcode="{{ $produk->barcode }}"
                                            {{ $item['id_produk'] == $produk->id_produk ? 'selected' : '' }}>
                                        {{ $produk->nama_produk }} - {{ $produk->kategori->nama_kategori ?? '' }}
                                        ({{ $produk->barcode }}) (Stok: {{ $displayStok }}) - {{ $displayHarga }}
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
                        @if(isset($item['variant']))
                        <div class="col-12">
                            <small class="text-muted">Varian: Ukuran {{ $item['variant']['ukuran'] ?? '-' }}, Warna {{ $item['variant']['warna'] ?? '-' }}</small>
                            <input type="hidden" name="items[{{ $index }}][ukuran]" value="{{ $item['variant']['ukuran'] ?? '' }}">
                            <input type="hidden" name="items[{{ $index }}][warna]" value="{{ $item['variant']['warna'] ?? '' }}">
                        </div>
                        @endif
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
                                @php
                                    $displayStok = $produk->has_variants ? $produk->total_stok : $produk->stok;
                                    // Display price range for variant products
                                    $displayHarga = $produk->has_variants && $produk->harga_min != $produk->harga_max
                                        ? 'Rp. ' . number_format($produk->harga_min, 0, ',', '.') . ' - Rp. ' . number_format($produk->harga_max, 0, ',', '.')
                                        : 'Rp. ' . number_format($produk->harga, 0, ',', '.');
                                @endphp
                                <option value="{{ $produk->id_produk }}"
                                        data-harga="{{ $produk->harga }}"
                                        data-stok="{{ $displayStok }}"
                                        data-has-variants="{{ $produk->has_variants ? 1 : 0 }}"
                                        data-barcode="{{ $produk->barcode }}">
                                    {{ $produk->nama_produk }} - {{ $produk->kategori->nama_kategori ?? '' }}
                                    (Stok: {{ $displayStok }}) - {{ $displayHarga }}
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

        // Initialize select2 for the new row
        $('#itemsContainer').find('.item-row').last().find('.produk-select').select2({
            placeholder: '-- Pilih Produk --',
            allowClear: true,
            width: '100%'
        });

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
    $(document).on('change', '.produk-select', function() {
        const row = $(this).closest('.item-row');
        calculateRowSubtotal(row);
    });

    $(document).on('change', '.qty-input', function() {
        const row = $(this).closest('.item-row');
        calculateRowSubtotal(row);
    });    function calculateRowSubtotal(row) {
        const select = row.find('.produk-select');
        const qtyInput = row.find('.qty-input');
        const subtotalDisplay = row.find('.subtotal-display');

        const harga = parseFloat(select.find(':selected').data('harga')) || 0;
        const stok = parseInt(select.find(':selected').data('stok')) || 0;
        const hasVariants = parseInt(select.find(':selected').data('has-variants')) || 0;
        const qty = parseInt(qtyInput.val()) || 0;

        // Skip stock validation for products with variants (will be validated on backend)
        if (hasVariants === 0) {
            // Validate stock only for non-variant products
            if (qty > stok) {
                Swal.fire('Stok Tidak Cukup', `Stok tersedia: ${stok}`, 'error');
                qtyInput.val(stok > 0 ? stok : 0);
                return;
            }
        }

        const subtotal = harga * qty;
        subtotalDisplay.val('Rp. ' + subtotal.toLocaleString('id-ID'));

        calculateTotal();
    }

    // Show variant selection modal
    function showVariantSelectionModal(productId, productName, row) {
        $.ajax({
            url: `/produk/${productId}/variants`,
            method: 'GET',
            success: function(variants) {
                if (variants.length === 0) {
                    Swal.fire('Error', 'Produk ini tidak memiliki varian tersedia', 'error');
                    row.find('.produk-select').val('').trigger('change');
                    return;
                }

                let variantOptions = '<select id="variantSelect" class="form-control mb-3"><option value="">-- Pilih Varian --</option>';
                variants.forEach(function(variant) {
                    variantOptions += `<option value="${variant.id_variant}"
                        data-ukuran="${variant.ukuran}"
                        data-warna="${variant.warna}"
                        data-stok="${variant.stok}"
                        data-harga="${variant.harga || 0}">
                        ${variant.ukuran} - ${variant.warna} (Stok: ${variant.stok})
                    </option>`;
                });
                variantOptions += '</select>';

                Swal.fire({
                    title: 'Pilih Varian',
                    html: `<p>Produk: <strong>${productName}</strong></p>${variantOptions}`,
                    showCancelButton: true,
                    confirmButtonText: 'Pilih',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        const selectedVariant = $('#variantSelect').val();
                        if (!selectedVariant) {
                            Swal.showValidationMessage('Silakan pilih varian');
                            return false;
                        }
                        return {
                            id_variant: selectedVariant,
                            ukuran: $('#variantSelect option:selected').data('ukuran'),
                            warna: $('#variantSelect option:selected').data('warna'),
                            stok: $('#variantSelect option:selected').data('stok'),
                            harga: $('#variantSelect option:selected').data('harga')
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update row with variant info
                        const variant = result.value;
                        row.find('.produk-select').attr('data-stok', variant.stok);
                        row.find('.produk-select').attr('data-harga', variant.harga);

                        // Add hidden inputs for variant
                        row.find('input[name$="[ukuran]"]').remove();
                        row.find('input[name$="[warna]"]').remove();
                        row.find('input[name$="[id_variant]"]').remove();

                        const index = row.data('index');
                        row.find('.produk-select').after(`
                            <input type="hidden" name="items[${index}][ukuran]" value="${variant.ukuran}">
                            <input type="hidden" name="items[${index}][warna]" value="${variant.warna}">
                            <input type="hidden" name="items[${index}][id_variant]" value="${variant.id_variant}">
                        `);

                        // Show variant info
                        row.find('.variant-info').remove();
                        row.find('.produk-select').after(`
                            <small class="text-muted variant-info d-block">Varian: ${variant.ukuran} - ${variant.warna}</small>
                        `);

                        calculateRowSubtotal(row);
                    } else {
                        // User cancelled, reset product selection
                        row.find('.produk-select').val('').trigger('change');
                    }
                });
            },
            error: function() {
                Swal.fire('Error', 'Gagal memuat varian produk', 'error');
                row.find('.produk-select').val('').trigger('change');
            }
        });
    }

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

    // Handle payment method selection
    $('#paymentMethod').on('change', function() {
        const method = $(this).val();
        const $bayarGroup = $('#bayarGroup');
        const $qrisGroup = $('#qrisActionGroup');
        const $bayarInput = $('#bayarInput');
        const $submitBtn = $('#submitBtn');

        // Hide all first
        $bayarGroup.hide();
        $qrisGroup.hide();
        $('#kembalianDisplay').hide();

        if (method === 'qris') {
            // Show QRIS generate button
            $qrisGroup.show();
            $bayarInput.removeAttr('required');
            $submitBtn.prop('disabled', true); // Disable until QRIS paid

            // Auto fill with total amount
            const total = parseFloat($('#totalDisplay').data('total')) || 0;
            $bayarInput.val(total);
        } else if (method === 'in_store') {
            // Hide jumlah bayar for bayar di outlet
            $bayarInput.removeAttr('required');
            $submitBtn.prop('disabled', false);

            // Auto fill with total amount
            const total = parseFloat($('#totalDisplay').data('total')) || 0;
            $bayarInput.val(total);
        } else if (method === 'cash') {
            // Show for cash
            $bayarGroup.show();
            $bayarInput.attr('required', 'required');
            $submitBtn.prop('disabled', false);
        } else {
            $submitBtn.prop('disabled', false);
        }
    });    // Main calculation function
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
        const paymentMethod = $('#paymentMethod').val();

        // Check payment method selected
        if (!paymentMethod) {
            e.preventDefault();
            Swal.fire('Metode Pembayaran', 'Pilih metode pembayaran terlebih dahulu!', 'warning');
            return false;
        }

        // Only validate bayar for cash
        if (paymentMethod === 'cash') {
            const bayar = parseFloat($('#bayarInput').val()) || 0;

            if (bayar < total) {
                e.preventDefault();
                Swal.fire('Pembayaran Kurang', 'Jumlah bayar harus lebih besar atau sama dengan total!', 'error');
                return false;
            }
        }

        // Validate at least one item
        if ($('.item-row').length === 0) {
            e.preventDefault();
            Swal.fire('Produk Kosong', 'Minimal harus ada 1 produk!', 'error');
            return false;
        }
    });

    // Generate QRIS handler
    let qrisTransactionId = null;

    $('#generateQris').click(function() {
        const total = parseFloat($('#totalDisplay').data('total')) || 0;

        if (total <= 0) {
            Swal.fire('Error', 'Total pembayaran harus lebih dari 0!', 'error');
            return;
        }

        if ($('.item-row').length === 0) {
            Swal.fire('Produk Kosong', 'Tambahkan produk terlebih dahulu!', 'error');
            return;
        }

        // Show modal and loading
        $('#qrisModal').modal('show');
        $('#qrisLoading').show();
        $('#qrisContent').hide();
        $('#qrisError').hide();

        // Call API to generate QRIS
        $.ajax({
            url: '/kasir/generate-qris',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                total: total
            },
            success: function(response) {
                $('#qrisLoading').hide();

                if (response.success && response.qr_string) {
                    qrisTransactionId = response.transaction_id;

                    // Display QR code
                    $('#qrisTotal').text('Rp ' + total.toLocaleString('id-ID'));
                    $('#qrisImage').attr('src', response.qr_string);
                    $('#qrisTransId').text(response.transaction_id);
                    $('#qrisContent').show();
                    $('#confirmQrisPayment').show();
                } else {
                    $('#qrisErrorMsg').text(response.message || 'Gagal generate QR code');
                    $('#qrisError').show();
                }
            },
            error: function(xhr) {
                $('#qrisLoading').hide();
                let errorMsg = 'Terjadi kesalahan saat generate QR code';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                $('#qrisErrorMsg').text(errorMsg);
                $('#qrisError').show();
            }
        });
    });

    // Confirm QRIS payment
    $('#confirmQrisPayment').click(function() {
        if (!qrisTransactionId) {
            Swal.fire('Error', 'Transaction ID tidak ditemukan!', 'error');
            return;
        }

        const $btn = $(this);
        const originalHtml = $btn.html();

        // Show loading
        $btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Mengecek...');

        // Check payment status
        $.ajax({
            url: '/kasir/check-qris-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                transaction_id: qrisTransactionId
            },
            success: function(response) {
                $btn.prop('disabled', false).html(originalHtml);

                if (response.success && response.status === 'settlement') {
                    $('#qrisModal').modal('hide');
                    $('#submitBtn').prop('disabled', false);

                    // Store transaction ID for form submit
                    $('#formPenjualan').append(
                        '<input type="hidden" name="qris_transaction_id" value="' + qrisTransactionId + '">'
                    );

                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        html: '<p style="font-size: 16px; margin-top: 10px;">Pembayaran telah diterima.<br>Silakan simpan transaksi.</p>',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK',
                        timer: 3000
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Dibayar',
                        html: '<p style="font-size: 16px; margin-top: 10px;">Pembayaran belum diterima.<br>Status: <strong>' + (response.status || 'pending') + '</strong></p>',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                $btn.prop('disabled', false).html(originalHtml);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Cek Status',
                    text: 'Terjadi kesalahan saat mengecek status pembayaran',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });
});
</script>
@endpush
