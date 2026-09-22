<script>
    const modalPembelianDetail = document.getElementById('ModalPembelianDetail');

    modalPembelianDetail.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('pd-kode').textContent = button.getAttribute('data-kode');
        document.getElementById('pd-tanggal').textContent = button.getAttribute('data-tanggal');
        document.getElementById('pd-pembeli').textContent = button.getAttribute('data-pembeli');
        document.getElementById('pd-email').textContent = button.getAttribute('data-email');
        document.getElementById('pd-payment').textContent = button.getAttribute('data-payment');
        document.getElementById('pd-total').textContent = button.getAttribute('data-total');

        const statusEl = document.getElementById('pd-status');
        statusEl.textContent = button.getAttribute('data-status');
        statusEl.className = 'status-badge ' + button.getAttribute('data-statusclass');

        const items = JSON.parse(button.getAttribute('data-items') || '[]');
        const tbody = document.getElementById('pd-items-body');
        tbody.innerHTML = '';

        if (items.length === 0) {
            tbody.innerHTML =
                '<tr><td colspan="6" class="cell-muted text-center">Tidak ada rincian produk</td></tr>';
            return;
        }

        items.forEach(item => {
            const tipeLabel = item.tipe;
            const row = document.createElement('tr');
            row.innerHTML = `
                    <td class="cell-primary">${item.produk}</td>
                    <td class="cell-muted">${item.jumlah}</td>
                    <td class="cell-muted">${tipeLabel}</td>
                    <td class="cell-muted">${item.penerima ?? '-'}<br><span class="cell-muted small">${item.telepon ?? '-'}</span></td>
                    <td class="cell-muted small">${item.alamat ?? '-'}</td>
                    <td class="cell-primary text-end">Rp ${item.subtotal}</td>
                `;
            tbody.appendChild(row);
        });
    });

    const paymentStatusSelects = document.querySelectorAll('.payment-status-select:not(:disabled)');
    console.log('[payment-status] jumlah select ditemukan:', paymentStatusSelects.length);

    paymentStatusSelects.forEach(function(select) {
        console.log('[payment-status] binding select untuk kode:', select.dataset.originalStatus, '-> next:',
            select.dataset.nextValue, '| url:', select.dataset.url);

        // Warna select mengikuti pilihan yang sedang aktif (badge + pilihan berikutnya).
        function paintSelect(el) {
            const isNext = el.value === el.dataset.nextValue;
            const cls = isNext ? el.dataset.nextBadge : el.dataset.originalBadge;

            el.classList.remove(
                'text-bg-success',
                'text-bg-warning',
                'text-bg-primary',
                'text-bg-secondary',
                'text-bg-danger',
            );

            if (cls) {
                el.classList.add(cls);
            }
        }

        paintSelect(select);

        select.addEventListener('change', async function() {
            console.log('[payment-status] event change terpicu. value dipilih:', this.value);

            const originalStatus = this.dataset.originalStatus;
            const nextStatus = this.dataset.nextValue;

            console.log('[payment-status] originalStatus:', originalStatus,
                '| nextStatus (dataset):', nextStatus);

            if (!nextStatus || this.value === originalStatus) {
                console.warn(
                    '[payment-status] dibatalkan: nextStatus kosong atau value sama dengan originalStatus.'
                );
                this.value = originalStatus;
                paintSelect(this);
                return;
            }

            // Langsung tampilkan warna status tujuan sebelum dikonfirmasi.
            paintSelect(this);

            if (!window.confirm(this.dataset.nextWarning ||
                    'Ubah status transaksi ini?')) {
                console.log('[payment-status] dibatalkan oleh user (confirm ditolak).');
                this.value = originalStatus;
                paintSelect(this);
                return;
            }

            this.disabled = true;
            console.log('[payment-status] mengirim request ke:', this.dataset.url, 'payload:', {
                payment_status: nextStatus
            });

            try {
                const response = await fetch(this.dataset.url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({
                        payment_status: nextStatus
                    }),
                });

                console.log('[payment-status] response status:', response.status, response
                    .statusText);

                const data = await response.json();
                console.log('[payment-status] response body:', data);

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Status pembayaran gagal diperbarui.');
                }

                console.log('[payment-status] sukses, reload halaman...');
                // Ambil ulang data dari server agar status tabel dan modal selalu sinkron.
                window.location.reload();
            } catch (error) {
                console.error('[payment-status] ERROR:', error);
                this.value = originalStatus;
                paintSelect(this);
                this.disabled = false;
                alert(error.message || 'Status pembayaran gagal diperbarui.');
            }
        });
    });
</script>
