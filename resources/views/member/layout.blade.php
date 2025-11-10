<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - S&Waldorf</title>
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
body{font-family:Arial,sans-serif;margin:0;background:#fff;color:#000}
.top-bar{background:#000;color:#fff;padding:0.75rem 0}
.top-bar-container{max-width:1400px;margin:0 auto;padding:0 1.5rem;display:flex;justify-content:space-between}
.top-bar a{color:#fff;text-decoration:none;padding:0.5rem;font-size:0.875rem}
.main-header{background:#fff;border-bottom:1px solid #e8e8e8;position:sticky;top:0;z-index:100}
.header-container{max-width:1400px;margin:0 auto;padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center;gap:2rem}
.logo{font-size:1.5rem;font-weight:700;color:#000;text-decoration:none;letter-spacing:2px}
.search-input{width:100%;padding:0.75rem 1rem;border:1px solid #e8e8e8;border-radius:24px;background:#f7f7f7}
.nav-menu{background:#fff;border-bottom:1px solid #e8e8e8}
.nav-container{max-width:1400px;margin:0 auto;padding:0 1.5rem;display:flex;gap:0;overflow-x:auto}
.nav-link{color:#000;text-decoration:none;padding:1rem 1.25rem;font-size:0.875rem;border-bottom:3px solid transparent}
.nav-link.active{border-bottom-color:#000;font-weight:600}
.main-content{max-width:1400px;margin:0 auto;padding:2rem 1.5rem}
.alert{padding:1rem;margin-bottom:1rem;border-radius:4px}
.alert-success{background:#d4edda;color:#155724}
.alert-danger{background:#f8d7da;color:#721c24}
    </style>
    @stack('styles')
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-container">
            <a href="{{ route('member.profile') }}"><i class="mdi mdi-account-circle"></i> Akun Saya</a>
            <div style="display:flex;gap:1rem"><a href="{{ route('member.cart.index') }}"><i class="mdi mdi-cart"></i> Tas</a></div>
        </div>
    </div>
    <header class="main-header">
        <div class="header-container">
            <a href="{{ route('member.dashboard') }}" class="logo">S&WALDORF</a>
            <div style="flex:1;max-width:600px"><input type="text" class="search-input" placeholder="Anda cari apa?"></div>
            <a href="{{ route('member.cart.index') }}" style="color:#000;font-size:1.5rem"><i class="mdi mdi-cart-outline"></i></a>
        </div>
    </header>
    <nav class="nav-menu">
        <div class="nav-container">
            <a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('member.catalog.index') }}" class="nav-link {{ request()->routeIs('member.catalog.*') ? 'active' : '' }}">Katalog</a>
            <a href="{{ route('member.cart.index') }}" class="nav-link {{ request()->routeIs('member.cart.*') ? 'active' : '' }}">Keranjang</a>
            <a href="{{ route('member.orders') }}" class="nav-link {{ request()->routeIs('member.orders*') ? 'active' : '' }}">Pesanan</a>
            <a href="{{ route('member.profile') }}" class="nav-link {{ request()->routeIs('member.profile') ? 'active' : '' }}">Profil</a>
            <form method="POST" action="{{ route('member.logout') }}" style="display:inline;margin:0">@csrf<button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer">Logout</button></form>
        </div>
    </nav>
    <main class="main-content">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @yield('content')
    </main>
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>