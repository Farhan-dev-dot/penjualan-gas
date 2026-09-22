<!-- Modal Tambah Barang Masuk (multi baris, full manual) -->
<div class="modal fade cm-modal" id="ModalBarangMasuk" tabindex="-1" aria-labelledby="BarangMasukModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content cm-content">

            <form id="form-barang-masuk" action="{{ route('admin.barang-masuk.store') }}" method="POST">
                @csrf

                <div class="modal-header cm-header">
                    <div class="cm-header-left">
                        <div class="cm-avatar"><i class="fa-solid fa-boxes-stacked"></i></div>
                        <div>
                            <h1 class="modal-title cm-title" id="BarangMasukModalLabel">Tambah Barang Masuk</h1>
                            <span class="cm-subtitle">Isi manual per baris, bisa banyak produk sekaligus</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body cm-body">

                    <!-- Tanggal Transaksi -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" class="form-control" required
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- Tabel Items -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle w-100 mb-0" id="table-barang-masuk">
                            <thead>
                                <tr>
                                    <th style="width:22%">Produk</th>
                                    <th style="width:24%">Pembelian Asal</th>
                                    <th style="width:12%">Jenis Transaksi</th>
                                    <th style="width:9%">Isi</th>
                                    <th style="width:9%">Kosong</th>
                                    <th style="width:9%">Pinjam</th>
                                    <th>Keterangan</th>
                                    <th style="width:5%"></th>
                                </tr>
                            </thead>
                            <tbody id="barang-masuk-body">
                                {{-- baris awal & tambahan di-generate via JS --}}
                            </tbody>
                        </table>
                    </div>

                    <!-- Tombol Tambah Baris -->
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btn-add-row">
                        <i class="fa-solid fa-plus"></i> Tambah Baris
                    </button>

                </div>

                <div class="modal-footer cm-footer">
                    <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-barang-masuk">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Semua
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Template 1 baris (disembunyikan, di-clone via JS) -->
<template id="row-template">
    <tr class="row-item">
        <td>
            <select class="form-select form-select-sm select-produk-row" name="items[__INDEX__][id_produk]" required>
                <option value="">-- Pilih Produk --</option>
                @foreach ($produks as $produk)
                    <option value="{{ $produk->id_produk }}">{{ $produk->jenis_gas }}</option>
                @endforeach
            </select>
        </td>
        <td>
            {{--
                Pembelian Asal bukan <select>.
                Klik input ini akan membuka Modal Pembelian Asal, lalu pembelian
                yang dipilih disimpan pada hidden input di bawahnya.
            --}}
            <div class="input-group input-group-sm">
                <input type="text" readonly class="form-control pembelian-asal-input"
                    placeholder="Pilih Pembelian Asal" value="">
                <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            </div>

            {{-- id_penjualan hasil pilihan dari modal (dipakai proses Barang Masuk) --}}
            <input type="hidden" class="pembelian-asal-id" name="items[__INDEX__][id_penjualan]" value="">

            {{-- Data pendukung pilihan, diisi JS setelah pembelian dipilih --}}
            <input type="hidden" class="pembelian-asal-data-produk" value="">
            <input type="hidden" class="pembelian-asal-data-sisa-pengembalian" value="">
            <input type="hidden" class="pembelian-asal-data-sisa-retur" value="">
        </td>
        <td>
            <select class="form-select form-select-sm" name="items[__INDEX__][jenis_transaksi]" required>
                <option value="masuk">Masuk</option>
                <option value="retur">Retur</option>
                <option value="pengembalian">Pengembalian</option>
            </select>
        </td>
        <td>
            <input type="number" min="0" class="form-control form-control-sm stok-input"
                name="items[__INDEX__][stok_isi]" value="0" placeholder="0" required>
        </td>
        <td>
            <input type="number" min="0" class="form-control form-control-sm stok-input"
                name="items[__INDEX__][stok_kosong]" value="0" required>
        </td>
        <td>
            <input type="number" min="0" class="form-control form-control-sm stok-input"
                name="items[__INDEX__][stok_pinjam]" value="0" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="items[__INDEX__][keterangan]"
                placeholder="Opsional">
        </td>
        <td class="text-center">
            <button type="button" class="btn-icon btn-icon-danger btn-remove-row" title="Hapus baris">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<!-- Modal Detail Barang Masuk (read only) -->
<div class="modal fade cm-modal" id="ModalDetailBarangMasuk" tabindex="-1" aria-labelledby="DetailBarangMasukLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cm-content">
            <div class="modal-header cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <h1 class="modal-title cm-title" id="DetailBarangMasukLabel">Detail Barang Masuk</h1>
                        <span class="cm-subtitle">Informasi lengkap transaksi</span>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body cm-body">
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-box"></i> Produk</span>
                    <span class="cm-field-value" id="detail-bm-produk">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-right-left"></i> Jenis Transaksi</span>
                    <span class="cm-field-value" id="detail-bm-jenis">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-right-left"></i> Nama Petugas </span>
                    <span class="cm-field-value" id="detail-bm-petugas">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-cubes"></i> Stok Isi</span>
                    <span class="cm-field-value" id="detail-bm-isi">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-cube"></i> Stok Kosong</span>
                    <span class="cm-field-value" id="detail-bm-kosong">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-hand-holding"></i> Stok Pinjam</span>
                    <span class="cm-field-value" id="detail-bm-pinjam">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-calendar"></i> Tanggal</span>
                    <span class="cm-field-value" id="detail-bm-tanggal">-</span>
                </div>
                <div class="cm-field cm-field-last">
                    <span class="cm-field-label"><i class="fa-solid fa-note-sticky"></i> Keterangan</span>
                    <span class="cm-field-value" id="detail-bm-keterangan">-</span>
                </div>

            </div>

            <div class="modal-footer cm-footer">
                <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{--
    Modal Pembelian Asal
    Daftar seluruh pembelian yang masih dapat digunakan sebagai sumber Barang Masuk.

    Ketersediaan ditentukan oleh sisa jumlah yang masih dapat diproses
    (Pengembalian / Retur), bukan oleh payment_status. Transaksi berstatus
    'settlement' tetap ditampilkan selama masih ada sisa.

    Sumber data: $pembelianDetails (query existing pada TransaksiController),
    dikelompokkan per id_penjualan.
--}}
<div class="modal fade cm-modal" id="ModalPembelianAsal" tabindex="-1" aria-labelledby="PembelianAsalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content cm-content">
            <div class="modal-header cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar"><i class="fa-solid fa-file-invoice"></i></div>
                    <div>
                        <h1 class="modal-title cm-title" id="PembelianAsalLabel">Pilih Pembelian Asal</h1>
                        <span class="cm-subtitle">Pembelian yang masih memiliki sisa barang untuk diproses</span>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body cm-body">

                {{-- Search: kode pembelian / penerima / produk / status pembayaran --}}
                <div class="input-group search-box mb-3">
                    <span class="input-group-text">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" id="pa-search" class="form-control"
                        placeholder="Cari kode pembelian / penerima / produk / status..." autocomplete="off">
                </div>

                @php
                    // Satu baris = satu pembelian (dikelompokkan per id_penjualan).
                    // Logic sisa_pengembalian / sisa_retur tetap memakai data existing.
                    $barisPembelian = $pembelianDetails
                        ->groupBy('id_penjualan')
                        ->map(function ($details, $idPenjualan) {
                            $detailUtama = $details->first();

                            return [
                                'id_penjualan' => $idPenjualan,
                                'kode' => $detailUtama->pembelian->kode_penjualan ?? '-',
                                'penerima' => $detailUtama->nama_penerima ?? '-',
                                'telepon' => $detailUtama->telepon_penerima ?? '',
                                'produk' => $details
                                    ->pluck('produk.jenis_gas')
                                    ->filter()
                                    ->unique()
                                    ->values()
                                    ->implode(', '),
                                'status' => $detailUtama->pembelian->payment_status ?? '-',
                                'sisa_pengembalian' => (int) $details->sum('sisa_pengembalian'),
                                'sisa_retur' => (int) $details->sum('sisa_retur'),
                                'data_produk' => $details->pluck('id_produk')->unique()->implode(','),
                                'data_sisa_pengembalian' => $details
                                    ->pluck('sisa_pengembalian', 'id_produk')
                                    ->toArray(),
                                'data_sisa_retur' => $details->pluck('sisa_retur', 'id_produk')->toArray(),
                            ];
                        })
                        ->values();
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="table-pembelian-asal">
                        <thead>
                            <tr>
                                <th style="width:15%">Kode Pembelian</th>
                                <th style="width:18%">Penerima</th>
                                <th style="width:24%">Produk</th>
                                <th style="width:12%">Status</th>
                                <th style="width:10%" class="text-center">Sisa Pengembalian</th>
                                <th style="width:10%" class="text-center">Sisa Retur</th>
                                <th style="width:11%" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pa-tbody">
                            @forelse ($barisPembelian as $baris)
                                <tr class="pa-row">
                                    <td class="text-break">{{ $baris['kode'] }}</td>
                                    <td class="text-break">
                                        {{ $baris['penerima'] }}
                                        @if ($baris['telepon'])
                                            <span class="d-block text-muted small">{{ $baris['telepon'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-break">{{ $baris['produk'] ?: '-' }}</td>
                                    <td class="text-break">
                                        @if ($baris['status'] === 'settlement')
                                            <span class="badge bg-success">Berhasil</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $baris['status'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $baris['sisa_pengembalian'] }}</td>
                                    <td class="text-center">{{ $baris['sisa_retur'] }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-pembelian"
                                            data-id="{{ $baris['id_penjualan'] }}" data-kode="{{ $baris['kode'] }}"
                                            data-produk="{{ $baris['data_produk'] }}"
                                            data-sisa-pengembalian='@json($baris['data_sisa_pengembalian'])'
                                            data-sisa-retur='@json($baris['data_sisa_retur'])'>
                                            <i class="fa-solid fa-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="table-empty">
                                            <i class="fa-solid fa-box-open"></i>
                                            <p>Belum ada pembelian yang bisa diproses.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            {{-- Pesan khusus ketika search tidak menemukan hasil --}}
                            <tr id="pa-empty-search" style="display:none">
                                <td colspan="7">
                                    <div class="table-empty">
                                        <i class="fa-solid fa-box-open"></i>
                                        <p>Pembelian tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination: maksimal 10 pembelian per halaman --}}
                <div id="pa-footer" class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3">
                    <span class="text-muted small" id="pa-info">-</span>
                    <nav aria-label="Pagination pembelian asal">
                        <ul class="pagination pagination-sm mb-0" id="pa-pagination"></ul>
                    </nav>
                </div>

                <p class="text-muted small mt-3 mb-0">
                    Pembelian berstatus <strong>settlement</strong> tetap dapat dipilih selama masih ada sisa barang
                    yang dapat diproses.
                </p>
            </div>

            <div class="modal-footer cm-footer">
                <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
