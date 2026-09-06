<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-3">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('/') }}">
            <span class="logo-wrap">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            </span>

            <span class="brand-text">
                <span class="brand-line1">BBB</span>
                <span class="brand-line2">PT Berkat Bidara Berwibawa</span>
            </span>
        </a>

        <!-- Mobile Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarScroll">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarScroll">

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <li class="nav-item mx-lg-2">
                    <a class="nav-link {{ request()->routeIs('/') ? 'active' : '' }}" href="{{ route('/') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item mx-lg-2">
                    <a class="nav-link {{ request()->routeIs('produk.index') ? 'active' : '' }}"
                        href="{{ route('produk.index') }}">
                        Produk
                    </a>
                </li>

                <li class="nav-item mx-lg-2">
                    <a class="nav-link {{ request()->routeIs('tentang.index') ? 'active' : '' }}"
                        href="{{ route('tentang.index') }}">
                        Tentang Kami
                    </a>
                </li>

                @auth('web')
                    <li class="nav-item mx-lg-2">
                        <a class="nav-link {{ request()->routeIs('user.pesanan*') ? 'active' : '' }}"
                            href="{{ route('user.pesanan') }}">
                            Pesanan Saya
                        </a>
                    </li>
                @endauth

                {{-- <li class="nav-item mx-lg-2">
                    <a class="nav-link {{ request()->routeIs('kontak.index') ? 'active' : '' }}" href="{{ route('kontak.index') }}">
                        Kontak Kami
                    </a>
                </li> --}}

            </ul>

            <!-- Button -->
            <div class="d-flex gap-3 ms-lg-4">

                @auth
                    @unless (request()->routeIs('user.checkout'))
                        {{-- Cart --}}
                        <a href="" class="nav-login-icon position-relative" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#cartOffcanvas" aria-controls="staticBackdrop">
                            <i class="fa-solid fa-cart-shopping fs-4"></i>

                            <span
                                class="cart-nav-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </a>
                    @endunless
                    <div class="dropdown">
                        @php($user = auth()->user())
                        <button class="user-chip" type="button" data-bs-toggle="dropdown">
                            <span class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            {{-- <span class="d-none d-md-block text-start">
                                <span class="user-name d-block">{{ $user->name }}</span>
                                <span class="user-role">{{ ucfirst($user->role ?? 'user') }}</span>
                            </span> --}}
                            <i class="fa-solid fa-chevron-down small text-muted d-none d-md-inline"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 220px;">
                            <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i
                                        class="fa-regular fa-user"></i>Profil</a></li>
                            {{-- <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear"></i>Pengaturan</a></li> --}}
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST"
                                    onclick="return confirm('Apakah Anda yakin ingin keluar?');">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100">
                                        <i class="fa-solid fa-right-from-bracket"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    {{-- Login --}}
                    <a href="{{ route('login') }}" class="nav-login-icon">
                        <i class="fa-regular fa-circle-user fs-3"></i>
                    </a>
                @endauth

            </div>

        </div>
    </div>
</nav>
