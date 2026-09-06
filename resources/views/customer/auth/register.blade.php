@extends('layouts.customer.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9">
                    <div class="card auth-card">
                        <div class="row g-0">
                            <div class="col-md-5 auth-side">
                                <h2>Bergabung Bersama Kami</h2>
                                <p>Daftar sekarang dan nikmati kemudahan pemesanan gas berkualitas untuk kebutuhan Anda.</p>
                            </div>

                            <div class="col-md-7 auth-body">
                                <h1 class="h4 auth-title">Daftar Akun Baru</h1>

                                @if ($errors->any())
                                    <div class="alert alert-danger py-2">
                                        <ul class="mb-0 small ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register.store') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                                required class="form-control @error('name') is-invalid @enderror">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                required class="form-control @error('email') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telepon" class="form-label">Nomor Telepon</label>

                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>

                                            <input type="tel" id="telepon" name="telepon" value="{{ old('telepon') }}"
                                                required class="form-control @error('telepon') is-invalid @enderror"
                                                placeholder="08xxxxxxxxxx">
                                        </div>

                                        @error('telepon')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}"
                                                required class="form-control @error('alamat') is-invalid @enderror">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                <input type="password" id="password" name="password" required
                                                    minlength="8"
                                                    class="form-control @error('password') is-invalid @enderror">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" required minlength="8"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-brand w-100 mt-2">
                                        Daftar
                                    </button>
                                </form>

                                <p class="text-center auth-footer-link mt-4 mb-0">
                                    Sudah punya akun?
                                    <a href="{{ route('login') }}">Masuk di sini</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
