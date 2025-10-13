<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - S&Waldorf</title>
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background:#fff; color:#111 }
        .container { max-width: 1000px }
        .card-plain { background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:20px }
        .badge-dark { background:#111 }
        .empty { text-align:center; color:#666; padding:40px }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Riwayat Transaksi</h4>
        <a href="{{ route('member.dashboard') }}" class="btn btn-sm btn-outline-dark"><i class="mdi mdi-arrow-left"></i> Dashboard</a>
    </div>

    <div class="card-plain">
        @if($orders->count() === 0)
            <div class="empty">
                <i class="mdi mdi-history" style="font-size:48px"></i>
                <h5 class="mt-2">Belum ada transaksi</h5>
                <p class="mb-0">Transaksi Anda akan tampil di sini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $o)
                            <tr>
                                <td>{{ $o->created_at->format('d M Y H:i') }}</td>
                                <td>#{{ $o->id }}</td>
                                <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                                <td><span class="badge badge-dark">{{ ucfirst($o->status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
