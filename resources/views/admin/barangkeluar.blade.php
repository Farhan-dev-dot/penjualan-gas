@extends('layouts.admin.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <section class="data-barang-keluar">
            <div class="content-card">

                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Data Barang Keluar
                        <small>Kelola seluruh transaksi barang keluar berdasarkan pembelian</small>
                    </h6>

                    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#ModalBarangKeluar">
                        <i class="fa-solid fa-plus"></i> Tambah Barang Keluar
                    </button>
                </div>

                <form action="{{ route('admin.barang-keluar') }}" method="GET" class="table-toolbar">
                    <div class="input-group search-box">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari produk, kode pembelian, atau penerima..." value="{{ request('search') }}">
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.barang-keluar') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Pembelian</th>
                                <th>Penerima</th>
                                <th class="text-center">Jumlah Produk</th>
                                <th>Petugas</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barangKeluars as $pembelian)
                                @php
                                    $transaksis = $pembelian->barangTransaksis;
                                    $detailPertama = $pembelian->details->first();
                                    $transaksiTerbaru = $transaksis->first();
                                    $itemsDetail = $transaksis->map(function ($transaksi) use ($pembelian) {
                                        $detail = $pembelian->details->firstWhere('id_produk', $transaksi->id_produk);

                                        return [
                                            'produk' => $transaksi->produk->jenis_gas ?? '-',
                                            'tipe' => ucfirst(str_replace('_', ' ', $detail->tipe_transaksi ?? '-')),
                                            'isi' => $transaksi->stok_isi,
                                            'kosong' => $transaksi->stok_kosong,
                                            'pinjam' => $transaksi->stok_pinjam,
                                            'keterangan' => $transaksi->keterangan ?? '-',
                                        ];
                                    });
                                @endphp
                                <tr>
                                    <td>{{ optional($transaksiTerbaru?->tanggal_transaksi)->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $pembelian->kode_penjualan }}</td>
                                    <td>{{ $detailPertama->nama_penerima ?? '-' }}</td>
                                    <td class="text-center">{{ $transaksis->count() }}</td>
                                    <td>{{ $transaksiTerbaru->nama_petugas ?? '-' }}</td>
                                    <td class="text-end">
                                        <div class="row-actions justify-content-end">
                                            <a href="{{ route('admin.barang-keluar.surat-jalan', $pembelian) }}"
                                                target="_blank" class="btn-icon" title="Cetak Surat Jalan">
                                                <i class="fa-solid fa-file-invoice"></i>
                                            </a>
                                            <button class="btn-icon" title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#ModalDetailBarangKeluar"
                                                data-kode="{{ $pembelian->kode_penjualan }}"
                                                data-penerima="{{ $detailPertama->nama_penerima ?? '-' }}"
                                                data-telepon="{{ $detailPertama->telepon_penerima ?? '-' }}"
                                                data-alamat="{{ $detailPertama->alamat_penerima ?? '-' }}"
                                                data-tanggal="{{ optional($transaksiTerbaru?->tanggal_transaksi)->format('d M Y') ?? '-' }}"
                                                data-items='@json($itemsDetail)'>
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <form action="{{ route('admin.barang-keluar.destroy', $pembelian) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus seluruh transaksi barang keluar untuk kode pembelian ini? Semua stok akan dikembalikan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-icon-danger"
                                                    title="Hapus Semua Transaksi">
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
                                            <p>Belum ada data barang keluar</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="table-info">
                        Menampilkan {{ $barangKeluars->firstItem() ?? 0 }}–{{ $barangKeluars->lastItem() ?? 0 }}
                        dari {{ $barangKeluars->total() }} data
                    </span>
                    {{ $barangKeluars->links() }}
                </div>

            </div>
        </section>
    </div>

    @include('components.barangkeluarmodal')

@endsection

@section('scripts')
    @include('admin.components.scriptsbarangkeluar')
@endsection
