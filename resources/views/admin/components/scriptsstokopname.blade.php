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
</script>
