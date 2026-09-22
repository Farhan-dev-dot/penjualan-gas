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
           0) MODAL PEMBELIAN ASAL
           Alur: klik input Pembelian Asal -> modal terbuka berisi daftar
           pembelian -> klik tombol "Pilih" -> id_penjualan disimpan ke hidden
           input pada baris tersebut -> input terisi kode pembelian -> modal tutup.
        ============================ */
        const modalPembelianAsal = document.getElementById('ModalPembelianAsal');

        // Baris (tr) yang sedang memilih Pembelian Asal.
        let barisPembelianAktif = null;

        // Ambil elemen di dalam baris, toleran bila baris tidak ditemukan.
        function elDiBaris(row, selector) {
            return row ? row.querySelector(selector) : null;
        }

        // Bukа Modal Pembelian Asal ketika input Pembelian Asal diklik.
        // Delegasi event dipakai agar tetap bekerja untuk baris hasil clone.
        document.addEventListener('click', function(e) {
            const input = e.target.closest('.pembelian-asal-input');
            if (!input) return;

            barisPembelianAktif = input.closest('tr');

            if (modalPembelianAsal && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalPembelianAsal).show();
            }
        });

        /* ------------------------------------------------------------
           SEARCH + PAGINATION TABEL PEMBELIAN ASAL
           Maksimal 10 baris per halaman. Hanya baris pada halaman aktif
           yang ditampilkan, sehingga data yang sangat banyak tidak
           sekaligus masuk ke tampilan.
        ------------------------------------------------------------ */
        const PA_PER_PAGE = 10;
        const paTbody = document.getElementById('pa-tbody');
        const paSearch = document.getElementById('pa-search');
        const paPagination = document.getElementById('pa-pagination');
        const paInfo = document.getElementById('pa-info');
        const paFooter = document.getElementById('pa-footer');
        const paEmptySearch = document.getElementById('pa-empty-search');

        // Seluruh baris pembelian (tanpa baris empty-state search).
        const paRows = paTbody ? Array.from(paTbody.querySelectorAll('tr.pa-row')) : [];
        const paEmptyAwal = paTbody ? paTbody.querySelector('.table-empty') : null;

        let paHalaman = 1;

        function paCocok(row, kata) {
            if (!kata) return true;

            // Cari di seluruh isi baris: kode pembelian, penerima, produk, status.
            return row.textContent.toLowerCase().includes(kata.toLowerCase());
        }

        function paRender() {
            if (!paTbody) return;

            const kata = (paSearch?.value || '').trim();
            const hasil = paRows.filter(row => paCocok(row, kata));

            const totalHalaman = Math.max(1, Math.ceil(hasil.length / PA_PER_PAGE));
            if (paHalaman > totalHalaman) paHalaman = totalHalaman;

            const mulai = (paHalaman - 1) * PA_PER_PAGE;
            const akhir = Math.min(mulai + PA_PER_PAGE, hasil.length);

            // Sembunyikan semua baris, lalu tampilkan hanya halaman aktif.
            paRows.forEach(row => row.style.display = 'none');
            hasil.slice(mulai, akhir).forEach(row => row.style.display = '');

            // Empty state: data kosong vs search tidak menemukan.
            if (paEmptyAwal) {
                const kosongTotal = paRows.length === 0;
                paEmptyAwal.style.display = kosongTotal ? '' : 'none';
            }

            // Getarkan footer bila tidak ada data sama sekali.
            if (paFooter) {
                paFooter.style.display = paRows.length === 0 ? 'none' : '';
            }

            if (paEmptySearch) {
                const tidakDitemukan = paRows.length > 0 && hasil.length === 0;
                paEmptySearch.style.display = tidakDitemukan ? '' : 'none';
            }

            // Info jumlah data.
            if (paInfo) {
                paInfo.textContent = hasil.length ?
                    `Menampilkan ${mulai + 1}–${akhir} dari ${hasil.length} pembelian` :
                    '';
            }

            paRenderPagination(totalHalaman);
        }

        function paRenderPagination(totalHalaman) {
            if (!paPagination) return;
            paPagination.replaceChildren();

            if (totalHalaman <= 1) return;

            const tambahItem = (label, halamanTujuan, aktif, disabled) => {
                const li = document.createElement('li');
                li.className = 'page-item' + (aktif ? ' active' : '') + (disabled ? ' disabled' : '');

                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = label;

                a.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (disabled || aktif) return;
                    paHalaman = halamanTujuan;
                    paRender();
                });

                li.appendChild(a);
                paPagination.appendChild(li);
            };

            // Tombol sebelumnya.
            tambahItem('‹', Math.max(1, paHalaman - 1), false, paHalaman === 1);

            // Nomor halaman (dengan elipsis bila halaman banyak).
            const halamanDitampilkan = [];
            for (let i = 1; i <= totalHalaman; i++) {
                if (i === 1 || i === totalHalaman || Math.abs(i - paHalaman) <= 2) {
                    halamanDitampilkan.push(i);
                }
            }

            let sebelumnya = 0;
            halamanDitampilkan.forEach(function(i) {
                if (sebelumnya && i - sebelumnya > 1) {
                    tambahItem('…', paHalaman, false, true);
                }
                tambahItem(String(i), i, i === paHalaman, false);
                sebelumnya = i;
            });

            // Tombol berikutnya.
            tambahItem('›', Math.min(totalHalaman, paHalaman + 1), false, paHalaman === totalHalaman);
        }

        // Ketikan pada search mengembalikan pencarian ke halaman pertama.
        if (paSearch) {
            paSearch.addEventListener('input', function() {
                paHalaman = 1;
                paRender();
            });
        }

        // Setiap modal dibuka: reset pencarian lalu render ulang.
        if (modalPembelianAsal) {
            modalPembelianAsal.addEventListener('show.bs.modal', function() {
                if (paSearch) paSearch.value = '';
                paHalaman = 1;
                paRender();
            });
        }

        // Render awal saat halaman dimuat.
        paRender();

        // Tombol "Pilih" pada tiap pembelian di dalam modal.
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-pilih-pembelian');
            if (!btn) return;

            const row = barisPembelianAktif;

            const idPembelian = btn.dataset.id || '';
            const kodePembelian = btn.dataset.kode || '-';

            const inputTampil = elDiBaris(row, '.pembelian-asal-input');
            const inputId = elDiBaris(row, '.pembelian-asal-id');
            const inputProduk = elDiBaris(row, '.pembelian-asal-data-produk');
            const inputSisaPengembalian = elDiBaris(row, '.pembelian-asal-data-sisa-pengembalian');
            const inputSisaRetur = elDiBaris(row, '.pembelian-asal-data-sisa-retur');

            // Simpan id_penjualan untuk diproses pada Barang Masuk.
            if (inputId) inputId.value = idPembelian;

            // Tampilkan kode pembelian pada input yang terlihat.
            if (inputTampil) inputTampil.value = kodePembelian;

            // Simpan data pendukung untuk validasi sisa barang.
            if (inputProduk) inputProduk.value = btn.dataset.produk || '';
            if (inputSisaPengembalian) {
                inputSisaPengembalian.value = btn.dataset.sisaPengembalian || '{}';
            }
            if (inputSisaRetur) {
                inputSisaRetur.value = btn.dataset.sisaRetur || '{}';
            }

            if (modalPembelianAsal && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalPembelianAsal).hide();
            }
        });

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

                // Batalkan konteks Modal Pembelian Asal yang mungkin masih aktif.
                barisPembelianAktif = null;
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

                // Pembelian Asal berupa hidden input yang diisi dari Modal Pembelian Asal.
                const pembelianEl = row.querySelector('.pembelian-asal-id');
                const pembelian = pembelianEl ? pembelianEl.value : '';

                // Jumlah stok tidak boleh 0 semuanya (isi, kosong, dan pinjam).
                const stokIsi = parseInt(row.querySelector('[name*="[stok_isi]"]').value, 10) ||
                    0;
                const stokKosong = parseInt(row.querySelector('[name*="[stok_kosong]"]').value,
                    10) || 0;
                const stokPinjam = parseInt(row.querySelector('[name*="[stok_pinjam]"]').value,
                    10) || 0;

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
                    alert(
                        `Baris ${index + 1}: Pembelian asal wajib dipilih untuk transaksi retur atau pengembalian.`
                    );
                    isValid = false;
                } else if (['retur', 'pengembalian'].includes(jenis) && pembelian) {
                    // Data pembelian asal diambil dari hidden input yang diisi
                    // saat user menekan "Pilih" pada Modal Pembelian Asal.
                    const dataProdukEl = row.querySelector('.pembelian-asal-data-produk');
                    const dataSisaPengembalianEl = row.querySelector(
                        '.pembelian-asal-data-sisa-pengembalian');
                    const dataSisaReturEl = row.querySelector(
                        '.pembelian-asal-data-sisa-retur');

                    // Jenis gas yang dipilih harus SAMA dengan jenis gas pada
                    // pembelian asal. Contoh: pembelian asal Argon, maka barang
                    // masuk harus Argon (bukan Nitrogen).
                    const produkIdPembelian = String(dataProdukEl?.value || '')
                        .split(',')
                        .map(v => v.trim())
                        .filter(Boolean);

                    const namaProduk = produkEl.selectedOptions[0]?.textContent.trim() ?? '-';
                    const namaPembelian = row.querySelector('.pembelian-asal-input')?.value ||
                        '-';

                    if (!produkIdPembelian.includes(String(produk))) {
                        alert(
                            `Baris ${index + 1}: Barang tidak sesuai dengan pembelian asal.\n\n` +
                            `Jenis gas yang Anda masukkan: ${namaProduk}\n` +
                            `Pembelian asal yang dipilih: ${namaPembelian}\n\n` +
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

                        const sisaPengembalian = parseSisa(dataSisaPengembalianEl?.value);
                        const sisaRetur = parseSisa(dataSisaReturEl?.value);

                        const sisaProdukPengembalian = parseInt(sisaPengembalian[produk] || 0,
                            10) || 0;
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
