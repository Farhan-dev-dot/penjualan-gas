<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ==============================
        // Tampilkan baris produk sesuai Pembelian yang dipilih
        // ==============================
        const selectPembelian = document.getElementById('select-penjualan-keluar');
        const tbody = document.getElementById('barang-keluar-body');
        const hintPilihDulu = document.getElementById('hint-pilih-dulu');

        function resetAllRows() {
            if (!tbody) return;
            tbody.querySelectorAll('tr.row-item').forEach(function(row) {
                row.style.display = 'none';
                row.querySelectorAll('.item-input').forEach(function(input) {
                    input.disabled = true;
                });
            });
        }

        function showRowsForPembelian(idPembelian) {
            resetAllRows();
            if (!idPembelian) return;

            const rows = tbody.querySelectorAll('.row-penjualan-' + idPembelian);
            rows.forEach(function(row) {
                row.style.display = '';
                row.querySelectorAll('.item-input').forEach(function(input) {
                    input.disabled = false;
                });
            });

            if (hintPilihDulu) {
                hintPilihDulu.style.display = rows.length ? 'none' : '';
            }
        }

        if (selectPembelian) {
            selectPembelian.addEventListener('change', function() {
                showRowsForPembelian(this.value);
            });
        }

        // Reset form & tampilan setiap kali modal Tambah dibuka
        const modalTambah = document.getElementById('ModalBarangKeluar');
        if (modalTambah) {
            modalTambah.addEventListener('show.bs.modal', function() {
                if (selectPembelian) selectPembelian.value = '';
                resetAllRows();
                if (hintPilihDulu) hintPilihDulu.style.display = '';
            });
        }

        // Validasi ringan: minimal harus pilih pembelian sebelum submit
        const formBarangKeluar = document.getElementById('form-barang-keluar');
        if (formBarangKeluar) {
            formBarangKeluar.addEventListener('submit', function(e) {
                if (!selectPembelian || !selectPembelian.value) {
                    e.preventDefault();
                    alert('Silakan pilih pembelian terlebih dahulu.');
                }
            });
        }

        // ==============================
        // MODAL DETAIL: Isi dari atribut data-* tombol "Detail"
        // ==============================
        const modalDetail = document.getElementById('ModalDetailBarangKeluar');

        if (modalDetail) {
            modalDetail.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                document.getElementById('detail-bk-kode').textContent = button.getAttribute(
                    'data-kode') || '-';
                document.getElementById('detail-bk-penerima').textContent = button.getAttribute(
                    'data-penerima') || '-';
                document.getElementById('detail-bk-telepon').textContent = button.getAttribute(
                    'data-telepon') || '-';
                document.getElementById('detail-bk-alamat').textContent = button.getAttribute(
                    'data-alamat') || '-';
                document.getElementById('detail-bk-tanggal').textContent = button.getAttribute(
                    'data-tanggal') || '-';

                const itemsBody = document.getElementById('detail-bk-items');
                const items = JSON.parse(button.getAttribute('data-items') || '[]');
                itemsBody.replaceChildren();

                items.forEach(function(item) {
                    const row = document.createElement('tr');
                    [item.produk, item.tipe, item.isi, item.kosong, item.pinjam].forEach(function(value, index) {
                        const cell = document.createElement('td');
                        cell.textContent = value;
                        if (index >= 2) cell.classList.add('text-center');
                        row.appendChild(cell);
                    });

                    itemsBody.appendChild(row);
                });
            });
        }

    });
</script>
