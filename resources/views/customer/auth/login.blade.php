@extends('layouts.customer.app')

@section('content')
    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9 col-xl-8">
                    <div class="card auth-card">
                        <div class="row g-0">
                            <div class="col-md-5 auth-side">
                                <h2>Selamat Datang Kembali</h2>
                                <p>Masuk untuk melanjutkan transaksi dan memantau pesanan gas Anda.</p>
                            </div>

                            <div class="col-md-7 auth-body">
                                <h1 class="h4 auth-title">Masuk ke Akun</h1>

                                @if (session('status'))
                                    <div class="alert alert-success py-2 small">{{ session('status') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger py-2">
                                        <ul class="mb-0 small ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                required autofocus
                                                class="form-control @error('email') is-invalid @enderror">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                            <input type="password" id="password" name="password" required
                                                class="form-control @error('password') is-invalid @enderror">
                                            <span class="input-group-text" role="button" style="cursor:pointer"
                                                id="togglePassword" aria-label="Tampilkan password">
                                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label small" for="remember">
                                                Ingat saya
                                            </label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="forgot-link">
                                            Lupa password?
                                        </a>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        Masuk
                                    </button>
                                </form>

                                <div class="auth-divider">
                                    <hr><span>ATAU</span>
                                    <hr>
                                </div>

                                <a href="{{ route('auth.google') }}"
                                    class="btn btn-google w-100 d-flex align-items-center justify-content-center gap-2">
                                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"
                                        width="18" height="18">
                                    Masuk dengan Google
                                </a>

                                <p class="text-center auth-footer-link mt-4 mb-0">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}">Daftar di sini</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !isHidden);
            icon.classList.toggle('fa-eye-slash', isHidden);

            this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
@endsection
