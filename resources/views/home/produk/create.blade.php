@extends('layouts.master')
@section('title', 'Tambah Produk')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
<li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Produk</h4>
                <h6 class="card-subtitle mb-4">Isi form untuk menambah produk baru dengan varian</h6>

                <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf

                    <!-- Basic Product Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                       name="nama_produk" value="{{ old('nama_produk') }}" required>
                                @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori <span class="text-danger">*</span></label>
                                <select class="form-control @error('id_kategori') is-invalid @enderror"
                                        name="id_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Harga Dasar <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                       name="harga" value="{{ old('harga') }}" min="0" step="0.01" required>
                                @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted">Harga dasar produk (dapat diubah per varian)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status Produk</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label">Featured</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                          name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Product Type Selection -->
                    <div class="row">
                        <div class="col-12">
                            <h5>Jenis Produk</h5>
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="has_variants" value="0" id="singleProduct" {{ old('has_variants', '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="singleProduct">Produk Tunggal</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" name="has_variants" value="1" id="variantProduct" {{ old('has_variants') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="variantProduct">Produk dengan Varian</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Product Form -->
                    <div id="singleProductForm" style="display: {{ old('has_variants', '0') == '0' ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ukuran <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ukuran') is-invalid @enderror"
                                           name="ukuran" value="{{ old('ukuran') }}" placeholder="S, M, L, XL">
                                    @error('ukuran')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Warna <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('warna') is-invalid @enderror"
                                           name="warna" value="{{ old('warna') }}">
                                    @error('warna')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Stok <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                           name="stok" value="{{ old('stok', 0) }}" min="0" required>
                                    @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Utama</label>
                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                           name="gambar" accept="image/*">
                                    @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Variant Product Form -->
                    <div id="variantProductForm" style="display: {{ old('has_variants') == '1' ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-12">
                                <h6>Varian Produk</h6>
                                <div id="variantsContainer">
                                    <!-- Variants will be added here dynamically -->
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addVariant">
                                    <i class="mdi mdi-plus"></i> Tambah Varian
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Gambar Produk</h6>
                                <div class="form-group">
                                    <div class="file-upload-area border-2 border-dashed border-primary rounded p-4 text-center">
                                        <input type="file" class="form-control d-none" name="images[]" id="imageUpload" multiple accept="image/*">
                                        <div class="upload-content">
                                            <i class="mdi mdi-cloud-upload display-4 text-primary mb-3"></i>
                                            <h5>Drag & Drop gambar di sini</h5>
                                            <p class="text-muted">atau klik untuk memilih file</p>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('imageUpload').click()">
                                                <i class="mdi mdi-folder-open me-1"></i>Pilih Gambar
                                            </button>
                                        </div>
                                    </div>
                                    <div id="imagePreview" class="row mt-3" style="display: none;">
                                        <!-- Preview images will be shown here -->
                                    </div>
                                    <small class="text-muted">Format: JPG, PNG (Max: 2MB per file, Max: 10 gambar)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.file-upload-area {
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #0056b3 !important;
    background-color: #f8f9fa;
}

.file-upload-area.dragover {
    border-color: #28a745 !important;
    background-color: #d4edda;
}

.image-preview-item {
    position: relative;
    margin-bottom: 15px;
}

.image-preview-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e9ecef;
}

.image-preview-item .remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #dc3545;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
}

.image-preview-item .image-order {
    position: absolute;
    top: -8px;
    left: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let variantCount = 0;
    let selectedImages = [];

    // Toggle between single and variant product forms
    $('input[name="has_variants"]').change(function() {
        if ($(this).val() == '1') {
            $('#singleProductForm').hide();
            $('#variantProductForm').show();
        } else {
            $('#singleProductForm').show();
            $('#variantProductForm').hide();
        }
    });

    // Add variant
    $('#addVariant').click(function() {
        variantCount++;
        const variantHtml = `
            <div class="variant-item border rounded p-3 mb-3" data-variant="${variantCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Varian ${variantCount}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Ukuran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="variants[${variantCount}][ukuran]" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Warna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="variants[${variantCount}][warna]" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Kode Warna</label>
                            <input type="color" class="form-control form-control-color" name="variants[${variantCount}][kode_warna]">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="variants[${variantCount}][stok]" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" name="variants[${variantCount}][harga]" min="0" step="0.01">
                            <small class="text-muted">Kosongkan untuk menggunakan harga dasar</small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#variantsContainer').append(variantHtml);
    });

    // Remove variant
    $(document).on('click', '.remove-variant', function() {
        $(this).closest('.variant-item').remove();
    });

    // Image upload handling
    const fileUploadArea = $('.file-upload-area');
    const imageUpload = $('#imageUpload');
    const imagePreview = $('#imagePreview');

    // Drag and drop events
    fileUploadArea.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    fileUploadArea.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    fileUploadArea.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        handleImageFiles(files);
    });

    // File input change
    imageUpload.on('change', function(e) {
        const files = e.target.files;
        handleImageFiles(files);
    });

    // Handle image files
    function handleImageFiles(files) {
        if (files.length === 0) return;

        // Validate file count
        if (selectedImages.length + files.length > 10) {
            Swal.fire('Error', 'Maksimal 10 gambar per produk!', 'error');
            return;
        }

        Array.from(files).forEach((file, index) => {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                Swal.fire('Error', 'File harus berupa gambar!', 'error');
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire('Error', 'Ukuran file maksimal 2MB!', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const imageData = {
                    file: file,
                    url: e.target.result,
                    name: file.name
                };
                selectedImages.push(imageData);
                updateImagePreview();
            };
            reader.readAsDataURL(file);
        });
    }

    // Update image preview
    function updateImagePreview() {
        if (selectedImages.length === 0) {
            imagePreview.hide();
            return;
        }

        imagePreview.show();
        imagePreview.empty();

        selectedImages.forEach((imageData, index) => {
            const previewHtml = `
                <div class="col-md-3 col-sm-4 col-6">
                    <div class="image-preview-item">
                        <img src="${imageData.url}" alt="${imageData.name}">
                        <div class="image-order">${index + 1}</div>
                        <button type="button" class="remove-image" onclick="removeImage(${index})">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                </div>
            `;
            imagePreview.append(previewHtml);
        });
    }

    // Remove image
    window.removeImage = function(index) {
        selectedImages.splice(index, 1);
        updateImagePreview();
        updateFileInput();
    };

    // Update file input
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedImages.forEach(imageData => {
            dt.items.add(imageData.file);
        });
        imageUpload[0].files = dt.files;
    }

    // Form validation
    $('#productForm').submit(function(e) {
        const hasVariants = $('input[name="has_variants"]:checked').val();

        if (hasVariants == '1') {
            const variantCount = $('.variant-item').length;
            if (variantCount === 0) {
                e.preventDefault();
                Swal.fire('Error', 'Minimal harus ada 1 varian untuk produk dengan varian!', 'error');
                return false;
            }
        }

        // Validate images
        if (selectedImages.length === 0) {
            e.preventDefault();
            Swal.fire('Error', 'Minimal harus ada 1 gambar untuk produk!', 'error');
            return false;
        }
    });

    // Add initial variant if variant product is selected
    if ($('input[name="has_variants"]:checked').val() == '1') {
        $('#addVariant').click();
    }
});
</script>
@endpush
