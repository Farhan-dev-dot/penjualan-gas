{{-- ============ MODAL DETAIL PEMBELIAN ============ --}}
<div class="modal fade cm-modal" id="ModalPembelianDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content cm-content">
            <div class="cm-header">
                <div class="cm-header-left">
                    <div class="cm-avatar">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <h6 class="cm-title" id="pd-kode">-</h6>
                        <span class="cm-subtitle" id="pd-tanggal">-</span>
                    </div>
                </div>
                <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="cm-body">
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-user"></i> Pembeli</span>
                    <span class="cm-field-value" id="pd-pembeli">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-envelope"></i> Email</span>
                    <span class="cm-field-value" id="pd-email">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-credit-card"></i> Metode Bayar</span>
                    <span class="cm-field-value" id="pd-payment">-</span>
                </div>
                <div class="cm-field">
                    <span class="cm-field-label"><i class="fa-solid fa-circle-check"></i> Status</span>
                    <span class="cm-field-value">
                        <span class="status-badge" id="pd-status">-</span>
                    </span>
                </div>
                <div class="cm-field cm-field-last">
                    <span class="cm-field-label"><i class="fa-solid fa-money-bill-wave"></i> Total Bayar</span>
                    <span class="cm-field-value" id="pd-total">-</span>
                </div>

                <hr class="my-3">

                <p class="pf-section-label mb-2">Rincian Produk</p>
                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Tipe</th>
                                <th>Penerima</th>
                                <th>Alamat</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="pd-items-body">
                            <tr>
                                <td colspan="6" class="cell-muted text-center">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="cm-footer">
                <button type="button" class="cm-btn-close" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
