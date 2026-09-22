@extends('layouts.customer.app')

@section('content')
    <div class="container py-3">

        <div class="pesanan-detail-wrap">

            {{-- ================= HEADER ================= --}}
            <div class="pesanan-detail-header">
                <div class="pesanan-detail-header-row">
                    <div class="pesanan-flat-top-left">
                        <span class="pesanan-flat-kode">{{ $pembelian->kode_penjualan }}</span>
                        <span class="pesanan-flat-dot">&middot;</span>
                        <span class="pesanan-flat-date">{{ $pembelian->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    @if (in_array($pembelian->payment_status, ['pending', 'process'], true) && $pembelian->expired_at)
                        @if ($pembelian->expired_at->isPast())
                            <div class="pesanan-expired-chip pesanan-expired-chip--danger">
                                <span class="pesanan-expired-chip-icon">
                                    <i class="fa-regular fa-clock"></i>
                                </span>
                                <span class="pesanan-expired-chip-body">
                                    <span class="pesanan-expired-chip-label">Status Pembayaran</span>
                                    <span class="pesanan-expired-chip-text">Sudah kedaluwarsa</span>
                                </span>
                            </div>
                        @else
                            <div id="expiredBadge" class="pesanan-expired-chip pesanan-expired-chip--warning"
                                data-expired-at="{{ $pembelian->expired_at->toIso8601String() }}">
                                <span class="pesanan-expired-chip-icon">
                                    <i class="fa-regular fa-clock"></i>
                                    <span class="pesanan-expired-chip-pulse"></span>
                                </span>
                                <span class="pesanan-expired-chip-body">
                                    <span class="pesanan-expired-chip-label">Bayar sebelum</span>
                                    <span class="pesanan-expired-chip-text" id="expiredCountdownText">
                                        {{ $pembelian->expired_at->format('d M Y, H:i') }}
                                    </span>
                                </span>
                            </div>
                        @endif
                    @endif
                </div>

                @if (in_array($pembelian->payment_status, ['pending', 'process'], true))
                    <div class="pesanan-flat-actions mt-3">
                        <button type="button" class="btn-flat-outline btn-sm btn-batalkan-pesanan"
                            data-url="{{ route('user.pesanan.batalkan', $pembelian->id_penjualan) }}">
                            Batalkan
                        </button>
                        <a href="{{ route('user.pesanan.payment-token', $pembelian->id_penjualan) }}"
                            data-sync-url="{{ route('user.pesanan.sync-payment-status', $pembelian->id_penjualan) }}"
                            class="btn-flat-primary btn-sm js-pay-order">
                            Bayar Sekarang
                        </a>
                    </div>
                @endif
            </div>


            {{-- =====================================================
                SEMUA PRODUK DALAM PEMBELIAN
            ====================================================== --}}

            <div class="pesanan-detail-section-label">Produk Dipesan</div>

            <div class="pesanan-detail-products">
                @forelse ($pembelian->details as $detail)
                    @if ($detail->produk)
                        <div class="pesanan-detail-product-row">

                            <img src="{{ $detail->produk->foto ? asset('storage/' . $detail->produk->foto) : asset('images/no-image.png') }}"
                                alt="Produk" class="pesanan-flat-thumb">

                            <div class="pesanan-flat-info">
                                <div class="pesanan-flat-title">{{ $detail->produk->jenis_gas }}</div>

                                <div class="pesanan-flat-meta">
                                    Kode: {{ $detail->produk->kode_produk }}
                                </div>

                                <div class="pesanan-flat-meta">
                                    {{ $detail->produk->berat }} {{ $detail->produk->satuan }}
                                    &middot;
                                    {{ $detail->tipe_transaksi === 'refil' ? 'Refil' : ucwords(str_replace('_', ' ', $detail->tipe_transaksi)) }}
                                    &middot;
                                    x{{ $detail->jumlah }}
                                </div>

                                @if ($detail->tipe_transaksi === 'pinjam')
                                    <div class="pesanan-flat-meta">
                                        Durasi sewa: {{ $detail->durasi }} hari
                                        &middot; {{ $detail->mulai_sewa?->format('d M Y') }}
                                        s/d {{ $detail->akhir_sewa?->format('d M Y') }}
                                    </div>
                                    <div class="pesanan-flat-meta">
                                        Refill: {{ $detail->jumlah_refill ?? 0 }} kali
                                        ({{ $detail->jumlah_tabung_refill ?? 0 }} tabung)
                                    </div>
                                @endif
                            </div>

                            <div class="pesanan-detail-product-subtotal">
                                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                            </div>

                        </div>
                    @endif
                @empty
                    <div class="pesanan-flat-empty py-4">
                        Tidak ada produk dalam pesanan ini.
                    </div>
                @endforelse
            </div>


            {{-- =====================================================
                INFORMASI PENERIMA
                Layout: icon + label sejajar (flex), value di baris bawahnya
            ====================================================== --}}

            @php
                $detailPenerima = $pembelian->details->first();
            @endphp

            @if ($detailPenerima)
                <div class="pesanan-detail-section-label border-top">Informasi Penerima</div>

                <div class="pesanan-detail-penerima">
                    <div class="pesanan-detail-penerima-grid">

                        <div class="pesanan-saya-penerima-item">
                            <div class="pesanan-saya-penerima-head">
                                <span class="pesanan-saya-penerima-icon"><i class="fa-regular fa-user fs-4"></i></span>
                                <span class="pesanan-saya-penerima-label">Nama Penerima</span>
                            </div>
                            <div class="pesanan-saya-penerima-value">{{ $detailPenerima->nama_penerima }}</div>
                        </div>

                        <div class="pesanan-saya-penerima-item">
                            <div class="pesanan-saya-penerima-head">
                                <span class="pesanan-saya-penerima-icon"><i class="fa-solid fa-phone fs-4"></i></span>
                                <span class="pesanan-saya-penerima-label">Telepon</span>
                            </div>
                            <div class="pesanan-saya-penerima-value">{{ $detailPenerima->telepon_penerima }}</div>
                        </div>


                        <div class="pesanan-saya-penerima-item pesanan-saya-penerima-item--full">
                            <div class="pesanan-saya-penerima-head">
                                <span class="pesanan-saya-penerima-icon"><i
                                        class="fa-solid fa-location-dot fs-4"></i></span>
                                <span class="pesanan-saya-penerima-label">Alamat</span>
                            </div>
                            <div class="pesanan-saya-penerima-value">
                                {{ $detailPenerima->alamat_penerima }},
                                {{ $detailPenerima->kelurahan }},
                                {{ $detailPenerima->kecamatan }},
                                {{ $detailPenerima->kota }},
                                {{ $detailPenerima->provinsi }}
                            </div>
                        </div>

                        @if ($detailPenerima->catatan)
                            <div class="pesanan-saya-penerima-item pesanan-saya-penerima-item--full">
                                <div class="pesanan-saya-penerima-head">
                                    <span class="pesanan-saya-penerima-icon"><i
                                            class="fa-regular fa-note-sticky"></i></span>
                                    <span class="pesanan-saya-penerima-label">Catatan</span>
                                </div>
                                <div class="pesanan-saya-penerima-value">{{ $detailPenerima->catatan }}</div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif


            {{-- =====================================================
                TOTAL PESANAN
            ====================================================== --}}

            <div class="pesanan-detail-footer">
                <span class="pesanan-flat-total-label">Total Pesanan</span>
                <span class="pesanan-detail-footer-value">
                    Rp{{ number_format($pembelian->gross_amount, 0, ',', '.') }}
                </span>
            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        (function() {
            const badge = document.getElementById('expiredBadge');
            if (!badge) return;

            const countdownText = document.getElementById('expiredCountdownText');
            const expiredAt = new Date(badge.dataset.expiredAt).getTime();
            let intervalHitungMundur = null;

            function ubahKeKedaluwarsa() {
                badge.classList.remove('pesanan-expired-chip--warning');
                badge.classList.add('pesanan-expired-chip--danger');
                badge.querySelector('.pesanan-expired-chip-pulse')?.remove();
                badge.querySelector('.pesanan-expired-chip-label').textContent = 'Status Pembayaran';
                countdownText.textContent = 'Sudah kedaluwarsa';
            }

            function perbaruiHitungMundur() {
                const sisa = expiredAt - Date.now();

                if (sisa <= 0) {
                    ubahKeKedaluwarsa();
                    if (intervalHitungMundur) clearInterval(intervalHitungMundur);
                    return;
                }

                const totalJam = Math.floor(sisa / 3600000);
                const hari = Math.floor(totalJam / 24);
                const jam = totalJam % 24;
                const menit = Math.floor((sisa % 3600000) / 60000);
                const detik = Math.floor((sisa % 60000) / 1000);

                const bagianHari = hari > 0 ? `${hari}h ` : '';
                countdownText.textContent = `${bagianHari}${jam}j ${menit}m ${detik}d lagi`;
            }

            if (expiredAt - Date.now() <= 0) {
                ubahKeKedaluwarsa();
                return;
            }

            perbaruiHitungMundur();
            intervalHitungMundur = setInterval(perbaruiHitungMundur, 1000);
        })();
    </script>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.querySelectorAll('.js-pay-order').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                button.classList.add('disabled');

                fetch(button.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(async function(response) {
                        const data = await response.json();

                        if (!response.ok) {
                            if (data.payment_status === 'expire') {
                                window.location.reload();
                            }

                            throw new Error(data.message || 'Token pembayaran tidak tersedia.');
                        }

                        return data;
                    })
                    .then(function(data) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function() {
                                window.location.reload();
                            },
                            onPending: function() {
                                window.location.reload();
                            },
                            onError: function() {
                                alert('Pembayaran gagal, silakan coba lagi.');
                                window.location.reload();
                            },
                            onClose: function() {
                                button.classList.remove('disabled');
                            }
                        });
                    })
                    .catch(function(error) {
                        alert(error.message);
                        button.classList.remove('disabled');
                    });
            });
        });

        // Batalkan pesanan: pakai event delegation agar tetap bekerja
        // untuk elemen apa pun yang memakai class .btn-batalkan-pesanan.
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.btn-batalkan-pesanan');
            if (!button) return;

            event.preventDefault();

            if (!confirm('Batalkan pesanan ini?')) return;

            const teksAsli = button.textContent.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ??
                @json(csrf_token());

            button.disabled = true;
            button.textContent = 'Memproses...';

            fetch(button.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(async function(response) {
                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Pesanan tidak dapat dibatalkan.');
                    }

                    return data;
                })
                .then(function() {
                    window.location.reload();
                })
                .catch(function(error) {
                    alert(error.message);
                    button.disabled = false;
                    button.textContent = teksAsli;
                });
        });
    </script>
@endpush
