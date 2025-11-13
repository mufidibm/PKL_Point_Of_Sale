<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}"
       class="brand-link">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
             alt="Logo"
             class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">POS System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}"
                     class="img-circle elevation-2"
                     alt="User">
            </div>
            <div class="info">
                <a href="#"
                   class="d-block">{{ Auth::user()->name ?? 'User' }}</a>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                {{-- DASHBOARD --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- MASTER DATA --}}
                <li
                    class="nav-item {{ request()->is('produk*', 'kategori*', 'supplier*', 'gudang*', 'karyawan*', 'pelanggan*', 'membership*', 'user*') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}"
                               class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produk.index') }}"
                               class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}"
                               class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}"
                               class="nav-link {{ request()->is('supplier*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gudang.index') }}"
                               class="nav-link {{ request()->is('gudang*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('stok.index') }}"
                               class="nav-link {{ request()->is('stok*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stok Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('karyawan.index') }}"
                               class="nav-link {{ request()->is('karyawan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Karyawan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pelanggan.index') }}"
                               class="nav-link {{ request()->is('pelanggan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pelanggan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('membership.index') }}"
                               class="nav-link {{ request()->is('membership*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Membership</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- TRANSAKSI --}}
                <li class="nav-item {{ request()->is('penjualan*', 'pembelian*') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Transaksi
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('penjualan.index') }}"
                               class="nav-link {{ request()->is('penjualan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pembelian.index') }}"
                               class="nav-link {{ request()->is('pembelian*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pembelian</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- RETUR --}}
                <li class="nav-item {{ request()->is('retur-penjualan*', 'retur-pembelian*') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link">
                        <i class="nav-icon fas fa-undo"></i>
                        <p>
                            Retur
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('retur-penjualan.index') }}"
                               class="nav-link {{ request()->is('retur-penjualan*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Retur Penjualan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('retur-pembelian.index') }}"
                               class="nav-link {{ request()->is('retur-pembelian*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Retur Pembelian</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- KASIR (POS) --}}
                <li class="nav-item">
                    <a href="{{ route('pos.index') }}"
                       class="nav-link {{ request()->is('pos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>
                            Kasir
                            <span class="right badge badge-danger">LIVE</span>
                        </p>
                    </a>
                </li>
                {{-- LAPORAN --}}
                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}"
                       class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                {{-- LOGOUT --}}
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                       class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST"
                          class="d-none">
                        @csrf
                    </form>
                </li>



            </ul>
        </nav>
    </div>
</aside>