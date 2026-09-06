{{-- ============================================================
     SATU SCRIPT GABUNGAN untuk:
     1) Mengisi modal detail pelanggan saat dibuka
     2) Update status pelanggan (dropdown) via AJAX
     ============================================================ --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = '{{ csrf_token() }}';

        const pelangganModal = document.getElementById('ModalPelanggan');

        if (pelangganModal) {
            pelangganModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const name = button.getAttribute('data-name') || '-';
                const email = button.getAttribute('data-email') || '-';
                const alamat = button.getAttribute('data-alamat') || '-';
                const role = button.getAttribute('data-role') || '-';
                const status = button.getAttribute('data-status');
                const fotoKtp = button.getAttribute('data-foto-ktp') || '';

                document.getElementById('detail-name').textContent = name;
                document.getElementById('detail-email').textContent = email;
                document.getElementById('detail-alamat').textContent = alamat;
                document.getElementById('detail-role').textContent = role;

                const isActive = (status || '').trim().toLowerCase() === 'aktif';
                const statusText = document.getElementById('detail-status');
                const statusBadge = document.getElementById('detail-status-badge');

                statusText.textContent = isActive ? 'Aktif' : 'Nonaktif';
                statusBadge.classList.remove('status-active', 'status-inactive');
                statusBadge.classList.add(isActive ? 'status-active' : 'status-inactive');

                const avatar = document.getElementById('detail-avatar');
                avatar.textContent = name.trim().charAt(0).toUpperCase() || '?';

                const thumb = document.getElementById('detail-ktp-thumb');
                const empty = document.getElementById('detail-ktp-empty');
                const ktpButton = document.getElementById('detail-ktp-btn');

                if (fotoKtp) {
                    thumb.src = fotoKtp;
                    thumb.classList.remove('d-none');
                    empty.classList.add('d-none');
                    ktpButton.dataset.ktpSrc = fotoKtp;
                    ktpButton.disabled = false;
                } else {
                    thumb.removeAttribute('src');
                    thumb.classList.add('d-none');
                    empty.classList.remove('d-none');
                    ktpButton.removeAttribute('data-ktp-src');
                    ktpButton.disabled = true;
                }
            });
        }

        /* ============================================
           2) UPDATE STATUS CUSTOMER (AJAX, dari tabel)
        ============================================ */
        document.querySelectorAll('.status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const url = this.getAttribute('data-url');
                const status = this.value; // 'aktif' | 'nonaktif'
                const el = this;
                const originalValue = status === 'aktif' ? 'nonaktif' : 'aktif';

                el.disabled = true;

                fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(async (res) => {
                        el.disabled = false;

                        if (!res.ok) {
                            const errText = await res.text();
                            console.error(`Update gagal — Status Code: ${res.status}`,
                                errText);
                            alert(
                                `Gagal update status (kode ${res.status}). Cek console untuk detail.`
                            );
                            el.value = originalValue;
                            return;
                        }

                        const data = await res.json();
                        if (data.success) {
                            el.classList.remove('status-active', 'status-inactive');
                            el.classList.add(status === 'aktif' ? 'status-active' :
                                'status-inactive');
                        } else {
                            alert('Gagal update status');
                            el.value = originalValue;
                        }
                    })
                    .catch((err) => {
                        el.disabled = false;
                        console.error('Fetch error:', err);
                        alert('Terjadi kesalahan koneksi. Cek console untuk detail.');
                        el.value = originalValue;
                    });
            });
        });
    });
</script>
