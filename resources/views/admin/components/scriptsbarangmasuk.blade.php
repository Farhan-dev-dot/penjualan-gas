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
                const produk = row.querySelector('[name*="[id_produk]"]').value;

                if (!produk) {
                    alert(`Baris ${index + 1}: Pilih produk terlebih dahulu.`);
                    isValid = false;
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
