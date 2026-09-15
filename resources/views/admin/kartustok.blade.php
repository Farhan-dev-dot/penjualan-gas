@extends('layouts.admin.app')

@section('title', 'Kartu Stok')
@section('page-title', 'Kartu Stok')

@section('content')
    <x-breadcrumb />

    @php
        $sudahFilter =
            request()->filled('perusahaan') || request()->filled('tanggal_mulai') || request()->filled('tanggal_akhir');
    @endphp

    <div class="container-fluid px-0">
        <section class="data-kartu-stok">
            <div class="content-card">

                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Kartu Stok
                        <small>Riwayat mutasi stok masuk, keluar, dan Stok per produk</small>
                    </h6>
                </div>

                @if (session('error'))
                    <div class="alert alert-warning mx-4 mt-3 mb-0">{{ session('error') }}</div>
                @endif

                {{-- ===================== FILTER (Bootstrap 5.4) ===================== --}}
                <div class="stok-filter-panel">
                    <div class="stok-filter-heading">
                        <div>
                            <h6 class="mb-1"><i class="fa-solid fa-filter me-2"></i>Filter Data Kartu Stok</h6>
                            <p class="mb-0">Cari berdasarkan nama perusahaan atau rentang tanggal transaksi.</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.kartu-stok') }}" method="GET"
                        class="row g-3 align-items-end stok-filter-form">

                        @if ($errors->any())
                            <div class="col-12">
                                <div class="alert alert-danger mb-0 py-2" role="alert">
                                    {{ $errors->first() }}
                                </div>
                            </div>
                        @endif

                        <div class="col-12 col-md-4">
                            <label for="perusahaan" class="form-label fw-semibold">Nama Perusahaan/Pelanggan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-building"></i>
                                </span>
                                <input type="text" id="perusahaan" name="perusahaan" class="form-control"
                                    placeholder="Cari nama perusahaan atau pelanggan..."
                                    value="{{ request('perusahaan') }}">
                                @error('perusahaan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <label for="tanggal_mulai" class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control"
                                value="{{ request('tanggal_mulai') }}">
                        </div>

                        <div class="col-6 col-md-3">
                            <label for="tanggal_akhir" class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control"
                                value="{{ request('tanggal_akhir') }}">
                        </div>

                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>
                        </div>

                        @if ($sudahFilter)
                            <div class="col-12 d-flex gap-2">
                                <a href="{{ route('admin.kartu-stok') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                                </a>
                                <a href="{{ route('admin.kartu-stok.print', request()->query()) }}" target="_blank"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fa-solid fa-print me-1"></i> Cetak
                                </a>
                            </div>
                        @endif

                    </form>
                </div>

                <hr class="m-0">

                {{-- ===================== HASIL ===================== --}}
                @if (!$sudahFilter)
                    {{-- Tabel belum ditampilkan sebelum user melakukan filter --}}
                    <div class="table-empty py-5 text-center">
                        <i class="fa-solid fa-filter fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">
                            Silakan isi nama perusahaan dan/atau rentang tanggal di atas,
                            lalu klik <strong>Filter</strong> untuk menampilkan data kartu stok.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table w-100 mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Perusahaan</th>
                                    <th>Produk</th>
                                    <th>Jenis Transaksi</th>
                                    <th class="text-center" colspan="3">Masuk</th>
                                    <th class="text-center" colspan="3">Keluar</th>
                                    <th class="text-center" colspan="3">Stok</th>
                                    <th>Keterangan</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center small">Isi</th>
                                    <th class="text-center small">Kosong</th>
                                    <th class="text-center small">Pinjam</th>
                                    <th class="text-center small">Isi</th>
                                    <th class="text-center small">Kosong</th>
                                    <th class="text-center small">Pinjam</th>
                                    <th class="text-center small">Isi</th>
                                    <th class="text-center small">Kosong</th>
                                    <th class="text-center small">Pinjam</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kartuStoks as $item)
                                    @php
                                        $transaksi = $item->barangTransaksi;
                                        $isMasuk = in_array(
                                            $transaksi?->jenis_transaksi,
                                            ['masuk', 'retur', 'pengembalian'],
                                            true,
                                        );
                                        $detail = $transaksi?->pembelian?->details?->firstWhere(
                                            'id_produk',
                                            $transaksi->id_produk,
                                        );
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}</td>
                                        <td>{{ $detail?->nama_penerima ?? '-' }}
                                        </td>
                                        <td>{{ $transaksi?->produk?->jenis_gas ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="status-badge {{ $isMasuk ? 'status-masuk' : 'status-pengembalian' }}">
                                                {{ ucfirst($transaksi?->jenis_transaksi ?? '-') }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            {{ $isMasuk ? $transaksi->stok_isi : 0 }}</td>
                                        <td class="text-center">
                                            {{ $isMasuk ? $transaksi->stok_kosong : 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $isMasuk ? $transaksi->stok_pinjam : 0 }}
                                        </td>

                                        <td class="text-center">
                                            {{ $transaksi?->jenis_transaksi === 'keluar' ? $transaksi->stok_isi : 0 }}</td>
                                        <td class="text-center">
                                            {{ $transaksi?->jenis_transaksi === 'keluar' ? $transaksi->stok_kosong : 0 }}
                                        </td>
                                        <td class="text-center">
                                            {{ $transaksi?->jenis_transaksi === 'keluar' ? $transaksi->stok_pinjam : 0 }}
                                        </td>

                                        <td class="text-center fw-semibold">{{ $item->stok_isi_sesudah }}</td>
                                        <td class="text-center fw-semibold">{{ $item->stok_kosong_sesudah }}</td>
                                        <td class="text-center fw-semibold">{{ $item->stok_pinjam_sesudah }}</td>

                                        <td class="cell-muted">{{ $transaksi?->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14">
                                            <div class="table-empty">
                                                <i class="fa-solid fa-box-open"></i>
                                                <p>Tidak ada data kartu stok yang cocok dengan filter ini</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <span class="table-info">
                            Menampilkan {{ $kartuStoks->firstItem() ?? 0 }}–{{ $kartuStoks->lastItem() ?? 0 }}
                            dari {{ $kartuStoks->total() }} data
                        </span>
                        {{ $kartuStoks->links() }}
                    </div>
                @endif

            </div>
        </section>
    </div>
@endsection
