<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - S&Waldorf</title>
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem 0;
        }
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 24px;
        }
        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .points-card {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .points-value {
            font-size: 48px;
            font-weight: 700;
        }
        .btn-logout {
            background: white;
            color: #059669;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <span class="navbar-brand">S&WALDORF Member</span>
            <form method="POST" action="{{ route('member.logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="mdi mdi-logout"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>Selamat Datang, {{ $member->nama_member }}! 👋</h2>
            <p class="text-muted mb-0">Email: {{ $member->email }}</p>
            <p class="text-muted">No. HP: {{ $member->no_hp }}</p>
        </div>

        <div class="row">
            <!-- Points Card -->
            <div class="col-md-6">
                <div class="points-card">
                    <i class="mdi mdi-star-circle" style="font-size: 60px;"></i>
                    <h3 class="mt-3">Poin Anda</h3>
                    <div class="points-value">{{ number_format($member->points) }}</div>
                    <p class="mt-2">Setara dengan Rp {{ number_format($member->getPointsValue(), 0, ',', '.') }}</p>
                    <small>100 poin = Rp 10.000</small>
                </div>
            </div>

            <!-- Total Spent Card -->
            <div class="col-md-6">
                <div class="welcome-section">
                    <div class="text-center">
                        <i class="mdi mdi-cash-multiple" style="font-size: 60px; color: #10b981;"></i>
                        <h3 class="mt-3">Total Belanja</h3>
                        <h2 class="text-success">Rp {{ number_format($member->total_spent, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="welcome-section mt-4">
            <h4><i class="mdi mdi-information"></i> Informasi Member</h4>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge badge-success">{{ ucfirst($member->status) }}</span></p>
                    <p><strong>Member Sejak:</strong> {{ $member->created_at->format('d F Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> {{ $member->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Features Info -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="welcome-section text-center">
                    <i class="mdi mdi-shopping" style="font-size: 40px; color: #10b981;"></i>
                    <h5 class="mt-2">Belanja</h5>
                    <p class="text-muted">Dapatkan 1% poin dari setiap pembelian</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="welcome-section text-center">
                    <i class="mdi mdi-gift" style="font-size: 40px; color: #10b981;"></i>
                    <h5 class="mt-2">Tukar Poin</h5>
                    <p class="text-muted">Tukar poin jadi diskon belanja</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="welcome-section text-center">
                    <i class="mdi mdi-history" style="font-size: 40px; color: #10b981;"></i>
                    <h5 class="mt-2">Riwayat</h5>
                    <p class="text-muted">Lihat semua transaksi Anda</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
