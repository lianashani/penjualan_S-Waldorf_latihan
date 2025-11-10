win<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li class="sidebar-item" style="margin-bottom: 20px;">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #2c2c2c, #404040); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; margin-right: 12px; color: #ffffff; border: 2px solid #ffffff;">
                            @auth
                                {{ strtoupper(substr(Auth::user()->nama_user, 0, 1)) }}
                            @else
                                G
                            @endauth
                        </div>
                        <span class="hide-menu">
                            @auth
                                {{ Auth::user()->nama_user }}
                                <br>
                                <small style="color: #999; font-size: 11px;">
                                    {{ ucfirst(Auth::user()->role) }}
                                </small>
                            @else
                                Guest
                            @endauth
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('profile') }}" class="sidebar-link">
                                <i class="ti-user"></i>
                                <span class="hide-menu"> My Profile </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('change-password') }}" class="sidebar-link">
                                <i class="ti-lock"></i>
                                <span class="hide-menu"> Change Password </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" id="sidebarLogout" class="sidebar-link">
                                <i class="ti-power-off"></i>
                                <span class="hide-menu"> Logout </span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Divider -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Main Menu</span>
                </li>

                <!-- Dashboard -->
                <li class="sidebar-item {{ Request::is('/') || Request::is('dashboard') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ url('/') }}" aria-expanded="false">
                        <i class="ti-home"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                @auth
                @if(Auth::user()->role == 'admin')
                <!-- ADMIN MENU -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Master Data</span>
                </li>

                <!-- User Management -->
                <li class="sidebar-item {{ Request::is('user*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('user.index') }}" aria-expanded="false">
                        <i class="mdi mdi-account-key"></i>
                        <span class="hide-menu">Kelola User</span>
                    </a>
                </li>

                <!-- Kategori -->
                <li class="sidebar-item {{ Request::is('kategori*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('kategori.index') }}" aria-expanded="false">
                        <i class="mdi mdi-package"></i>
                        <span class="hide-menu">Kategori</span>
                    </a>
                </li>

                <!-- Produk -->
                <li class="sidebar-item {{ Request::is('produk*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('produk.index') }}" aria-expanded="false">
                        <i class="mdi mdi-package-variant"></i>
                        <span class="hide-menu">Produk</span>
                    </a>
                </li>

                <!-- Pelanggan -->
                <li class="sidebar-item {{ Request::is('pelanggan*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('pelanggan.index') }}" aria-expanded="false">
                        <i class="mdi mdi-account-multiple"></i>
                        <span class="hide-menu">Membership</span>
                    </a>
                </li>

                <!-- Promo -->
                <li class="sidebar-item {{ Request::is('promo*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('promo.index') }}" aria-expanded="false">
                        <i class="mdi mdi-sale"></i>
                        <span class="hide-menu">Promo Diskon</span>
                    </a>
                </li>

                <!-- Katalog -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Katalog</span>
                </li>

                <!-- Katalog Produk -->
                <li class="sidebar-item {{ Request::is('katalog*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('katalog.index') }}" aria-expanded="false">
                        <i class="mdi mdi-view-grid"></i>
                        <span class="hide-menu">Katalog Produk</span>
                    </a>
                </li>

                <!-- Katalog Elegan -->
                <li class="sidebar-item {{ Request::is('katalog-elegant*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('katalog.elegant') }}" aria-expanded="false">
                        <i class="mdi mdi-store"></i>
                        <span class="hide-menu">Katalog view</span>
                    </a>
                </li>

                <!-- Antrian Order (Admin) -->
                @if (Route::has('admin.member-orders.index'))
                @php($pendingCountAdmin = \App\Models\MemberOrder::where('status','awaiting_preparation')->count())
                <li class="sidebar-item {{ request()->routeIs('admin.member-orders.*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark d-flex justify-content-between align-items-center" href="{{ route('admin.member-orders.index') }}" aria-expanded="false">
                        <span><i class="mdi mdi-clipboard-list"></i> <span class="hide-menu">Antrian Order</span></span>
                        @if($pendingCountAdmin > 0)
                            <span class="badge badge-danger">{{ $pendingCountAdmin }}</span>
                        @endif
                    </a>
                </li>
                @endif

                <!-- Laporan -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Laporan</span>
                </li>

                <!-- Laporan Penjualan -->
                <li class="sidebar-item {{ Request::is('laporan*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('laporan.index') }}" aria-expanded="false">
                        <i class="mdi mdi-file-chart"></i>
                        <span class="hide-menu">Laporan Penjualan</span>
                    </a>
                </li>

                {{-- <!-- Rating Management -->
                <li class="sidebar-item {{ Request::is('admin/ratings*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('ratings.index') }}" aria-expanded="false">
                        <i class="mdi mdi-star"></i>
                        <span class="hide-menu">Kelola Rating</span>
                    </a>
                </li> --}}

                @else
                <!-- KASIR MENU -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Transaksi</span>

                </li>


                <!-- Penjualan -->
                <li class="sidebar-item {{ Request::is('penjualan*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('penjualan.index') }}" aria-expanded="false">
                        <i class="mdi mdi-cart"></i>
                        <span class="hide-menu">Transaksi Penjualan</span>
                    </a>
                </li>

                <!-- Antrian Order (Kasir) -->
                @if (Route::has('kasir.member-orders.index'))
                @php($pendingCountKasir = \App\Models\MemberOrder::where('status','awaiting_preparation')->count())
                <li class="sidebar-item {{ request()->routeIs('kasir.member-orders.*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark d-flex justify-content-between align-items-center" href="{{ route('kasir.member-orders.index') }}" aria-expanded="false">
                        <span><i class="mdi mdi-clipboard"></i> <span class="hide-menu">Antrian Order</span></span>
                        @if($pendingCountKasir > 0)
                            <span class="badge badge-danger">{{ $pendingCountKasir }}</span>
                        @endif
                    </a>
                </li>
                @endif

                <!-- Chat Member (Kasir) -->
                @if (Route::has('kasir.chat.index'))
                @php($unreadCountKasir = \App\Models\MemberChat::where('sender_type','member')->where('is_read',0)->count())
                <li class="sidebar-item {{ request()->routeIs('kasir.chat.*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark d-flex justify-content-between align-items-center" href="{{ route('kasir.chat.index') }}" aria-expanded="false">
                        <span><i class="mdi mdi-message"></i> <span class="hide-menu">Chat Member</span></span>
                        @if($unreadCountKasir > 0)
                            <span class="badge badge-info">{{ $unreadCountKasir }}</span>
                        @endif
                    </a>
                </li>
                @endif

                <!-- Katalog -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Katalog</span>
                </li>

                <!-- Katalog Produk -->
                <li class="sidebar-item {{ Request::is('katalog*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('katalog.index') }}" aria-expanded="false">
                        <i class="mdi mdi-view-grid"></i>
                        <span class="hide-menu">Katalog Produk</span>
                    </a>
                </li>

                <!-- Katalog Elegan -->
                <li class="sidebar-item {{ Request::is('katalog-elegant*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="{{ route('katalog.elegant') }}" aria-expanded="false">
                        <i class="mdi mdi-store"></i>
                        <span class="hide-menu">Katalog Elegan</span>
                    </a>
                </li>
                @endif
                @endauth

                <!-- Logout -->
                <li class="nav-small-cap" style="margin-top: 20px;">
                    <span class="hide-menu">System</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark" href="javascript:void(0)" id="sidebarLogoutBottom" aria-expanded="false">
                        <i class="ti-power-off"></i>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
                <!-- Logout Form (Hidden) -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

<style>
    /* Sidebar Black Theme - Specific Only */
    aside.left-sidebar {
        background: #000000 !important;
    }

    aside.left-sidebar .scroll-sidebar {
        background: #000000 !important;
    }

    aside.left-sidebar .sidebar-nav {
        background: #000000 !important;
    }

    aside.left-sidebar .sidebar-nav ul {
        background: #000000 !important;
    }

    aside.left-sidebar .sidebar-link,
    aside.left-sidebar .sidebar-link span,
    aside.left-sidebar .hide-menu {
        color: #ffffff !important;
    }

    aside.left-sidebar .sidebar-link i {
        color: #ffffff !important;
    }

    aside.left-sidebar .sidebar-link:hover {
        background: #1a1a1a !important;
        color: #ffffff !important;
    }

    aside.left-sidebar .sidebar-item.selected > .sidebar-link,
    aside.left-sidebar .sidebar-link.active {
        background: #2c2c2c !important;
        border-left: 3px solid #ffffff;
        color: #ffffff !important;
    }

    aside.left-sidebar .nav-small-cap,
    aside.left-sidebar .nav-small-cap span,
    aside.left-sidebar .nav-small-cap .hide-menu {
        color: #ffffff !important;
        opacity: 0.7;
    }

    aside.left-sidebar .user-profile {
        background: #000000 !important;
        border-bottom: 1px solid #1a1a1a;
    }

    aside.left-sidebar .profile-text,
    aside.left-sidebar .profile-text a,
    aside.left-sidebar .profile-text span,
    aside.left-sidebar .user-profile a {
        color: #ffffff !important;
    }

    /* First level menu */
    aside.left-sidebar .first-level .sidebar-link {
        color: #ffffff !important;
    }
</style>
