<aside class="left-sidebar" data-sidebarbg="skin5">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li class="sidebar-item" style="margin-bottom: 20px;">
                    <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #4a90e2, #5ba3ff); display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px; margin-right: 12px; color: #ffffff;">
                            @auth
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @else
                                G
                            @endauth
                        </div>
                        <span class="hide-menu">
                            @auth
                                {{ Auth::user()->name }}
                            @else
                                Guest
                            @endauth
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <i class="ti-user"></i>
                                <span class="hide-menu"> My Profile </span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="javascript:void(0)" class="sidebar-link">
                                <i class="ti-settings"></i>
                                <span class="hide-menu"> Settings </span>
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
                <!-- Admin Menu -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Master Data</span>
                </li>

                <!-- User Management -->
                <li class="sidebar-item {{ Request::is('user*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/" aria-expanded="false">
                        <i class="ti-user"></i>
                        <span class="hide-menu">Users</span>
                    </a>
                </li>

                <!-- Jenis Produk -->
                <li class="sidebar-item {{ Request::is('jenis*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/}" aria-expanded="false">
                        <i class="ti-tag"></i>
                        <span class="hide-menu">Categories</span>
                    </a>
                </li>

                <!-- Produk -->
                <li class="sidebar-item {{ Request::is('produk*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/}}" aria-expanded="false">
                        <i class="ti-package"></i>
                        <span class="hide-menu">Products</span>
                    </a>
                </li>

                <!-- Transaksi -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Transactions</span>
                </li>

                <!-- Penjualan -->
                <li class="sidebar-item {{ Request::is('penjualan*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/') }}" aria-expanded="false">
                        <i class="ti-shopping-cart"></i>
                        <span class="hide-menu">Sales</span>
                    </a>
                </li>

                <!-- Laporan -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Reports</span>
                </li>

                <!-- Laporan Penjualan -->
                <li class="sidebar-item {{ Request::is('laporan*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/ }}" aria-expanded="false">
                        <i class="ti-clipboard"></i>
                        <span class="hide-menu">Sales Report</span>
                    </a>
                </li>

                @else
                <!-- Customer Menu -->
                <li class="nav-small-cap">
                    <span class="hide-menu">Shopping</span>
                </li>

                <!-- Produk -->
                <li class="sidebar-item {{ Request::is('produk*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/}}" aria-expanded="false">
                        <i class="ti-package"></i>
                        <span class="hide-menu">Products</span>
                    </a>
                </li>

                <!-- Keranjang -->
                <li class="sidebar-item {{ Request::is('keranjang*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/') }}" aria-expanded="false">
                        <i class="ti-shopping-cart"></i>
                        <span class="hide-menu">Cart
                            @php $count = count(session('keranjang', [])); @endphp
                            @if($count > 0)
                                <span class="badge badge-success" style="margin-left: 8px; padding: 4px 8px; border-radius: 12px; font-size: 10px;">{{ $count }}</span>
                            @endif
                        </span>
                    </a>
                </li>

                <!-- Riwayat Transaksi -->
                <li class="sidebar-item {{ Request::is('transaksi*') ? 'selected' : '' }}">
                    <a class="sidebar-link waves-effect waves-dark" href="/at') }}" aria-expanded="false">
                        <i class="ti-receipt"></i>
                        <span class="hide-menu">History</span>
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
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

<!-- Sidebar Dark Theme CSS -->
<style>
    /* Sidebar - Dark Theme (Hitam Pekat) */
    .left-sidebar {
        background: #000000;
        border-right: 1px solid #1a1a1a;
        box-shadow: 2px 0 15px rgba(0,0,0,0.8);
    }

    .sidebar-nav ul .sidebar-item .sidebar-link {
        color: #ffffff;
        padding: 14px 20px;
        border-radius: 8px;
        margin: 4px 10px;
        transition: all 0.3s ease;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link:hover {
        background: #1a1a1a;
        color: #ffffff;
        transform: translateX(4px);
    }

    .sidebar-nav ul .sidebar-item.selected .sidebar-link,
    .sidebar-nav ul .sidebar-item .sidebar-link.active {
        background: linear-gradient(135deg, #4a90e2, #5ba3ff);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(74, 144, 226, 0.4);
    }

    .sidebar-nav .nav-small-cap {
        color: #8a8a8a;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 20px 20px 10px;
        margin-top: 10px;
    }

    .sidebar-nav ul .sidebar-item .sidebar-link i {
        color: #ffffff;
        margin-right: 10px;
        font-size: 18px;
        width: 20px;
        text-align: center;
    }

    .sidebar-nav ul .sidebar-item.selected .sidebar-link i,
    .sidebar-nav ul .sidebar-item .sidebar-link:hover i {
        color: #ffffff;
    }

    /* Submenu styling */
    .sidebar-nav ul .first-level {
        padding-left: 20px;
    }

    .sidebar-nav ul .first-level .sidebar-item .sidebar-link {
        padding: 10px 15px;
        font-size: 13px;
    }

    /* Badge styling */
    .badge {
        background: #10b981;
        color: #ffffff;
    }

    /* Scrollbar */
    .scroll-sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .scroll-sidebar::-webkit-scrollbar-track {
        background: #000000;
    }

    .scroll-sidebar::-webkit-scrollbar-thumb {
        background: #1a1a1a;
        border-radius: 3px;
    }

    .scroll-sidebar::-webkit-scrollbar-thumb:hover {
        background: #4a90e2;
    }

    /* Hide-menu text color */
    .sidebar-nav .hide-menu {
        color: #ffffff;
    }
</style>
