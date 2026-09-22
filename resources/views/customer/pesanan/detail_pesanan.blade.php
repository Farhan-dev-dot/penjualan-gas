@extends('layouts.customer.app')

@section('content')
    <div class="container py-3">

        <div class="pesanan-detail-wrap">

            {{-- ================= HEADER ================= --}}
            <div class="pesanan-detail-header">
                <div class="pesanan-flat-top-left">
                    <span class="pesanan-flat-kode">{{ $pembelian->kode_penjualan }}</span>
                    <span class="pesanan-flat-dot">&middot;</span>
                    <span class="pesanan-flat-date">{{ $pembelian->created_at->format('d M Y, H:i') }}</span>
                </div>

                @if (in_array($pembelian->payment_status, ['pending', 'process'], true) && $pembelian->expired_at)
                    @if ($pembelian->expired_at->isPast())
                        <span class="pesanan-expired-badge pesanan-expired-badge--danger">
                            Sudah kedaluwarsa
                        </span>
                    @else
                        <span id="expiredBadge" class="pesanan-expired-badge pesanan-expired-badge--warning"
                            data-expired-at="{{ $pembelian->expired_at->toIso8601String() }}">
                            Berlaku sampai {{ $pembelian->expired_at->format('d M Y, H:i') }}
                        </span>
                    @endif
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

                        <div class="pesanan-saya-penerima-item">
                            <div class="pesanan-saya-penerima-head">
                                <span class="pesanan-saya-penerima-icon"><i class="fa-regular fa-envelope fs-4"></i></span>
                                <span class="pesanan-saya-penerima-label">Email</span>
                            </div>
                            <div class="pesanan-saya-penerima-value">{{ $detailPenerima->email_penerima }}</div>
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

            const expiredAt = new Date(badge.dataset.expiredAt).getTime();
            let intervalHitungMundur = null;

            function ubahKeKedaluwarsa() {
                badge.classList.remove('pesanan-expired-badge--warning');
                badge.classList.add('pesanan-expired-badge--danger');
                badge.textContent = 'Sudah kedaluwarsa';
            }

            function perbaruiHitungMundur() {
                const sisa = expiredAt - Date.now();

                if (sisa <= 0) {
                    ubahKeKedaluwarsa();
                    if (intervalHitungMundur) {
                        clearInterval(intervalHitungMundur);
                    }
                    return;
                }

                const totalJam = Math.floor(sisa / 3600000);
                const hari = Math.floor(totalJam / 24);
                const jam = totalJam % 24;
                const menit = Math.floor((sisa % 3600000) / 60000);
                const detik = Math.floor((sisa % 60000) / 1000);

                badge.textContent = `${jam}j ${menit}m ${detik}d`;
            }

            if (expiredAt - Date.now() <= 0) {
                ubahKeKedaluwarsa();
                return;
            }

            perbaruiHitungMundur();
            intervalHitungMundur = setInterval(perbaruiHitungMundur, 1000);
        })();
    </script>
@endpush
