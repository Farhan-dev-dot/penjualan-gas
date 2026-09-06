@extends('layouts.admin.app')

@section('title', 'Data Pelanggan')

@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <section class="data-pelanggan">
            <div class="content-card">

                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Data Pelanggan
                        <small>Kelola seluruh data pelanggan terdaftar</small>
                    </h6>
                </div>

                <form action="{{ route('admin.pelanggan') }}" method="GET" class="table-toolbar">

                    <div class="input-group search-box">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>

                        <input type="text" name="search" class="form-control"
                            placeholder="Cari nama, email, atau telepon..." value="{{ request('search') }}">
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.pelanggan') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i>
                            Reset
                        </a>
                    @endif

                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pelanggans as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="cell-primary">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td class="cell-muted">{{ $item->telepon }}</td>
                                    <td>
                                        <select
                                            class="form-select form-select-sm status-select {{ $item->status === 'aktif' ? 'status-active' : 'status-inactive' }}"
                                            data-id="{{ $item->id }}"
                                            data-url="{{ route('admin.pelanggan.status', $item->id) }}">
                                            <option value="aktif" {{ $item->status === 'aktif' ? 'selected' : '' }}>
                                                Aktif
                                            </option>

                                            <option value="nonaktif" {{ $item->status === 'nonaktif' ? 'selected' : '' }}>
                                                Nonaktif
                                            </option>
                                        </select>
                                    </td>
                                    <td class="text-end">
                                        <div class="row-actions justify-content-end">
                                            <button class="btn-icon" title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#ModalPelanggan" data-name="{{ $item->name }}"
                                                data-email="{{ $item->email }}" data-alamat="{{ $item->alamat }}"
                                                data-role="{{ $item->role }}" data-status="{{ $item->status }}"
                                                data-foto-ktp="{{ $item->foto_ktp ? asset('storage/' . ltrim($item->foto_ktp, '/')) : '' }}"><i
                                                    class="fa-solid fa-eye"></i></button>
                                            <button class="btn-icon btn-icon-danger" title="Hapus"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="table-empty">
                                            <i class="fa-solid fa-users-slash"></i>
                                            <p>Belum ada data customer</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="table-info">Menampilkan 1–10 dari 42 data</span>
                    {{ $pelanggans->links() }}
                </div>

            </div>
        </section>
    </div>
@endsection


@include('components.pelangganmodal')

@section('scripts')
    @include('admin.components.scriptspelanggan')
@endsection
