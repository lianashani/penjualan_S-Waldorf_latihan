<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tukar Poin - S&Waldorf</title>
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background:#fff; color:#111 }
        .container { max-width: 900px }
        .card-plain { background:#fff; border:1px solid #e5e5e5; border-radius:12px; padding:20px }
        .pill { background:#111; color:#fff; border-radius:999px; padding:6px 12px; font-size:12px; font-weight:600 }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Tukar Poin</h4>
        <a href="{{ route('member.dashboard') }}" class="btn btn-sm btn-outline-dark"><i class="mdi mdi-arrow-left"></i> Dashboard</a>
    </div>

    <div class="card-plain mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted">Poin Anda</div>
                <h2 class="mb-0">{{ number_format($member->points) }} <span class="pill ms-2">Rp {{ number_format($member->getPointsValue(), 0, ',', '.') }}</span></h2>
            </div>
        </div>
    </div>

    <div class="card-plain">
        <h6 class="mb-3">Voucher Tersedia</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 border rounded-3 h-100">
                    <h5>Diskon Rp 10.000</h5>
                    <div class="text-muted mb-2">Tukar 100 poin</div>
                    <button class="btn btn-dark w-100" disabled>Tukar (coming soon)</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded-3 h-100">
                    <h5>Diskon Rp 25.000</h5>
                    <div class="text-muted mb-2">Tukar 250 poin</div>
                    <button class="btn btn-dark w-100" disabled>Tukar (coming soon)</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded-3 h-100">
                    <h5>Diskon Rp 50.000</h5>
                    <div class="text-muted mb-2">Tukar 500 poin</div>
                    <button class="btn btn-dark w-100" disabled>Tukar (coming soon)</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
