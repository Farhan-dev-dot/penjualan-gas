@extends('layouts.admin.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <section class="data-barang-masuk">
            <div class="content-card">

                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Data Barang Masuk
                        <small>Kelola seluruh transaksi barang masuk secara manual</small>
                    </h6>

                    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#ModalBarangMasuk">
                        <i class="fa-solid fa-plus"></i> Tambah Barang Masuk
                    </button>
                </div>

                <form action="{{ route('admin.barang-masuk') }}" method="GET" class="table-toolbar">
                    <div class="input-group search-box">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari produk atau keterangan..." value="{{ request('search') }}">
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.barang-masuk') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Jenis Transaksi</th>
                                <th class="text-center">Isi</th>
                                <th class="text-center">Kosong</th>
                                <th class="text-center">Pinjam</th>
                                <th>Keterangan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangMasuks as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}</td>
                                    <td>{{ $item->produk->jenis_gas ?? '-' }}</td>
                                    <td>
                                        <span
                                            class="status-badge
        {{ $item->jenis_transaksi === 'masuk'
            ? 'status-masuk'
            : ($item->jenis_transaksi === 'retur'
                ? 'status-retur'
                : 'status-pengembalian') }}">
                                            {{ ucfirst($item->jenis_transaksi) }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $item->stok_isi }}</td>
                                    <td class="text-center">{{ $item->stok_kosong }}</td>
                                    <td class="text-center">{{ $item->stok_pinjam }}</td>
                                    <td class="cell-muted">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-end">
                                        <div class="row-actions justify-content-end">
                                            <button class="btn-icon" title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#ModalDetailBarangMasuk"
                                                data-produk="{{ $item->produk->jenis_gas ?? '-' }}"
                                                data-jenis="{{ ucfirst($item->jenis_transaksi) }}"
                                                data-petugas="{{ $item->nama_petugas ?? '-' }}"
                                                data-isi="{{ $item->stok_isi }}" data-kosong="{{ $item->stok_kosong }}"
                                                data-pinjam="{{ $item->stok_pinjam }}"
                                                data-keterangan="{{ $item->keterangan ?? '-' }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <form action="{{ route('admin.barang-masuk.destroy', $item->id_transaksi) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus transaksi barang masuk ini? Stok akan dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-icon-danger" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="table-empty">
                                            <i class="fa-solid fa-box-open"></i>
                                            <p>Belum ada data barang masuk</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="table-info">
                        Menampilkan {{ $barangMasuks->firstItem() ?? 0 }}–{{ $barangMasuks->lastItem() ?? 0 }}
                        dari {{ $barangMasuks->total() }} data
                    </span>
                    {{ $barangMasuks->links() }}
                </div>

            </div>
        </section>
    </div>

    @include('components.barangmasukmodal')
@endsection

@section('scripts')
    @include('admin.components.scriptsbarangmasuk')
@endsection
