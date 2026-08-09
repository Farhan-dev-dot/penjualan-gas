<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-3">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('/') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" width="45" height="45" class="me-2">

            <div class="lh-sm">
                <span class="text-dark">Berdiri</span><br>
                <span class="text-orange">Berkat Berwibawa</span>
            </div>
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
                    <a class="nav-link active" href="{{ route('/') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item mx-lg-2">
                    <a class="nav-link" href="{{ route('produk.index') }}">
                        Produk
                    </a>
                </li>

                <li class="nav-item mx-lg-2">
                    <a class="nav-link" href="{{ route('tentang.index') }}">
                        Tentang Kami
                    </a>
                </li>

                {{-- <li class="nav-item mx-lg-2">
                    <a class="nav-link" href="{{ route('kontak.index') }}">
                        Kontak Kami
                    </a>
                </li> --}}

            </ul>

            <!-- Button -->
            <div class="d-flex gap-2 ms-lg-4">

                <a href="{{ asset('login') }}" class="nav-login-icon">
                    <i class="fa-regular fa-circle-user fs-3"></i>
                </a>

            </div>

        </div>
    </div>
</nav>
