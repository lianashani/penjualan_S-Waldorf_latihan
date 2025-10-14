@extends('layouts.master')
@section('title', 'Kelola Rating Produk')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Rating Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title">Kelola Rating Produk</h4>
                        <h6 class="card-subtitle">Kelola ulasan dan rating dari pelanggan</h6>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="bulkAction('approve')">
                            <i class="mdi mdi-check"></i> Setujui Terpilih
                        </button>
                        <button class="btn btn-warning" onclick="bulkAction('reject')">
                            <i class="mdi mdi-close"></i> Tolak Terpilih
                        </button>
                        <button class="btn btn-danger" onclick="bulkAction('delete')">
                            <i class="mdi mdi-delete"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>

                <form id="bulkForm" method="POST" action="{{ route('ratings.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="action" id="bulkAction">
                    <input type="hidden" name="ratings" id="bulkRatings">

                    <div class="table-responsive">
                        <table id="tableRatings" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th width="50">No</th>
                                    <th>Produk</th>
                                    <th>Pengguna</th>
                                    <th>Rating</th>
                                    <th>Komentar</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ratings as $rating)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_ratings[]" value="{{ $rating->id_rating }}" class="form-check-input rating-checkbox">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $rating->produk->main_image }}"
                                                 alt="{{ $rating->produk->nama_produk }}"
                                                 style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                            <div>
                                                <strong>{{ $rating->produk->nama_produk }}</strong><br>
                                                <small class="text-muted">{{ $rating->produk->kategori->nama_kategori }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $rating->display_name }}</strong><br>
                                            <small class="text-muted">{{ $rating->email_pengguna ?? $rating->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning me-2">{!! $rating->stars !!}</span>
                                            <span class="badge bg-info">{{ $rating->rating_text }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($rating->komentar)
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $rating->komentar }}">
                                                {{ $rating->komentar }}
                                            </div>
                                        @else
                                            <span class="text-muted">Tidak ada komentar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rating->is_approved)
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                        @if($rating->is_verified_purchase)
                                            <br><small class="badge bg-info mt-1">Verified Purchase</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            {{ $rating->created_at->format('d/m/Y') }}<br>
                                            <small class="text-muted">{{ $rating->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if(!$rating->is_approved)
                                                <button class="btn btn-sm btn-success" onclick="approveRating({{ $rating->id_rating }})" title="Setujui">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-warning" onclick="rejectRating({{ $rating->id_rating }})" title="Tolak">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-info" onclick="viewRating({{ $rating->id_rating }})" title="Lihat Detail">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteRating({{ $rating->id_rating }})" title="Hapus">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada rating produk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                @if($ratings->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $ratings->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tableRatings').DataTable({
        "pageLength": 10,
        "ordering": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Select all checkbox
    $('#selectAll').change(function() {
        $('.rating-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Individual checkbox change
    $(document).on('change', '.rating-checkbox', function() {
        const totalCheckboxes = $('.rating-checkbox').length;
        const checkedCheckboxes = $('.rating-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
});

function approveRating(id) {
    Swal.fire({
        title: 'Setujui Rating?',
        text: 'Rating ini akan ditampilkan di katalog produk',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/ratings/${id}/approve`;
        }
    });
}

function rejectRating(id) {
    Swal.fire({
        title: 'Tolak Rating?',
        text: 'Rating ini tidak akan ditampilkan di katalog produk',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/ratings/${id}/reject`;
        }
    });
}

function deleteRating(id) {
    Swal.fire({
        title: 'Hapus Rating?',
        text: 'Rating ini akan dihapus secara permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/ratings/${id}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        }
    });
}

function viewRating(id) {
    // Get rating data via AJAX and show in modal
    $.ajax({
        url: `/admin/ratings/${id}`,
        method: 'GET',
        success: function(response) {
            Swal.fire({
                title: 'Detail Rating',
                html: `
                    <div class="text-left">
                        <p><strong>Produk:</strong> ${response.produk.nama_produk}</p>
                        <p><strong>Pengguna:</strong> ${response.display_name}</p>
                        <p><strong>Email:</strong> ${response.email_pengguna || response.user?.email || 'N/A'}</p>
                        <p><strong>Rating:</strong> <span class="text-warning">${response.stars}</span> (${response.rating_text})</p>
                        <p><strong>Komentar:</strong> ${response.komentar || 'Tidak ada komentar'}</p>
                        <p><strong>Status:</strong> ${response.is_approved ? 'Disetujui' : 'Menunggu'}</p>
                        <p><strong>Tanggal:</strong> ${response.created_at}</p>
                    </div>
                `,
                showConfirmButton: false,
                showCloseButton: true,
                width: 600
            });
        },
        error: function() {
            Swal.fire('Error', 'Gagal memuat detail rating', 'error');
        }
    });
}

function bulkAction(action) {
    const selectedRatings = $('.rating-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedRatings.length === 0) {
        Swal.fire('Peringatan', 'Pilih rating yang akan diproses', 'warning');
        return;
    }

    let title, text, confirmText;
    switch (action) {
        case 'approve':
            title = 'Setujui Rating Terpilih?';
            text = `${selectedRatings.length} rating akan disetujui dan ditampilkan di katalog`;
            confirmText = 'Ya, Setujui';
            break;
        case 'reject':
            title = 'Tolak Rating Terpilih?';
            text = `${selectedRatings.length} rating akan ditolak dan tidak ditampilkan di katalog`;
            confirmText = 'Ya, Tolak';
            break;
        case 'delete':
            title = 'Hapus Rating Terpilih?';
            text = `${selectedRatings.length} rating akan dihapus secara permanen`;
            confirmText = 'Ya, Hapus';
            break;
    }

    Swal.fire({
        title: title,
        text: text,
        icon: action === 'delete' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        confirmButtonColor: action === 'delete' ? '#dc3545' : undefined
    }).then((result) => {
        if (result.isConfirmed) {
            $('#bulkAction').val(action);
            $('#bulkRatings').val(JSON.stringify(selectedRatings));
            $('#bulkForm').submit();
        }
    });
}
</script>
@endpush
