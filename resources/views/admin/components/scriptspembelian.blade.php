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

    document.querySelectorAll('.payment-status-select:not(:disabled)').forEach(function(select) {
        select.addEventListener('change', async function() {
            const originalStatus = this.dataset.originalStatus;
            const nextStatus = this.dataset.nextValue;

            if (!nextStatus || this.value === originalStatus) {
                this.value = originalStatus;
                return;
            }

            if (!window.confirm(this.dataset.nextWarning ||
                    'Ubah status transaksi ini?')) {
                this.value = originalStatus;
                return;
            }

            this.disabled = true;

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
                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Status pembayaran gagal diperbarui.');
                }

                // Ambil ulang data dari server agar status tabel dan modal selalu sinkron.
                window.location.reload();
            } catch (error) {
                this.value = originalStatus;
                this.disabled = false;
                alert(error.message || 'Status pembayaran gagal diperbarui.');
            }
        });
    });
</script>
