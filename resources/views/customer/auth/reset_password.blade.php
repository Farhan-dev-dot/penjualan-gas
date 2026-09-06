@extends('layouts.customer.app')

@section('content')
    <style>
        :root {
            --ink: #0B1F3A;
            --accent: #2196F3;
            --accent-dark: #0D47A1;
            --accent-light: #90CAF9;
            --accent-soft: #E3F2FD;
        }

        .auth-wrap {
            min-height: calc(100vh - 76px);
            background: linear-gradient(180deg, #fff 0%, var(--accent-soft) 40%, var(--accent-light) 100%);
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            border: 1px solid #E7ECF3;
            border-radius: 16px;
            padding: 2.5rem 3rem;
            box-shadow: 0 20px 40px -20px rgba(13, 71, 161, .18);
        }

        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: var(--ink);
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #D8E2F0;
            padding: .65rem .9rem;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .15);
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
            padding: .65rem;
        }

        .btn-accent:hover {
            background: var(--accent-dark);
            color: #fff;
        }

        @media (max-width:575.98px) {
            .auth-card {
                padding: 1.75rem;
                border-radius: 12px;
            }
        }
    </style>

    <div class="auth-wrap d-flex align-items-center justify-content-center p-3">
        <div class="auth-card">
            <h2 class="fw-bold mb-1" style="color:var(--ink);">Buat kata sandi baru</h2>
            <p class="text-secondary mb-4">Masukkan password baru untuk akun Anda.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $email) }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi Baru</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••"
                            required>
                    </div>
                    <div class="form-text small">Minimal 8 karakter.</div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-lock"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent w-100">Reset Password</button>
            </form>
        </div>
    </div>
@endsection
