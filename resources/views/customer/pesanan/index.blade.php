@extends('layouts.customer.app')

@section('content')
    <div class="container py-3">

        {{-- ================= TABS STATUS ================= --}}
        <ul class="nav pesanan-saya-order-tabs bg-white border-bottom mb-3">

            <li class="nav-item">
                <a href="{{ route('user.pesanan') }}" class="nav-link {{ !$status ? 'active' : '' }}">
                    Semua
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.pesanan', ['status' => 'pending']) }}"
                    class="nav-link {{ $status === 'pending' ? 'active' : '' }}">
                    Belum Bayar
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.pesanan', ['status' => 'settlement']) }}"
                    class="nav-link {{ $status === 'settlement' ? 'active' : '' }}">
                    Selesai / Dibayar
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.pesanan', ['status' => 'cancel']) }}"
                    class="nav-link {{ $status === 'cancel' ? 'active' : '' }}">
                    Dibatalkan
                </a>
            </li>

        </ul>


        {{-- ================= LIST PESANAN (FLAT, BUKAN CARD PER ITEM) ================= --}}
        <div class="pesanan-flat-list">

            @forelse ($pesanan as $item)
                @php
                    $lastDetail = $item->details->last();
                    $pinjamDetail = $item->details->firstWhere('tipe_transaksi', 'pinjam');
                    $extraCount = max(0, $item->details->count() - 1);
                    $totalPesanan = $item->gross_amount;

                    $statusMap = [
                        'settlement' => ['label' => 'Selesai', 'class' => 'is-settlement'],
                        'menunggu_konfirmasi' => ['label' => 'Menunggu Konfirmasi', 'class' => 'is-menunggu-konfirmasi'],
                        'pending' => ['label' => 'Belum Bayar', 'class' => 'is-pending'],
                        'cancel' => ['label' => 'Dibatalkan', 'class' => 'is-cancel'],
                        'process' => ['label' => 'Diproses', 'class' => 'is-proses'],
                        'expire' => ['label' => 'Kedaluwarsa', 'class' => 'is-expire'],
                        'deny' => ['label' => 'Ditolak', 'class' => 'is-deny'],
                        'failure' => ['label' => 'Gagal', 'class' => 'is-failure'],
                    ];

                    $statusInfo = $statusMap[$item->payment_status] ?? [
                        'label' => ucfirst($item->payment_status),
                        'class' => '',
                    ];
                @endphp

                <div class="pesanan-flat-item">

                    {{-- ================= BARIS ATAS: kode, tanggal, status ================= --}}
                    <div class="pesanan-flat-top">
                        <div class="pesanan-flat-top-left">
                            <span class="pesanan-flat-kode">{{ $item->kode_pembelian }}</span>
                            <span class="pesanan-flat-dot">&middot;</span>
                            <span class="pesanan-flat-date">{{ $item->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <div class="pesanan-flat-status {{ $statusInfo['class'] }}">
                            <span class="pesanan-flat-status-dot"></span>
                            {{ $statusInfo['label'] }}
                        </div>
                    </div>

                    {{-- ================= BARIS UTAMA: produk + total + aksi ================= --}}
                    <div class="pesanan-flat-main">

                        @if ($lastDetail && $lastDetail->produk)
                            <img src="{{ $lastDetail->produk->foto ? asset('storage/' . $lastDetail->produk->foto) : asset('assets/img/no-image.svg') }}"
                                alt="Produk" class="pesanan-flat-thumb">

                            <div class="pesanan-flat-info">
                                <div class="pesanan-flat-title">{{ $lastDetail->produk->jenis_gas }}</div>
                                <div class="pesanan-flat-meta">
                                    {{ $lastDetail->produk->berat }} {{ $lastDetail->produk->satuan }}
                                    &middot;
                                    {{ $lastDetail->tipe_transaksi === 'refil' ? 'Refil' : ucwords(str_replace('_', ' ', $lastDetail->tipe_transaksi)) }}
                                    &middot;
                                    x{{ $lastDetail->jumlah }}
                                </div>
                                @if ($lastDetail->tipe_transaksi === 'pinjam')
                                    <div class="pesanan-flat-meta">
                                        Sewa {{ $lastDetail->durasi }} hari
                                        &middot; {{ $lastDetail->mulai_sewa?->format('d M Y') }}
                                        s/d {{ $lastDetail->akhir_sewa?->format('d M Y') }}
                                    </div>
                                @endif
                                @if ($extraCount > 0)
                                    <div class="pesanan-flat-extra">+{{ $extraCount }} produk lainnya</div>
                                @endif
                            </div>
                        @endif

                        <div class="pesanan-flat-right">
                            <div class="pesanan-flat-total-label">Total Pesanan</div>
                            <div class="pesanan-flat-total-value">
                                Rp{{ number_format($totalPesanan, 0, ',', '.') }}
                            </div>

                            <div class="pesanan-flat-actions">
                                @if ($lastDetail && $lastDetail->produk)
                                    <a href="{{ route('user.pesanan-detail', $item->id_pembelian) }}"
                                        class="btn-flat-outline btn-sm">
                                        Lihat Detail
                                    </a>
                                @endif

                                @if (in_array($item->payment_status, ['pending', 'process']))
                                    <a href="{{ route('user.pesanan.payment-token', $item->id_pembelian) }}"
                                        data-sync-url="{{ route('user.pesanan.sync-payment-status', $item->id_pembelian) }}"
                                        class="btn-flat-primary btn-sm js-pay-order">
                                        Bayar Sekarang
                                    </a>
                                @endif

                                @if ($pinjamDetail && $pinjamDetail->akhir_sewa && now()->lte($pinjamDetail->akhir_sewa) && in_array($item->payment_status, ['menunggu_konfirmasi', 'settlement']))
                                    <a href="{{ route('user.refill', $item->id_pembelian) }}" class="btn-flat-primary btn-sm">
                                        Refill Tabung
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

            @empty

                <div class="pesanan-flat-empty">
                    <div class="pesanan-saya-empty-icon mx-auto">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <p class="text-muted mb-0 mt-3">Belum ada pesanan.</p>
                </div>
            @endforelse

        </div>


        {{-- ================= PAGINATION ================= --}}
        <div class="mt-3">
            {{ $pesanan->links() }}
        </div>

    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        async function syncPaymentStatus(url) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                });

                if (!response.ok) return null;

                return await response.json();
            } catch (_) {
                // Webhook tetap menjadi sumber sinkronisasi utama bila koneksi browser terputus.
                return null;
            }
        }

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
                            // Status expire sudah disimpan oleh backend. Muat ulang agar
                            // tombol Bayar Sekarang tidak lagi ditampilkan.
                            if (data.payment_status === 'expire') {
                                window.location.reload();
                            }

                            throw new Error(data.message || 'Token pembayaran tidak tersedia.');
                        }

                        return data;
                    })
                    .then(function(data) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: async function() {
                                await syncPaymentStatus(button.dataset.syncUrl);
                                window.location.reload();
                            },
                            onPending: async function() {
                                await syncPaymentStatus(button.dataset.syncUrl);
                                window.location.reload();
                            },
                            onError: async function() {
                                await syncPaymentStatus(button.dataset.syncUrl);
                                alert('Pembayaran gagal, silakan coba lagi.');
                                window.location.reload();
                            },
                            onClose: async function() {
                                // Snap dapat menampilkan pesan "Transaksi sudah kedaluwarsa"
                                // saat popup ditutup. Sinkronkan agar status lokal menjadi expire
                                // dan tombol Bayar Sekarang hilang setelah halaman dimuat ulang.
                                const result = await syncPaymentStatus(button.dataset.syncUrl);

                                if (result?.payment_status === 'expire') {
                                    window.location.reload();
                                    return;
                                }

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
    </script>
@endsection
