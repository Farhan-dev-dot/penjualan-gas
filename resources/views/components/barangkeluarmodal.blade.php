<!-- Modal Tambah Barang Keluar (pilih pembelian -> semua produk di dalamnya otomatis tampil) -->
<div class="modal fade cm-modal" id="ModalBarangKeluar" tabindex="-1" aria-labelledby="BarangKeluarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content cm-content">

            <form id="form-barang-keluar" action="{{ route('admin.barang-keluar.store') }}" method="POST">
                @csrf

                <div class="modal-header cm-header">
                    <div class="cm-header-left">
                        <div class="cm-avatar"><i class="fa-solid fa-truck-ramp-box"></i></div>
                        <div>
                            <h1 class="modal-title cm-title" id="BarangKeluarModalLabel">Tambah Barang Keluar</h1>
                            <span class="cm-subtitle">Pilih pembelian, semua produk di dalamnya otomatis tampil</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body cm-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pembelian</label>
                            <select id="select-penjualan-keluar" class="form-select" required>
                                <option value="">-- Pilih Pembelian --</option>
                                @foreach ($pembelianDetails->groupBy('id_penjualan') as $idPenjualan => $details)
                                    <option value="{{ $idPenjualan }}">
                                        {{ $details->first()->pembelian->kode_penjualan ?? '-' }}
                                        — {{ $details->first()->nama_penerima }}
                                        ({{ $details->count() }} produk)
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted" id="pembelian-empty-hint" style="display:none;">
                                Tidak ada data pembelian yang bisa diproses.
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" class="form-control" required
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- Tabel produk milik pembelian yang dipilih -->
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle w-100 mb-0" id="table-barang-keluar">
                            <thead>
                                <tr>
                                    <th style="width:20%">Produk</th>
                                    <th style="width:14%">Tipe</th>
                                    <th style="width:11%">Isi</th>
                                    <th style="width:11%">Kosong</th>
                                    <th style="width:11%">Pinjam</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="barang-keluar-body">
                                @forelse ($pembelianDetails->groupBy('id_penjualan') as $idPenjualan => $details)
                                    @foreach ($details as $detail)
                                        <tr class="row-item row-penjualan-{{ $idPenjualan }}" style="display:none">
                                            <td>
                                                {{ $detail->produk->jenis_gas ?? '-' }}
                                                <input type="hidden" class="item-input"
                                                    name="items[{{ $idPenjualan }}_{{ $detail->id_detail }}][id_detail]"
                                                    value="{{ $detail->id_detail }}" disabled>
                                            </td>
                                            <td>
                                                <span class="status-badge status-masuk">
                                                    {{ ucfirst(str_replace('_', ' ', $detail->tipe_transaksi)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <input type="number" min="0"
                                                    class="form-control form-control-sm item-input"
                                                    name="items[{{ $idPenjualan }}_{{ $detail->id_detail }}][stok_isi]"
                                                    value="0" disabled>
                                            </td>
                                            <td>
                                                <input type="number" min="0"
                                                    class="form-control form-control-sm item-input"
                                                    name="items[{{ $idPenjualan }}_{{ $detail->id_detail }}][stok_kosong]"
                                                    value="0" disabled>
                                            </td>
                                            <td>
                                                <input type="number" min="0"
                                                    class="form-control form-control-sm item-input"
                                                    name="items[{{ $idPenjualan }}_{{ $detail->id_detail }}][stok_pinjam]"
                                                    value="0" disabled>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm item-input"
                                                    name="items[{{ $idPenjualan }}_{{ $detail->id_detail }}][keterangan]"
                                                    placeholder="Opsional" disabled>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                @endforelse
                            </tbody>
                        </table>

                        @if ($pembelianDetails->isEmpty())
                            <div class="table-empty">
                                <i class="fa-solid fa-box-open"></i>
                                <p>Belum ada pembelian yang bisa diproses barang keluarnya</p>
                            </div>
                        @endif
                    </div>

                    <p class="text-muted small mt-2 mb-0" id="hint-pilih-dulu">
                        Pilih pembelian di atas untuk menampilkan daftar produknya.
                    </p>

                </div>

                <div class="modal-footer cm-footer">
                    <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-barang-keluar">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Semua
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Barang Keluar (read only) -->
<div class="modal fade cm-modal" id="ModalDetailBarangKeluar" tabindex="-1" aria-labelledby="DetailBarangKeluarLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cm-content">
            <div class="modal-header cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar"><i class="fa-solid fa-truck-ramp-box"></i></div>
                    <div>
                        <h1 class="modal-title cm-title" id="DetailBarangKeluarLabel">Detail Barang Keluar</h1>
                        <span class="cm-subtitle">Informasi lengkap transaksi</span>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body cm-body">
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-receipt"></i> Kode Pembelian</span>
                    <span class="cm-field-value" id="detail-bk-kode">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-user"></i> Penerima</span>
                    <span class="cm-field-value" id="detail-bk-penerima">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-phone"></i> Telepon</span>
                    <span class="cm-field-value" id="detail-bk-telepon">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-location-dot"></i> Alamat</span>
                    <span class="cm-field-value" id="detail-bk-alamat">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-calendar"></i> Tanggal</span>
                    <span class="cm-field-value" id="detail-bk-tanggal">-</span>
                </div>
                <div class="mt-4">
                    <span class="cm-field-label d-block mb-2"><i class="fa-solid fa-boxes-stacked"></i> Rincian Produk</span>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Tipe</th>
                                    <th class="text-center">Isi</th>
                                    <th class="text-center">Kosong</th>
                                    <th class="text-center">Pinjam</th>
                                </tr>
                            </thead>
                            <tbody id="detail-bk-items"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer cm-footer">
                <button type="button" class="btn btn-secondary cm-btn-close" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
