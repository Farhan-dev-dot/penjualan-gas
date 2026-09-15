@extends('layouts.admin.app')

@section('title', 'Data Pembelian')

@section('content')
    <x-breadcrumb />
    <div class="container-fluid px-0">
        <section class="data-pembelian">
            <div class="content-card">

                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Data Pembelian
                        <small>Kelola seluruh transaksi pembelian & sewa pelanggan</small>
                    </h6>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mx-4 mt-3 mb-0">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mx-4 mt-3 mb-0">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.pembelian') }}" method="GET" class="table-toolbar">

                    <div class="input-group search-box">
                        <span class="input-group-text">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari kode pembelian atau nama pembeli..." value="{{ request('search') }}">
                    </div>

                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>
                            Berhasil
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Menunggu Pembayaran
                        </option>
                        <option value="menunggu_konfirmasi"
                            {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>
                            Menunggu Konfirmasi
                        </option>
                        <option value="expire" {{ request('status') == 'expire' ? 'selected' : '' }}>
                            Kadaluarsa
                        </option>
                        <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>
                    </select>

                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    @if (request('search') || request('status'))
                        <a href="{{ route('admin.pembelian') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i>
                            Reset
                        </a>
                    @endif

                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Kode Pembelian</th>
                                <th>Pembeli</th>
                                <th>Produk</th>
                                <th>Metode Bayar</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pembelians as $item)
                                @php
                                    // Disiapkan di awal setiap baris agar tersedia untuk tombol detail.
                                    $detailItems = $item->details
                                        ->map(function ($detail) {
                                            return [
                                                'produk' => $detail->produk->jenis_gas ?? '-',
                                                'jumlah' => $detail->jumlah,
                                                'tipe' => $detail->tipe_transaksi,
                                                'durasi' => $detail->durasi,
                                                'subtotal' => number_format($detail->subtotal, 0, ',', '.'),
                                                'penerima' => $detail->nama_penerima,
                                                'telepon' => $detail->telepon_penerima,
                                                'alamat' => trim(
                                                    implode(
                                                        ', ',
                                                        array_filter([
                                                            $detail->alamat_penerima,
                                                            $detail->kelurahan,
                                                            $detail->kecamatan,
                                                            $detail->kota,
                                                            $detail->provinsi,
                                                        ]),
                                                    ),
                                                ),
                                                'catatan' => $detail->catatan,
                                            ];
                                        })
                                        ->values();
                                @endphp
                                <tr>
                                    <td>
                                        <span class="cell-primary d-block">{{ $item->kode_penjualan }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="row-avatar">
                                                {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                            <div>
                                                <span class="cell-primary d-block">{{ $item->user->name ?? '-' }}</span>
                                                <span class="cell-muted small">{{ $item->user->email ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php $firstDetail = $item->details->first(); @endphp
                                        @if ($firstDetail)
                                            <span class="cell-primary d-block">
                                                {{ $firstDetail->produk->jenis_gas ?? '-' }}
                                            </span>
                                            @if ($item->details->count() > 1)
                                                <span class="cell-muted small">
                                                    +{{ $item->details->count() - 1 }} produk lainnya
                                                </span>
                                            @else
                                                <span class="cell-muted small">
                                                    {{ $firstDetail->jumlah }}
                                                    {{ $firstDetail->tipe_transaksi }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="cell-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="cell-muted text-uppercase">
                                        {{ $item->payment_type ?? '-' }}
                                    </td>
                                    <td class="cell-primary">
                                        Rp {{ number_format($item->gross_amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'settlement' => ['label' => 'Berhasil', 'class' => 'status-active'],
                                                'capture' => ['label' => 'Selesai', 'class' => 'status-active'],
                                                'pending' => ['label' => 'Menunggu', 'class' => 'status-pending'],
                                                'menunggu_konfirmasi' => [
                                                    'label' => 'Menunggu Konfirmasi',
                                                    'class' => 'status-pending',
                                                ],
                                                'expire' => ['label' => 'Kadaluarsa', 'class' => 'status-inactive'],
                                                'cancel' => ['label' => 'Dibatalkan', 'class' => 'status-inactive'],
                                                'deny' => ['label' => 'Ditolak', 'class' => 'status-inactive'],
                                            ];
                                            $status = $statusMap[$item->payment_status] ?? [
                                                'label' => ucfirst($item->payment_status ?? '-'),
                                                'class' => 'status-pending',
                                            ];
                                        @endphp
                                        @php($canConfirmPayment = $item->payment_status === 'menunggu_konfirmasi')
                                        <select
                                            class="form-select form-select-sm status-select payment-status-select {{ $status['class'] }}"
                                            data-url="{{ route('admin.pembelian.payment-status', $item) }}"
                                            data-original-status="{{ $item->payment_status }}"
                                            {{ $canConfirmPayment ? '' : 'disabled' }}
                                            aria-label="Status pembayaran {{ $item->kode_penjualan }}">
                                            @if ($canConfirmPayment)
                                                <option value="menunggu_konfirmasi" selected>Menunggu Konfirmasi</option>
                                                <option value="settlement">Berhasil</option>
                                            @else
                                                <option value="{{ $item->payment_status }}" selected>
                                                    {{ $status['label'] }}</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td class="cell-muted">
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="row-actions justify-content-end">
                                            <button class="btn-icon" title="Detail" data-bs-toggle="modal"
                                                data-bs-target="#ModalPembelianDetail"
                                                data-kode="{{ $item->kode_penjualan }}"
                                                data-pembeli="{{ $item->user->name ?? '-' }}"
                                                data-email="{{ $item->user->email ?? '-' }}"
                                                data-payment="{{ strtoupper($item->payment_type ?? '-') }}"
                                                data-status="{{ $status['label'] }}"
                                                data-statusclass="{{ $status['class'] }}"
                                                data-total="Rp {{ number_format($item->gross_amount, 0, ',', '.') }}"
                                                data-tanggal="{{ $item->created_at->format('d M Y, H:i') }}"
                                                data-items='{{ json_encode($detailItems) }}'>
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

                                            <form action="{{ route('admin.pembelian.destroy', $item->id_penjualan) }}"
                                                method="POST" class="d-inline form-delete"
                                                onsubmit="return confirm('Yakin ingin menghapus pembelian {{ addslashes($item->kode_penjualan) }}?')">
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
                                            <i class="fa-solid fa-receipt"></i>
                                            <p>Belum ada data pembelian</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="table-info">
                        Menampilkan {{ $pembelians->firstItem() ?? 0 }}–{{ $pembelians->lastItem() ?? 0 }}
                        dari {{ $pembelians->total() }} data
                    </span>
                    {{ $pembelians->links() }}
                </div>

            </div>
        </section>
    </div>


@endsection

@include('components.pembeliandetailmodal')

@section('scripts')
    @include('admin.components.scriptspembelian')
@endsection
