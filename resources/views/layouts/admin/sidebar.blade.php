    <div class="nav-section-label">Menu Utama</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.pelanggan') }}"
                class="nav-link {{ request()->routeIs('admin.pelanggan') ? 'active' : '' }}">
                <i class="fa-solid fa-users me-2"></i> Data Customer
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.produk') }}"
                class="nav-link {{ request()->routeIs('admin.produk') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-packing me-2"></i> Data Produk
            </a>
        </li>

        <li class="nav-item">
            <a href="#submenuMaster" data-bs-toggle="collapse"
                class="nav-link d-flex justify-content-between align-items-center
        {{ request()->routeIs('admin.barang-masuk', 'admin.barang-keluar') ? 'active' : '' }}"
                aria-expanded="{{ request()->routeIs('admin.barang-masuk', 'admin.barang-keluar') ? 'true' : 'false' }}">

                <span>
                    <i class="fa-solid fa-cubes me-2"></i>
                    Persediaan Barang
                </span>

                <i class="fa-solid fa-chevron-down chevron small"></i>
            </a>

            <ul class="collapse list-unstyled
        {{ request()->routeIs('admin.barang-masuk', 'admin.barang-keluar') ? 'show' : '' }}"
                id="submenuMaster">
                <li>
                    <a href="{{ route('admin.barang-masuk') }}" class="nav-link text-white-50">
                        Barang Masuk
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.barang-keluar') }}" class="nav-link text-white-50">
                        Barang Keluar
                    </a>
                </li>

            </ul>
        </li>


        <li class="nav-item">
            <a href="{{ route('admin.pembelian') }}"
                class="nav-link {{ request()->routeIs('admin.pembelian') ? 'active' : '' }}">
                <i class="fa-solid fa-bag-shopping me-2"></i> Pembelian
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-chart-line me-2"></i> Laporan
                <span class="badge-count">Baru</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-gear me-2"></i> Pengaturan
            </a>
        </li>
    </ul>

    <div class="sidebar-footer mt-auto">
        <button type="button" class="btn-logout">
            <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
        </button>
    </div>
