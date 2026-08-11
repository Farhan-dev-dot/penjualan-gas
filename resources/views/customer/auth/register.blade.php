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
            max-width: 760px;
            background: #fff;
            border: 1px solid #E7ECF3;
            border-radius: 16px;
            padding: 2.5rem 3rem;
            box-shadow: 0 20px 40px -20px rgba(13, 71, 161, 0.18);
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 1.5rem;
        }

        @media (max-width: 575.98px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }
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

        textarea.form-control {
            resize: none;
        }

        .input-group-text.align-items-start {
            padding-top: 0.65rem;
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

            <h2 class="fw-bold mb-1" style="color: var(--ink);">Buat akun baru</h2>
            <p class="text-secondary mb-4">Lengkapi data di bawah untuk mendaftar.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row-2 mb-3">
                    <div>
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="nama@email.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <div class="input-group">
                        <span class="input-group-text align-items-start"><i class="fa-solid fa-location-pin"></i></span>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Alamat lengkap Anda" required>{{ old('alamat') }}</textarea>
                    </div>
                </div>

                <div class="form-row-2 mb-4">
                    <div>
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="••••••••" required>
                            <span class="input-group-text" role="button" id="togglePassword">
                                <i class="fa-regular fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                        <div class="form-text small">Minimal 8 karakter.</div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="••••••••" required>
                            <span class="input-group-text" role="button" id="togglePasswordConfirm">
                                <i class="fa-regular fa-eye" id="toggleIconConfirm"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent w-100 mb-3">Daftar</button>

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
                    Sudah punya akun?
                    <a href="{{ route('login') ?? '#' }}" class="link-accent">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupToggle(toggleId, inputId, iconId) {
                const toggleBtn = document.getElementById(toggleId);
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                toggleBtn.addEventListener('click', function() {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            setupToggle('togglePassword', 'password', 'toggleIcon');
            setupToggle('togglePasswordConfirm', 'password_confirmation', 'toggleIconConfirm');
        });
    </script>

@endsection
