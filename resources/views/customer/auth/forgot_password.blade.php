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

        .link-accent {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
        }

        .link-accent:hover {
            text-decoration: underline;
            color: var(--accent-dark);
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
            <h2 class="fw-bold mb-1" style="color:var(--ink);">Lupa kata sandi?</h2>
            <p class="text-secondary mb-4">Masukkan email Anda, kami akan kirim link untuk reset password.</p>

            @if (session('status'))
                <div class="alert alert-success py-2 small">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- {{ route('password.email') }} --}}
            <form method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent w-100 mb-3">Kirim Link Reset</button>

                <p class="text-center text-secondary small mb-0">
                    Ingat password Anda? <a href="{{ route('login') }}" class="link-accent">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>
@endsection
