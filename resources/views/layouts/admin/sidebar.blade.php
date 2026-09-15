@php
    // Login admin & manager memakai satu guard ('admin');
    // prefix ditentukan dari role user yang sedang login.
    $adminUser = auth('admin')->user();
    $isManager = ($adminUser?->role ?? null) === 'manager';
    $prefix = $isManager ? 'manager' : 'admin';
@endphp

<div class="nav-section-label">Menu Utama</div>

<ul class="nav flex-column">

    {{-- Dashboard --}}
    <li class="nav-item">
        <a href="{{ route($prefix . '.dashboard') }}"
            class="nav-link {{ request()->routeIs($prefix . '.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high me-2"></i>
            Dashboard
        </a>
    </li>

    {{-- Data Pelanggan --}}
    <li class="nav-item">
        <a href="{{ route($prefix . '.pelanggan') }}"
            class="nav-link {{ request()->routeIs($prefix . '.pelanggan') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear me-2"></i>
            Data Pelanggan
        </a>
    </li>

    {{-- Data Produk --}}
    <li class="nav-item">
        <a href="{{ route($prefix . '.produk') }}"
            class="nav-link {{ request()->routeIs($prefix . '.produk') ? 'active' : '' }}">
            <i class="fa-solid fa-boxes-packing me-2"></i>
            Data Produk
        </a>
    </li>

    {{-- Persediaan Barang --}}
    <li class="nav-item">

        <a href="#submenuMaster" data-bs-toggle="collapse"
            class="nav-link d-flex justify-content-between align-items-center
            {{ request()->routeIs($prefix . '.barang-masuk', $prefix . '.barang-keluar', $prefix . '.barang-rusak')
                ? 'active'
                : '' }}"
            aria-expanded="{{ request()->routeIs($prefix . '.barang-masuk', $prefix . '.barang-keluar', $prefix . '.barang-rusak')
                ? 'true'
                : 'false' }}">

            <span>
                <i class="fa-solid fa-cubes me-2"></i>
                Persediaan Barang
            </span>

            <i class="fa-solid fa-chevron-down chevron small"></i>
        </a>

        <ul class="collapse list-unstyled
            {{ request()->routeIs($prefix . '.barang-masuk', $prefix . '.barang-keluar', $prefix . '.barang-rusak')
                ? 'show'
                : '' }}"
            id="submenuMaster">

            {{-- Barang Masuk --}}
            <li>
                <a href="{{ route($prefix . '.barang-masuk') }}"
                    class="nav-link text-white-50
                    {{ request()->routeIs($prefix . '.barang-masuk') ? 'active' : '' }}">
                    Barang Masuk
                </a>
            </li>

            {{-- Barang Keluar --}}
            <li>
                <a href="{{ route($prefix . '.barang-keluar') }}"
                    class="nav-link text-white-50
                    {{ request()->routeIs($prefix . '.barang-keluar') ? 'active' : '' }}">
                    Barang Keluar
                </a>
            </li>

            {{-- Barang Rusak --}}
            <li>
                <a href="{{ route($prefix . '.barang-rusak') }}"
                    class="nav-link text-white-50
                    {{ request()->routeIs($prefix . '.barang-rusak') ? 'active' : '' }}">
                    Barang Rusak
                </a>
            </li>

        </ul>
    </li>

    {{-- Pembelian --}}
    <li class="nav-item">
        <a href="{{ route($prefix . '.pembelian') }}"
            class="nav-link {{ request()->routeIs($prefix . '.pembelian') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping me-2"></i>
            Pembelian
        </a>
    </li>

    {{-- Kartu Stok --}}
    <li class="nav-item">
        <a href="{{ route($prefix . '.kartu-stok') }}"
            class="nav-link {{ request()->routeIs($prefix . '.kartu-stok') ? 'active' : '' }}">
            <i class="fa-solid fa-book me-2"></i>
            Kartu Stok
        </a>
    </li>

    {{-- Stok Opname --}}
    <li class="nav-item">
        <a href="{{ route($prefix . '.stok-opname') }}"
            class="nav-link {{ request()->routeIs($prefix . '.stok-opname*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-check me-2"></i>
            Stok Opname
        </a>
    </li>


    {{-- =========================================================
         KHUSUS MANAGER
         ========================================================= --}}
    @if ($isManager)
        <li class="nav-item">
            <a href="{{ route('manager.manajemen-admin') }}"
                class="nav-link {{ request()->routeIs('manager.manajemen-admin*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield me-2"></i>
                Manajemen Admin
            </a>
        </li>
    @endif


    {{-- =========================================================
         MENU TAMBAHAN
         ========================================================= --}}

    {{--
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="fa-solid fa-chart-line me-2"></i>
            Laporan
            <span class="badge-count">Baru</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="fa-solid fa-gear me-2"></i>
            Pengaturan
        </a>
    </li>
    --}}

</ul>


{{-- =============================================================
     LOGOUT
     ============================================================= --}}

@php
    // Logout dikelola satu endpoint (admin.logout) untuk semua role.
    $logoutRoute = 'admin.logout';
@endphp

<div class="sidebar-footer mt-auto">

    <form action="{{ route($logoutRoute) }}" method="POST"
        onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">

        @csrf

        <button type="submit" class="btn-logout">
            <i class="fa-solid fa-right-from-bracket me-1"></i>
            Logout
        </button>

    </form>

</div>
