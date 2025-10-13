<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Area') - S&Waldorf</title>
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background:#fff; color:#111 }
        .layout { display:flex; min-height:100vh }
        .sidebar { width:260px; background:#0a0a0a; color:#f5f5f5; position:sticky; top:0; height:100vh; display:flex; flex-direction:column }
        .brand { padding:16px; border-bottom:1px solid rgba(255,255,255,0.08) }
        .brand .profile { display:flex; align-items:center; gap:12px }
        .avatar { width:40px; height:40px; border-radius:50%; object-fit:cover; background:#111; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700 }
        .menu { padding:8px 8px; flex:1 }
        .menu a { display:flex; align-items:center; gap:10px; color:#e5e5e5; text-decoration:none; padding:10px 12px; border-radius:8px; margin:4px 6px }
        .menu a:hover, .menu a.active { background:#111; color:#fff }
        .footer { padding:12px 10px 16px; border-top:1px solid rgba(255,255,255,0.08) }
        .btn-logout { background:#111; color:#fff; border:1px solid #111; padding:8px 16px; border-radius:8px; font-weight:600 }
        .content { flex:1; padding:24px }
        @media (max-width:992px){ .sidebar{ position:fixed; transform:translateX(-100%); transition:transform .2s; z-index:1030 } .sidebar.open{ transform:translateX(0) } .content{ padding:16px } }
    </style>
    @stack('styles')
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <div class="brand">
            <div class="profile">
                @php($m = Auth::guard('member')->user())
                @if($m && $m->photo)
                    <img src="{{ asset('storage/' . $m->photo) }}" class="avatar" alt="{{ $m->nama_member }}"/>
                @else
                    <div class="avatar">{{ $m ? strtoupper(substr($m->nama_member,0,1)) : 'M' }}</div>
                @endif
                <div>
                    <div style="font-weight:800">S&WALDORF</div>
                    <small style="color:#bbb">Member</small>
                </div>
            </div>
        </div>
        <div class="menu">
            <a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? 'active' : '' }}"><i class="mdi mdi-view-dashboard"></i> <span>Dashboard</span></a>
            <a href="{{ route('member.catalog.index') }}" class="{{ request()->routeIs('member.catalog.*') ? 'active' : '' }}"><i class="mdi mdi-store"></i> <span>Katalog</span></a>
            <a href="{{ route('member.cart.index') }}" class="{{ request()->routeIs('member.cart.*') ? 'active' : '' }}"><i class="mdi mdi-cart"></i> <span>Keranjang</span></a>
            <a href="{{ route('member.orders') }}" class="{{ request()->routeIs('member.orders*') ? 'active' : '' }}"><i class="mdi mdi-history"></i> <span>Pesanan</span></a>
            <a href="{{ route('member.profile') }}" class="{{ request()->routeIs('member.profile') ? 'active' : '' }}"><i class="mdi mdi-account"></i> <span>Profil</span></a>
        </div>
        <div class="footer">
            <form method="POST" action="{{ route('member.logout') }}" class="d-grid">
                @csrf
                <button type="submit" class="btn-logout w-100"><i class="mdi mdi-logout"></i> Logout</button>
            </form>
        </div>
    </aside>
    <main class="content container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
