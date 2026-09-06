@extends('layouts.customer.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h1 class="h4 fw-bold text-center mb-2">Verifikasi Email</h1>
                        <p class="small text-muted text-center mb-4">
                            Kami sudah mengirim kode verifikasi 6 digit ke
                            <span class="fw-medium text-body">{{ $email }}</span>.
                            Masukkan kode tersebut untuk menyelesaikan pendaftaran.
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success py-2 small" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger py-2" role="alert">
                                <ul class="mb-0 small ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.otp.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="form-label">Kode Verifikasi</label>
                                <input type="text" id="code" name="code" inputmode="numeric" pattern="[0-9]*"
                                    maxlength="6" required autofocus
                                    class="form-control text-center fs-4 fw-semibold @error('code') is-invalid @enderror"
                                    style="letter-spacing: 0.5rem;" placeholder="------">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                Verifikasi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('register.otp.resend') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link w-100 small text-decoration-none">
                                Kirim ulang kode
                            </button>
                        </form>

                        <p class="text-center text-muted mt-2 mb-0" style="font-size: 0.75rem;">
                            Kode berlaku selama 5 menit.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
