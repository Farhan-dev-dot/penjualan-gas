@extends('layouts.customer.app')

@section('content')
    <main class="py-5" style="min-height: 100vh;">
        <div class="container" style="max-width: 960px;">

            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Profil Saya</h2>
                <p class="text-secondary mb-0">Kelola informasi akun dan data verifikasi kamu.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- ============ HEADER PROFIL ============ --}}
            <div class="profile-header-card mb-4">
                <div class="profile-header-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="profile-header-info">
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <div class="text-secondary small mb-2">{{ $user->email }}</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="profile-badge profile-badge-role">
                            <i class="fa-solid fa-user-shield"></i>
                            {{ ucfirst($user->role ?? 'customer') }}
                        </span>

                        @php
                            $status = strtolower($user->status ?? '');
                            $statusMap = [
                                'aktif' => ['label' => 'Aktif', 'class' => 'is-aktif'],
                                'active' => ['label' => 'Aktif', 'class' => 'is-aktif'],
                                'pending' => ['label' => 'Menunggu Verifikasi', 'class' => 'is-pending'],
                                'nonaktif' => ['label' => 'Nonaktif', 'class' => 'is-nonaktif'],
                                'inactive' => ['label' => 'Nonaktif', 'class' => 'is-nonaktif'],
                                'ditolak' => ['label' => 'Ditolak', 'class' => 'is-ditolak'],
                            ];
                            $statusInfo = $statusMap[$status] ?? [
                                'label' => ucfirst($user->status ?? '-'),
                                'class' => 'is-pending',
                            ];
                        @endphp
                        <span class="profile-badge {{ $statusInfo['class'] }}">
                            <i class="fa-solid fa-circle" style="font-size: 6px;"></i>
                            {{ $statusInfo['label'] }}
                        </span>

                        @if ($user->google_id)
                            <span class="profile-badge profile-badge-google">
                                <i class="fa-brands fa-google"></i>
                                Terhubung Google
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- ============ INFORMASI AKUN ============ --}}
                <div class="col-12 col-lg-7">
                    <div class="profile-section-card h-100">
                        <h5 class="profile-section-title">
                            <i class="fa-solid fa-id-card"></i> Informasi Akun
                        </h5>

                        <form action="{{ route('user.profil.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" {{ $user->google_id ? 'readonly' : '' }}
                                    required>
                                @if ($user->google_id)
                                    <div class="form-text">
                                        <i class="fa-brands fa-google"></i> Email dikelola lewat akun Google, tidak bisa
                                        diubah di sini.
                                    </div>
                                @endif
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="telepon" class="form-label">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                    <input type="text" name="telepon" id="telepon" class="form-control"
                                        value="{{ old('telepon', $user->telepon) }}" placeholder="08xxxxxxxxxx">
                                </div>
                                @error('telepon')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3" class="form-control"
                                    placeholder="Alamat lengkap untuk pengiriman">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-brand px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ============ FOTO KTP ============ --}}
                <div class="col-12 col-lg-5">
                    <div class="profile-section-card h-100">
                        <h5 class="profile-section-title">
                            <i class="fa-solid fa-address-card"></i> Foto KTP
                        </h5>
                        <p class="text-secondary small mb-3">
                            Dipakai untuk verifikasi identitas sebelum sewa tabung disetujui.
                        </p>

                        <form action="{{ route('user.profil.ktp.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="profile-ktp-preview mb-3">
                                @if ($user->foto_ktp)
                                    <img src="{{ asset('storage/' . $user->foto_ktp) }}" alt="Foto KTP"
                                        id="ktp-preview-img">
                                @else
                                    <div class="profile-ktp-empty" id="ktp-preview-empty">
                                        <i class="fa-solid fa-image fa-2x mb-2"></i>
                                        <span>Belum ada foto KTP</span>
                                    </div>
                                    <img src="" alt="Foto KTP" id="ktp-preview-img" class="d-none">
                                @endif
                            </div>

                            <input type="file" name="foto_ktp" id="foto_ktp" class="form-control mb-3"
                                accept="image/png,image/jpeg,image/jpg" onchange="previewKtp(event)">
                            @error('foto_ktp')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn btn-cart-outline w-100">
                                <i class="fa-solid fa-upload me-1"></i>
                                {{ $user->foto_ktp ? 'Ganti Foto KTP' : 'Unggah Foto KTP' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- ============ KEAMANAN ============ --}}
                @unless ($user->google_id)
                    <div class="col-12">
                        <div class="profile-section-card">
                            <h5 class="profile-section-title">
                                <i class="fa-solid fa-lock"></i> Keamanan
                            </h5>
                            <p class="text-secondary small mb-3">Ubah kata sandi akun kamu secara berkala.</p>

                            <form action="{{ route('user.profil.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label for="current_password" class="form-label">Password Saat Ini</label>
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control" required>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="password_confirmation" class="form-label">Konfirmasi
                                            Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-brand px-4 mt-3">
                                    <i class="fa-solid fa-key me-1"></i> Perbarui Password
                                </button>
                            </form>
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </main>

    <script>
        function previewKtp(event) {
            const file = event.target.files[0];
            if (!file) return;

            const img = document.getElementById('ktp-preview-img');
            const empty = document.getElementById('ktp-preview-empty');

            img.src = URL.createObjectURL(file);
            img.classList.remove('d-none');
            if (empty) empty.classList.add('d-none');
        }
    </script>
@endsection
