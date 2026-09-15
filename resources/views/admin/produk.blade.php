@extends('layouts.admin.app')

@section('title', 'Data Produk')

@section('page-title', 'Data Produk')
@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <section class="data-produk">
            <div class="content-card">

                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Data Produk
                        <small>Kelola seluruh data produk & stok barang</small>
                    </h6>

                    <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#ModalFormProduk"
                        onclick="setModalMode('create')">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Produk
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mx-4 mt-3 mb-0">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mx-4 mt-3 mb-0">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.produk') }}" method="GET" class="table-toolbar">

                    <div class="input-group search-box">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Cari kode atau jenis gas..."
                            value="{{ request('search') }}">
                    </div>


                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.produk') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i>
                            Reset
                        </a>
                    @endif

                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Berat</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/no-image.svg') }}"
                                                alt="{{ $item->jenis_gas }}" class="produk-thumb">
                                            <div>
                                                <span class="cell-primary d-block">{{ $item->jenis_gas }}</span>
                                                <span class="cell-muted small">{{ $item->kode_produk }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="cell-muted">{{ $item->berat }} {{ $item->satuan }}</td>
                                    <td class="cell-primary">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="stok-info">
                                            <span class="stok-badge stok-isi" title="Stok Isi">
                                                <i class="fa-solid fa-box"></i> {{ $item->stok_isi }}
                                            </span>
                                            <span class="stok-badge stok-kosong" title="Stok Kosong">
                                                <i class="fa-solid fa-box-open"></i> {{ $item->stok_kosong }}
                                            </span>
                                            <span class="stok-badge stok-pinjam" title="Stok Dipinjam">
                                                <i class="fa-solid fa-arrow-right-arrow-left"></i> {{ $item->stok_pinjam }}
                                            </span>

                                            <span class="stok-badge stok-rusak" title="Stok Rusak">
                                                <i class="fa-solid fa-exclamation-triangle"></i> {{ $item->stok_rusak }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="row-actions justify-content-end">
                                            <button class="btn-icon" title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#ModalProdukDetail" data-kode="{{ $item->kode_produk }}"
                                                data-nama="{{ $item->jenis_gas }}" data-berat="{{ $item->berat }}"
                                                data-satuan="{{ $item->satuan }}"
                                                data-harga="{{ number_format($item->harga, 0, ',', '.') }}"
                                                data-deskripsi="{{ $item->deskripsi }}"
                                                data-foto="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/no-image.svg') }}"
                                                data-stokisi="{{ $item->stok_isi }}"
                                                data-stokkosong="{{ $item->stok_kosong }}"
                                                data-stokpinjam="{{ $item->stok_pinjam }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

                                            <button type="button" class="btn-icon" title="Edit" data-bs-toggle="modal"
                                                data-bs-target="#ModalFormProduk"
                                                onclick="setModalMode('edit', {
        id: '{{ $item->id_produk }}', 
        kode_produk: '{{ $item->kode_produk }}',
        jenis_gas: '{{ $item->jenis_gas }}',
        berat: '{{ $item->berat }}',
        satuan: '{{ $item->satuan }}',
        harga: '{{ $item->harga }}',
        deskripsi: `{{ $item->deskripsi }}`,
        foto: '{{ $item->foto ? asset('storage/' . $item->foto) : '' }}',
        stok_isi: '{{ $item->stok_isi }}',
        stok_kosong: '{{ $item->stok_kosong }}',
        stok_pinjam: '{{ $item->stok_pinjam }}'
    })">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <form action="{{ route('admin.produk.destroy', $item->id_produk) }}"
                                                method="POST" class="d-inline form-delete"
                                                onsubmit="return confirm('Yakin ingin menghapus produk {{ addslashes($item->jenis_gas) }}?')">
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
                                    <td colspan="6">
                                        <div class="table-empty">
                                            <i class="fa-solid fa-box-open"></i>
                                            <p>Belum ada data produk</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="table-info">
                        Menampilkan {{ $produks->firstItem() ?? 0 }}–{{ $produks->lastItem() ?? 0 }}
                        dari {{ $produks->total() }} data
                    </span>
                    {{ $produks->links() }}
                </div>

            </div>
        </section>
    </div>
@endsection

@include('components.produkmodaldetail')
@include('components.produkformmodal')

@section('scripts')
    @include('admin.components.scriptsproduk')
@endsection
