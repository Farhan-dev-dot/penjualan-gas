<div class="modal fade cm-modal" id="ModalFormProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content cm-content">

            <form id="formProduk" action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="cm-header">
                    <div class="cm-header-left">
                        <div class="cm-avatar">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <h6 class="cm-title" id="modalFormTitle">Tambah Produk</h6>
                            <span class="cm-subtitle" id="modalFormSubtitle">Lengkapi data produk baru</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"></button>
                </div>

                <div class="cm-body">
                    <div class="pf-grid">

                        {{-- Kolom 1: foto --}}
                        <div class="pf-photo-col">
                            <label class="pf-label">Foto Produk</label>
                            <div class="pf-photo-upload" id="photoDropzone">
                                <img id="photoPreview" src="{{ asset('assets/img/no-image.svg') }}" alt="Preview">
                                <div class="pf-photo-overlay">
                                    <i class="fa-solid fa-camera"></i>
                                    <span>Ganti Foto</span>
                                </div>
                                <input type="file" name="foto" id="inputFoto" accept="image/*"
                                    class="pf-photo-input">
                            </div>
                            <small class="pf-hint">Format JPG/PNG, maks 2MB</small>
                        </div>

                        {{-- Kolom 2: info dasar --}}
                        <div class="pf-fields-col">
                            <span class="pf-section-label">Informasi Dasar</span>

                            <div class="pf-field">
                                <label class="pf-label">Kode Produk</label>
                                <input type="text" name="kode_produk" id="input-kode_produk" class="pf-input"
                                    placeholder="By Sistem" disabled>
                            </div>

                            <div class="pf-field">
                                <label class="pf-label">Jenis Gas</label>
                                <input type="text" name="jenis_gas" id="input-jenis_gas" class="pf-input"
                                    placeholder="Jenis Gas" required>
                            </div>



                            <div class="pf-row">
                                <div class="pf-field">
                                    <label class="pf-label">Berat</label>
                                    <input type="number" step="0.01" name="berat" id="input-berat"
                                        class="pf-input" placeholder="0" required>
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">Satuan</label>
                                    <select name="satuan" id="input-satuan" class="pf-input" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="gram">Gram</option>
                                        <option value="liter">Liter</option>
                                        <option value="m3">Meter Kubik</option>
                                        <option value="tabung">Tabung</option>
                                        <option value="pcs">Pcs</option>
                                        <option value="unit">Unit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pf-field">
                                <label class="pf-label">Harga</label>
                                <div class="pf-input-prefix">
                                    <span>Rp</span>
                                    <input type="number" name="harga" id="input-harga"
                                        class="pf-input pf-input-has-prefix" placeholder="0" required>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom 3: deskripsi + stok --}}
                        <div class="pf-fields-col">
                            <span class="pf-section-label">Deskripsi</span>

                            <div class="pf-field pf-field-grow">
                                <textarea name="deskripsi" id="input-deskripsi" class="pf-input pf-textarea" placeholder="Deskripsi singkat produk"></textarea>
                            </div>

                            <span class="pf-section-label pf-section-label-spaced">Informasi Stok</span>

                            <div class="pf-field">
                                <label class="pf-label">
                                    <i class="fa-solid fa-box text-success"></i> Stok Isi
                                </label>
                                <input type="number" name="stok_isi" id="input-stok_isi" class="pf-input"
                                    placeholder="0" value="0" required>
                            </div>

                            <div class="pf-row">
                                <div class="pf-field">
                                    <label class="pf-label">
                                        <i class="fa-solid fa-box-open text-danger"></i> Stok Kosong
                                    </label>
                                    <input type="number" name="stok_kosong" id="input-stok_kosong" class="pf-input"
                                        placeholder="0" value="0" required>
                                </div>
                                <div class="pf-field">
                                    <label class="pf-label">
                                        <i class="fa-solid fa-arrow-right-arrow-left text-warning"></i> Pinjam
                                    </label>
                                    <input type="number" name="stok_pinjam" id="input-stok_pinjam" class="pf-input"
                                        placeholder="0" value="0" required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="cm-footer d-flex justify-content-end gap-2">
                    <button type="button" class="cm-btn-close" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-brand px-4 py-2" id="btnSubmitProduk">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span id="btnSubmitText">Simpan Produk</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
