@extends('layouts.admin.app')

@section('title', 'Manajemen Admin')

@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <section class="data-pelanggan">
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Manajemen Admin
                        <small>Kelola akun dengan role Manager, Admin, dan User</small>
                    </h6>
                    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#ModalTambahAkun">
                        <i class="fa-solid fa-plus"></i> Tambah Akun
                    </button>
                </div>

                <form action="{{ route('manager.manajemen-admin') }}" method="GET" class="table-toolbar">
                    <div class="input-group search-box">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama, email, atau telepon..." value="{{ $search }}">
                    </div>
                    <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
                    @if ($search)
                        <a href="{{ route('manager.manajemen-admin') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Administrator</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr>
                                    <td><span class="cell-primary">{{ $admin->name }}</span></td>
                                    <td>{{ $admin->email }}</td>
                                    <td class="cell-muted">{{ $admin->telepon ?: '-' }}</td>
                                    <td><span class="text-capitalize">{{ $admin->role }}</span></td>
                                    <td>
                                        <form action="{{ route('manager.manajemen-admin.status', $admin) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status"
                                                class="form-select form-select-sm status-select {{ $admin->status === 'aktif' ? 'status-active' : 'status-inactive' }}"
                                                onchange="this.form.submit()">
                                                <option value="aktif" @selected($admin->status === 'aktif')>Aktif</option>
                                                <option value="nonaktif" @selected($admin->status === 'nonaktif')>Nonaktif</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('manager.manajemen-admin.destroy', $admin) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus akun admin ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="table-empty">
                                            <i class="fa-solid fa-user-shield"></i>
                                            <p>Belum ada akun administrator</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    {{ $admins->links() }}
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade cm-modal" id="ModalTambahAkun" tabindex="-1" aria-labelledby="TambahAkunLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content cm-content">
                <form action="{{ route('manager.manajemen-admin.store') }}" method="POST">
                    @csrf
                    <div class="modal-header cm-header">
                        <div class="cm-header-left">
                            <div class="cm-avatar"><i class="fa-solid fa-user-plus"></i></div>
                            <div>
                                <h1 class="modal-title cm-title" id="TambahAkunLabel">Tambah Akun</h1>
                                <span class="cm-subtitle">Buat akun Manager, Admin, atau User</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body cm-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" type="text" name="name" class="form-control"
                                value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control"
                                value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input id="telepon" type="text" name="telepon" class="form-control"
                                value="{{ old('telepon') }}">
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control" rows="2" required>{{ old('alamat') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="manager" @selected(old('role') === 'manager')>Manager</option>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                                <option value="user" @selected(old('role') === 'user')>User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" class="form-control" minlength="8"
                                required>
                        </div>
                        <div>
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="form-control" minlength="8" required>
                        </div>
                    </div>
                    <div class="modal-footer cm-footer">
                        <button type="button" class="btn btn-secondary cm-btn-close"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                            Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
