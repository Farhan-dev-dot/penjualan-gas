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
            <select class="form-select form-select-sm select-penjualan-row" name="items[__INDEX__][id_penjualan]">
                <option value="">-- Tidak ada / pilih untuk retur --</option>
                @foreach ($pembelianDetails->groupBy('id_penjualan') as $idPenjualan => $details)
                    @php
                        $jenisGasPembelian = $details
                            ->pluck('produk.jenis_gas')
                            ->filter()
                            ->unique()
                            ->values();

                        // Sisa yang masih dapat diproses per produk.
                        // Dipakai untuk validasi di sisi klien agar tidak melebihi sisa.
                        $sisaPengembalian = $details->pluck('sisa_pengembalian', 'id_produk')->toArray();
                        $sisaRetur = $details->pluck('sisa_retur', 'id_produk')->toArray();
                    @endphp
                    <option value="{{ $idPenjualan }}"
                        data-produk="{{ $details->pluck('id_produk')->unique()->implode(',') }}"
                        data-jenisgas="{{ $jenisGasPembelian->implode(', ') }}"
                        data-sisa-pengembalian="{{ json_encode($sisaPengembalian) }}"
                        data-sisa-retur="{{ json_encode($sisaRetur) }}">
                        {{ $details->first()->pembelian->kode_penjualan ?? '-' }}
                        — {{ $details->first()->nama_penerima }}
                        ({{ $jenisGasPembelian->implode(', ') }})
                    </option>
                @endforeach
            </select>
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
