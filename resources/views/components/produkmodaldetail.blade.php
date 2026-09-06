<div class="modal fade cm-modal" id="ModalProdukDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cm-content">

            <div class="cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="min-width-0">
                        <h6 class="cm-title" id="modal-nama">Detail Produk</h6>
                        <small class="cm-subtitle" id="modal-kode">-</small>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>

            <div class="cm-body">
                <div class="text-center mb-3">
                    <img id="modal-foto" src="" alt="Foto Produk" class="modal-produk-foto">
                </div>


                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-weight-hanging"></i> Berat</span>
                    <span id="modal-berat" class="cm-field-value"></span>
                </div>

                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-tag"></i> Harga</span>
                    <span id="modal-harga" class="cm-field-value"></span>
                </div>

                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-boxes-stacked"></i> Stok</span>
                    <span class="cm-field-value">
                        <div class="stok-info justify-content-end">
                            <span class="stok-badge stok-isi">
                                <i class="fa-solid fa-box"></i> Isi: <span id="modal-stokisi"></span>
                            </span>
                            <span class="stok-badge stok-kosong">
                                <i class="fa-solid fa-box-open"></i> Kosong: <span id="modal-stokkosong"></span>
                            </span>
                            <span class="stok-badge stok-pinjam">
                                <i class="fa-solid fa-arrow-right-arrow-left"></i> Pinjam: <span
                                    id="modal-stokpinjam"></span>
                            </span>
                        </div>
                    </span>
                </div>

                <div class="cm-field cm-field-last flex-column align-items-start">
                    <span class="cm-field-label mb-1"><i class="fa-solid fa-align-left"></i> Deskripsi</span>
                    <p id="modal-deskripsi" class="cm-field-value text-start w-100 mb-0"></p>
                </div>
            </div>

            <div class="cm-footer">
                <button type="button" class="cm-btn-close w-100" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
