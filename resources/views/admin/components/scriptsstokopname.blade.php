<script>
    function getElement(id) {
        return document.getElementById(id);
    }

    const KATEGORI = ['isi', 'kosong', 'pinjam', 'rusak'];

    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */
    function resetFormStockOpname() {
        KATEGORI.forEach(kategori => {
            const fisik = getElement(`stok_${kategori}_fisik`);
            const selisih = getElement(`selisih_${kategori}`);

            if (fisik) fisik.value = '';
            if (selisih) {
                selisih.value = '';
                selisih.classList.remove('is-valid', 'is-invalid');
            }
        });

        const keterangan = getElement('keterangan');
        const penyesuaian = getElement('penyesuaian');

        if (keterangan) keterangan.value = '';
        if (penyesuaian) penyesuaian.checked = false;
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG SELISIH PER KATEGORI (REALTIME)
    |--------------------------------------------------------------------------
    */
    function hitungSelisih(kategori) {
        const sistemEl = getElement(`stok_${kategori}_sistem`);
        const fisikEl = getElement(`stok_${kategori}_fisik`);
        const selisihEl = getElement(`selisih_${kategori}`);

        if (!sistemEl || !fisikEl || !selisihEl) return;

        const sistem = parseInt(sistemEl.value) || 0;
        const fisik = parseInt(fisikEl.value) || 0;
        const hasil = fisik - sistem;

        selisihEl.value = hasil;
        selisihEl.classList.remove('is-valid', 'is-invalid');
        selisihEl.classList.add(hasil === 0 ? 'is-valid' : 'is-invalid');
    }

    document.addEventListener('input', function(e) {
        const match = e.target.id.match(/^stok_(isi|kosong|pinjam|rusak)_fisik$/);
        if (match) {
            hitungSelisih(match[1]);
        }
    });

    /*
    |--------------------------------------------------------------------------
    | SEARCH PRODUK
    |--------------------------------------------------------------------------
    */
    function searchBarang() {
        const kodeProduk = document.querySelector('input[name="kode_produk"]');
        resetFormStockOpname();
        window.location.href = `?kode_produk=${kodeProduk.value}`;
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM SUBMIT
    |--------------------------------------------------------------------------
    */
    function confirmStockOpname(event) {
        event.preventDefault();

        if (typeof Swal === 'undefined') {
            if (window.confirm('Pastikan data stok fisik sudah benar. Simpan data opname?')) {
                getElement('formStockOpname').submit();
            }
            return false;
        }

        Swal.fire({
            title: 'Simpan Data?',
            text: 'Pastikan data Stok Fisik sudah benar',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                getElement('formStockOpname').submit();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    function deleteBarang(id) {
        if (typeof Swal === 'undefined') {
            if (window.confirm('Yakin ingin menghapus data stok opname ini?')) {
                document.getElementById(`delete-form-${id}`).submit();
            }
            return false;
        }

        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data stok opname akan dihapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL DETAIL - isi data dari tombol yang diklik
    |--------------------------------------------------------------------------
    */
    document.addEventListener('show.bs.modal', function(e) {
        if (e.target.id !== 'ModalDetailOpname') return;

        const btn = e.relatedTarget;
        if (!btn) return;

        getElement('d-produk').textContent = btn.dataset.produk;
        getElement('d-tanggal').textContent = btn.dataset.tanggal;
        getElement('d-keterangan').textContent = btn.dataset.keterangan;

        KATEGORI.forEach(kategori => {
            getElement(`d-${kategori}-sistem`).textContent = btn.dataset[`${kategori}Sistem`];
            getElement(`d-${kategori}-fisik`).textContent = btn.dataset[`${kategori}Fisik`];
            getElement(`d-${kategori}-selisih`).textContent = btn.dataset[`${kategori}Selisih`];
        });
    });

    /*
    |--------------------------------------------------------------------------
    | MODAL PICKER PRODUK
    |--------------------------------------------------------------------------
    */
    const PRODUK_PICKER_URL = @json(route('admin.produk-picker'));

    function loadProdukPicker(url) {
        const container = getElement('picker-table-container');
        if (!container) return;

        container.innerHTML =
            '<div class="table-empty"><i class="fa-solid fa-spinner fa-spin"></i><p>Memuat data...</p></div>';

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                return response.text();
            })
            .then(function(html) {
                container.innerHTML = html;
            })
            .catch(function() {
                container.innerHTML =
                    '<div class="table-empty"><i class="fa-solid fa-triangle-exclamation"></i><p>Gagal memuat data produk</p></div>';
            });
    }

    function bukaPickerProduk() {
        const modalEl = getElement('ModalPilihProduk');
        if (!modalEl) return;

        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);

        // Hindari pemuatan ganda saat event focus dan click sama-sama terpicu.
        if (!modalEl.classList.contains('show')) {
            modalInstance.show();
            loadProdukPicker(PRODUK_PICKER_URL);
        }
    }

    // Buka modal saat input kode produk diklik / difokuskan.
    document.addEventListener('click', function(e) {
        if (e.target.closest('#kode_produk_input') || e.target.closest('#btn-buka-picker-produk')) {
            e.preventDefault();
            bukaPickerProduk();
        }
    });

    document.addEventListener('focus', function(e) {
        if (e.target.closest('#kode_produk_input')) {
            bukaPickerProduk();
        }
    }, true);

    // Search di dalam modal (debounce ~350ms).
    let pickerDebounce = null;
    document.addEventListener('input', function(e) {
        if (e.target.id !== 'picker-search-input') return;

        clearTimeout(pickerDebounce);
        const keyword = e.target.value.trim();

        pickerDebounce = setTimeout(function() {
            const url = keyword === '' ? PRODUK_PICKER_URL :
                `${PRODUK_PICKER_URL}?search=${encodeURIComponent(keyword)}`;
            loadProdukPicker(url);
        }, 350);
    });

    // Pilih produk (delegasi, karena tabel di-replace tiap AJAX).
    document.addEventListener('click', function(e) {
        const row = e.target.closest('.produk-picker-row');
        if (!row) return;

        const inputKodeProduk = getElement('kode_produk_input');
        if (inputKodeProduk) {
            inputKodeProduk.value = row.dataset.kodeProduk;
        }

        const modalEl = getElement('ModalPilihProduk');
        if (modalEl) {
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        }

        const formCari = getElement('formCariProduk');
        if (formCari) formCari.submit();
    });

    // Pagination picker tetap AJAX (delegasi).
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#picker-table-container .pagination a');
        if (!link) return;

        e.preventDefault();
        loadProdukPicker(link.href);
    });

    // Reset search saat modal picker ditutup.
    document.addEventListener('hidden.bs.modal', function(e) {
        if (e.target.id !== 'ModalPilihProduk') return;

        const searchInput = getElement('picker-search-input');
        if (searchInput) searchInput.value = '';
    });
</script>
