<script>
    // Route helper (Blade route() tidak bisa dipanggil dari JS biasa)
    const routeProdukStore = "{{ route('admin.produk.store') }}";
    const routeProdukUpdateBase = "{{ route('admin.produk.update', ':id') }}";

    function setModalMode(mode, data = {}) {
        const form = document.getElementById('formProduk');
        const methodInput = document.getElementById('form-method');
        const title = document.getElementById('modalFormTitle');
        const subtitle = document.getElementById('modalFormSubtitle');
        const btnText = document.getElementById('btnSubmitText');
        const photoPreview = document.getElementById('photoPreview');

        form.reset();

        if (mode === 'create') {
            form.action = routeProdukStore;
            methodInput.value = 'POST';
            title.textContent = 'Tambah Produk';
            subtitle.textContent = 'Lengkapi data produk baru';
            btnText.textContent = 'Simpan Produk';
            photoPreview.src = "{{ asset('images/no-image.svg') }}";
        }

        if (mode === 'edit') {
            form.action = routeProdukUpdateBase.replace(':id', data.id);
            methodInput.value = 'PUT';
            title.textContent = 'Edit Produk';
            subtitle.textContent = 'Perbarui data ' + data.jenis_gas;
            btnText.textContent = 'Update Produk';

            document.getElementById('input-kode_produk').value = data.kode_produk ?? '';
            document.getElementById('input-jenis_gas').value = data.jenis_gas ?? '';
            document.getElementById('input-berat').value = data.berat ?? '';
            document.getElementById('input-satuan').value = data.satuan ?? '';
            document.getElementById('input-harga').value = data.harga ?? '';
            document.getElementById('input-deskripsi').value = data.deskripsi ?? '';
            document.getElementById('input-stok_isi').value = data.stok_isi ?? 0;
            document.getElementById('input-stok_kosong').value = data.stok_kosong ?? 0;
            document.getElementById('input-stok_pinjam').value = data.stok_pinjam ?? 0;

            console.log(data.foto)
            // Set foto
            if (data.foto) {
                photoPreview.src = data.foto;

            } else {
                photoPreview.src = "{{ asset('images/no-image.png') }}";
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {

        // Preview foto saat file dipilih
        const inputFoto = document.getElementById('inputFoto');
        const photoPreview = document.getElementById('photoPreview');

        if (inputFoto) {
            inputFoto.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran foto maksimal 2MB');
                        inputFoto.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        photoPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Reset form saat modal ditutup
        const modalForm = document.getElementById('ModalFormProduk');
        if (modalForm) {
            modalForm.addEventListener('hidden.bs.modal', function() {
                document.getElementById('formProduk').reset();
                photoPreview.src = "{{ asset('assets/img/no-image.svg') }}";
            });
        }

        // Modal detail produk (yang sudah ada sebelumnya)
        const modalProduk = document.getElementById('ModalProdukDetail');
        if (modalProduk) {
            modalProduk.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                document.getElementById('modal-kode').textContent = button.getAttribute('data-kode');
                document.getElementById('modal-nama').textContent = button.getAttribute('data-nama');
                document.getElementById('modal-berat').textContent = button.getAttribute('data-berat') +
                    ' ' + button.getAttribute('data-satuan');
                document.getElementById('modal-harga').textContent = 'Rp ' + button.getAttribute(
                    'data-harga');
                document.getElementById('modal-stokisi').textContent = button.getAttribute(
                    'data-stokisi');
                document.getElementById('modal-stokkosong').textContent = button.getAttribute(
                    'data-stokkosong');
                document.getElementById('modal-stokpinjam').textContent = button.getAttribute(
                    'data-stokpinjam');
                document.getElementById('modal-deskripsi').textContent = button.getAttribute(
                    'data-deskripsi');
                document.getElementById('modal-foto').src = button.getAttribute('data-foto');
            });
        }

        // Konfirmasi hapus
        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Yakin ingin menghapus produk ini?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
