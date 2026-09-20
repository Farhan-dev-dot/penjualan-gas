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
                                                <span class="input-group-text" role="button" style="cursor:pointer"
                                                    id="togglePassword" aria-label="Tampilkan password">
                                                    {{-- Ikon WAJIB dibungkus <span>. Script Font Awesome Kit
                                                         mengganti <i> menjadi <svg> dan MENYALIN seluruh class
                                                         dari <i> ke <svg>, termasuk d-none. Karena <i> tidak bisa
                                                         dikembalikan lagi oleh JS kita, class d-none tidak akan
                                                         pernah bisa dilepas -> ikon tidak mau berubah.
                                                         Dengan pembungkus <span>, class d-none dipasang di <span>
                                                         milik kita sendiri, jadi aman untuk di-add/remove. --}}
                                                    <span class="toggle-icon d-none" id="iconPasswordShow"><i
                                                            class="fa-solid fa-eye"></i></span>
                                                    <span class="toggle-icon" id="iconPasswordHide"><i
                                                            class="fa-solid fa-eye-slash"></i></span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                                <input type="password" id="password_confirmation"
                                                    name="password_confirmation" required minlength="8"
                                                    class="form-control">
                                                <span class="input-group-text" role="button" style="cursor:pointer"
                                                    id="togglePasswordConfirmation"
                                                    aria-label="Tampilkan konfirmasi password">
                                                    {{-- Sama seperti kolom Password: pembungkus <span> supaya
                                                         class d-none aman dari proses i->svg milik FA Kit. --}}
                                                    <span class="toggle-icon d-none" id="iconPasswordConfirmationShow"><i
                                                            class="fa-solid fa-eye"></i></span>
                                                    <span class="toggle-icon" id="iconPasswordConfirmationHide"><i
                                                            class="fa-solid fa-eye-slash"></i></span>
                                                </span>
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

    <script>
        function setupPasswordToggle(toggleId, inputId, showIconId, hideIconId) {
            const toggleBtn = document.getElementById(toggleId);
            const passwordInput = document.getElementById(inputId);
            const showIcon = document.getElementById(showIconId); // fa-eye
            const hideIcon = document.getElementById(hideIconId); // fa-eye-slash

            if (!toggleBtn || !passwordInput || !showIcon || !hideIcon) return;
            if (toggleBtn.dataset.bound === 'true') return; // cegah listener dobel
            toggleBtn.dataset.bound = 'true';

            // Tampilkan/sembunyikan ikon secara EKSPLISIT (add/remove),
            // jangan pakai toggle(class, force).
            //
            // Soalnya: showIcon dan hideIcon itu DUA elemen berbeda yang kebetulan
            // sama-sama punya class dasar "fa-solid fa-eye...". classList.toggle()
            // mencari class di elemen ITU SENDIRI, jadi:
            //   - hideIcon.toggle('d-none', false) tidak menghapus apa pun
            //     (di elemen hideIcon memang tidak ada 'd-none'), lalu karena
            //     force=false class itu DITAMBAHKAN — ikon eye-slash jadi ikut
            //     tersembunyi selamanya.
            //   - showIcon.toggle('d-none', true) -> ikon eye ikut disembunyikan,
            //     hasilnya kedua ikon hilang dan tidak ada yang berubah.
            // Dengan add/remove eksplisit, hasilnya pasti benar.
            const render = (visible) => {
                showIcon.classList.remove('d-none'); // mata terbuka (password tersembunyi)
                hideIcon.classList.add('d-none');

                if (visible) {
                    showIcon.classList.add('d-none');
                    hideIcon.classList.remove('d-none');
                }
            };

            render(passwordInput.type !== 'password');

            toggleBtn.addEventListener('click', function() {
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';

                // Password baru saja dibuka -> tampilkan mata tercoret.
                // Password baru saja disembunyikan -> tampilkan mata terbuka.
                render(!isHidden);

                this.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupPasswordToggle('togglePassword', 'password', 'iconPasswordShow', 'iconPasswordHide');
            setupPasswordToggle('togglePasswordConfirmation', 'password_confirmation',
                'iconPasswordConfirmationShow', 'iconPasswordConfirmationHide');
        });
    </script>
@endsection
