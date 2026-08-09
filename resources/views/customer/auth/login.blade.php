@extends('layouts.app')

@section('content')

    <style>
        :root {
            --ink: #0B1F3A;
            --accent: #2196F3;
            --accent-dark: #0D47A1;
            --accent-light: #90CAF9;
            --accent-soft: #E3F2FD;
        }

        /* Sesuaikan 76px dengan tinggi navbar Anda yang sebenarnya */
        .auth-wrap {
            min-height: calc(100vh - 76px);
            background: linear-gradient(180deg,
                    #ffffff 0%,
                    var(--accent-soft) 40%,
                    var(--accent-light) 100%);
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border: 1px solid #E7ECF3;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px -20px rgba(13, 71, 161, 0.18);
        }

        .auth-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(33, 150, 243, 0.1);
            color: var(--accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--ink);
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #D8E2F0;
            padding: 0.65rem 0.9rem;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.15);
        }

        .input-group-text {
            background: #fff;
            border: 1px solid #D8E2F0;
            border-right: none;
            color: #7A8CA6;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--accent);
        }

        .btn-accent {
            background: var(--accent);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.65rem;
        }

        .btn-accent:hover {
            background: var(--accent-dark);
            color: #fff;
        }

        .divider-text {
            color: #A6B4C7;
            font-size: 0.8rem;
        }

        .divider-text::before,
        .divider-text::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #E1E8F2;
        }

        .btn-social {
            border: 1px solid #D8E2F0;
            border-radius: 10px;
            background: #fff;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-social:hover {
            background: #F0F4FA;
        }

        .link-accent {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
        }

        .link-accent:hover {
            text-decoration: underline;
            color: var(--accent-dark);
        }

        @media (max-width: 575.98px) {
            .auth-card {
                padding: 1.75rem;
                border-radius: 12px;
            }
        }
    </style>

    <div class="auth-wrap d-flex align-items-center justify-content-center p-3">
        <div class="auth-card">

            <h2 class="fw-bold mb-1" style="color: var(--ink);">Masuk ke akun Anda</h2>
            <p class="text-secondary mb-4">Silakan masukkan detail akun untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
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
                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                            required>
                        <span class="input-group-text" role="button" id="togglePassword">
                            <i class="fa-regular fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small text-secondary" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="" class="link-accent small">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn btn-accent w-100 mb-3">Masuk</button>

                <div class="d-flex align-items-center gap-3 my-4 divider-text">
                    <span>atau lanjutkan dengan</span>
                </div>

                {{-- <div class="d-flex gap-2 mb-4">
                    <button type="button" class="btn btn-social flex-fill py-2">
                        <i class="fa-brands fa-google me-1"></i> Google
                    </button>
                    <button type="button" class="btn btn-social flex-fill py-2">
                        <i class="fa-brands fa-github me-1"></i> GitHub
                    </button>
                </div> --}}

                <p class="text-center text-secondary small mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') ?? '#' }}" class="link-accent">Daftar sekarang</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('fa-eye');
                toggleIcon.classList.toggle('fa-eye-slash');
            });
        });
    </script>

@endsection
