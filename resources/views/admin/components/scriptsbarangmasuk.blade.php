<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '{{ csrf_token() }}';
        const rowTemplate = document.getElementById('row-template');
        const barangMasukBody = document.getElementById('barang-masuk-body');
        const btnAddRow = document.getElementById('btn-add-row');
        const form = document.getElementById('form-barang-masuk');
        const btnSubmit = document.getElementById('btn-submit-barang-masuk');
        const modalBarangMasuk = document.getElementById('ModalBarangMasuk');

        let rowCount = 0;

        /* ============================
           1) Isi modal detail (read only)
        ============================ */
        const modalDetail = document.getElementById('ModalDetailBarangMasuk');
        if (modalDetail) {
            modalDetail.addEventListener('show.bs.modal', function(event) {
                const btn = event.relatedTarget;
                if (!btn) return;

                document.getElementById('detail-bm-produk').textContent = btn.dataset.produk || '-';
                document.getElementById('detail-bm-jenis').textContent = btn.dataset.jenis || '-';
                document.getElementById('detail-bm-petugas').textContent = btn.dataset.petugas || '-';
                document.getElementById('detail-bm-isi').textContent = btn.dataset.isi || '-';
                document.getElementById('detail-bm-kosong').textContent = btn.dataset.kosong || '-';
                document.getElementById('detail-bm-pinjam').textContent = btn.dataset.pinjam || '-';
                document.getElementById('detail-bm-tanggal').textContent = btn.dataset.tanggal || '-';
                document.getElementById('detail-bm-keterangan').textContent = btn.dataset.keterangan ||
                    '-';
            });
        }

        /* ============================
           2) Fungsi clone row dari template
        ============================ */
        function cloneRow() {
            const clone = rowTemplate.content.cloneNode(true);

            // Ganti __INDEX__ dengan rowCount
            const elements = clone.querySelectorAll('[name*="__INDEX__"]');
            elements.forEach(el => {
                el.name = el.name.replace(/__INDEX__/g, rowCount);
            });

            clone.querySelectorAll('.stok-input').forEach(input => {
                input.defaultValue = '0';
                input.value = '0';
            });

            // Setup tombol hapus
            const btnRemove = clone.querySelector('.btn-remove-row');
            if (btnRemove) {
                btnRemove.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.closest('tr').remove();
                    checkTableEmpty();
                });
            }

            barangMasukBody.appendChild(clone);
            rowCount++;
            checkTableEmpty();
        }

        /* ============================
           3) Check apakah table kosong dan disable submit
        ============================ */
        function checkTableEmpty() {
            const rows = barangMasukBody.querySelectorAll('tr');
            btnSubmit.disabled = rows.length === 0;
        }

        /* ============================
           4) Tombol Tambah Baris
        ============================ */
        btnAddRow.addEventListener('click', function(e) {
            e.preventDefault();
            cloneRow();
        });

        /* ============================
           5) Buat baris awal ketika modal dibuka
        ============================ */
        if (modalBarangMasuk) {
            modalBarangMasuk.addEventListener('show.bs.modal', function() {
                if (barangMasukBody.querySelectorAll('tr').length === 0) {
                    cloneRow();
                }
                checkTableEmpty();
            });

            // Reset form ketika modal ditutup
            modalBarangMasuk.addEventListener('hide.bs.modal', function() {
                form.reset();
                barangMasukBody.innerHTML = '';
                rowCount = 0;
            });
        }

        /* ============================
           6) Submit form via AJAX
        ============================ */
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const rows = barangMasukBody.querySelectorAll('tr');
            if (rows.length === 0) {
                alert('Tambahkan minimal 1 baris data terlebih dahulu.');
                return;
            }

            let isValid = true;
            rows.forEach((row, index) => {
                const produkEl = row.querySelector('[name*="[id_produk]"]');
                const produk = produkEl.value;
                const jenis = row.querySelector('[name*="[jenis_transaksi]"]').value;
                const pembelianEl = row.querySelector('[name*="[id_penjualan]"]');
                const pembelian = pembelianEl.value;

                // Jumlah stok tidak boleh 0 semuanya (isi, kosong, dan pinjam).
                const stokIsi = parseInt(row.querySelector('[name*="[stok_isi]"]').value, 10) || 0;
                const stokKosong = parseInt(row.querySelector('[name*="[stok_kosong]"]').value, 10) || 0;
                const stokPinjam = parseInt(row.querySelector('[name*="[stok_pinjam]"]').value, 10) || 0;

                if (!produk) {
                    alert(`Baris ${index + 1}: Pilih produk terlebih dahulu.`);
                    isValid = false;
                } else if (stokIsi === 0 && stokKosong === 0 && stokPinjam === 0) {
                    alert(
                        `Baris ${index + 1}: Jumlah stok tidak boleh 0 semuanya.\n\n` +
                        `Isi minimal salah satu dari Isi, Kosong, atau Pinjam.\n\n` +
                        `Transaksi dibatalkan dan tidak disimpan.`
                    );
                    isValid = false;
                } else if (['retur', 'pengembalian'].includes(jenis) && !pembelian) {
                    alert(`Baris ${index + 1}: Pembelian asal wajib dipilih untuk transaksi retur atau pengembalian.`);
                    isValid = false;
                } else if (['retur', 'pengembalian'].includes(jenis) && pembelian) {
                    // Jenis gas yang dipilih harus SAMA dengan jenis gas pada
                    // pembelian asal. Contoh: pembelian asal Argon, maka barang
                    // masuk harus Argon (bukan Nitrogen).
                    const opsi = pembelianEl.selectedOptions[0];

                    const produkIdPembelian = String(opsi?.dataset.produk || '')
                        .split(',')
                        .map(v => v.trim())
                        .filter(Boolean);

                    const namaProduk = produkEl.selectedOptions[0]?.textContent.trim() ?? '-';
                    const jenisGasPembelian = String(opsi?.dataset.jenisgas || '').trim() || '-';

                    if (!produkIdPembelian.includes(String(produk))) {
                        alert(
                            `Baris ${index + 1}: Barang tidak sesuai dengan pembelian asal.\n\n` +
                            `Jenis gas yang Anda masukkan: ${namaProduk}\n` +
                            `Jenis gas pada pembelian asal: ${jenisGasPembelian}\n\n` +
                            `Transaksi dibatalkan dan tidak disimpan.`
                        );
                        isValid = false;
                    } else {
                        // Sisa barang yang masih dapat diproses pada pembelian asal.
                        // Pengembalian & retur boleh sebagian, tetapi tidak boleh
                        // melebihi sisa yang belum diproses.
                        const parseSisa = (raw) => {
                            try {
                                return JSON.parse(raw || '{}');
                            } catch (e) {
                                return {};
                            }
                        };

                        const sisaPengembalian = parseSisa(opsi?.dataset.sisaPengembalian);
                        const sisaRetur = parseSisa(opsi?.dataset.sisaRetur);

                        const sisaProdukPengembalian = parseInt(sisaPengembalian[produk] || 0, 10) || 0;
                        const sisaProdukRetur = parseInt(sisaRetur[produk] || 0, 10) || 0;

                        if (jenis === 'pengembalian') {
                            if (stokKosong === 0) {
                                alert(
                                    `Baris ${index + 1}: Pengembalian diisi lewat kolom Kosong.`
                                );
                                isValid = false;
                            } else if (stokKosong > sisaProdukPengembalian) {
                                alert(
                                    `Baris ${index + 1}: Jumlah pengembalian (${stokKosong}) ` +
                                    `melebihi sisa yang dapat dikembalikan (${sisaProdukPengembalian}).`
                                );
                                isValid = false;
                            }
                        }

                        if (jenis === 'retur') {
                            const totalRetur = stokIsi + stokKosong + stokPinjam;

                            if (totalRetur > sisaProdukRetur) {
                                alert(
                                    `Baris ${index + 1}: Jumlah retur (${totalRetur}) ` +
                                    `melebihi sisa yang dapat diretur (${sisaProdukRetur}).`
                                );
                                isValid = false;
                            }
                        }
                    }
                }
            });

            if (!isValid) return;

            const formData = new FormData(form);
            btnSubmit.disabled = true;
            btnSubmit.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();

                    if (!res.ok) {
                        throw new Error(data.message || 'Gagal menyimpan data.');
                    }

                    alert(data.message || 'Data berhasil disimpan!');
                    window.location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert(err.message || 'Terjadi kesalahan koneksi.');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML =
                        '<i class="fa-solid fa-floppy-disk"></i> Simpan Semua';
                });
        });
    });
</script>
