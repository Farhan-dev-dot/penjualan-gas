@extends('layouts.customer.app')

@section('content')
    <main class="py-5" style="min-height: 100vh; background-color: #f8f9fa;">
        <div class="container">
            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1">Refill Tabung</h2>
                <p class="text-secondary mb-0">Pilih produk untuk mengisi tabung yang kosong selama masa sewa aktif.</p>
            </div>

            <form id="refill-form">
                <div class="refill-flat-list">
                    @forelse ($sewaDetails as $sewa)
                        @php $isDisabled = $sewa->maksimal_refill < 1; @endphp
                        <div class="refill-flat-item {{ $isDisabled ? 'is-disabled' : '' }}"
                            data-detail-id="{{ $sewa->id_detail }}">

                            <div class="refill-flat-select">
                                <input class="refill-select-input" type="checkbox" id="select-{{ $sewa->id_detail }}"
                                    data-detail-id="{{ $sewa->id_detail }}" {{ $isDisabled ? 'disabled' : '' }}>
                                <label for="select-{{ $sewa->id_detail }}" class="refill-select-box"></label>
                            </div>

                            <img src="{{ $sewa->produk?->foto ? asset('storage/' . $sewa->produk->foto) : asset('assets/img/produk1.png') }}"
                                class="refill-flat-thumb" alt="{{ $sewa->produk?->jenis_gas ?? 'Produk' }}">

                            <div class="refill-flat-info">
                                <div class="refill-flat-top">
                                    <span class="refill-flat-kode">{{ $sewa->pembelian->kode_pembelian }}</span>
                                    <span class="refill-flat-dot">&bull;</span>
                                    <span class="refill-flat-badge">Sewa Aktif</span>
                                </div>
                                <div class="refill-flat-title">{{ $sewa->produk?->jenis_gas ?? 'Produk' }}</div>
                                <div class="refill-flat-meta">
                                    {{ $sewa->produk?->berat }} {{ $sewa->produk?->satuan }}
                                    &bull; {{ $sewa->jumlah }} tabung disewa
                                    &bull;
                                    {{ $sewa->mulai_sewa?->format('d M Y') }}–{{ $sewa->akhir_sewa?->format('d M Y') }}
                                </div>
                            </div>

                            <div class="refill-flat-qty">
                                <span class="refill-flat-qty-label">Maks. {{ $sewa->maksimal_refill }}</span>
                                <div class="cart-qty-control">
                                    <button type="button" class="cart-qty-btn refill-qty-dec"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="number" id="refill-{{ $sewa->id_detail }}"
                                        class="cart-qty-input refill-quantity" min="1" step="1"
                                        max="{{ $sewa->maksimal_refill }}"
                                        value="{{ $sewa->maksimal_refill > 0 ? 1 : 0 }}"
                                        data-detail-id="{{ $sewa->id_detail }}" data-product-id="{{ $sewa->id_produk }}"
                                        data-name="{{ $sewa->produk?->jenis_gas ?? 'Produk' }}"
                                        data-variant="{{ $sewa->produk?->berat }} {{ $sewa->produk?->satuan }}"
                                        data-price="{{ $sewa->produk?->harga ?? 0 }}"
                                        data-image="{{ $sewa->produk?->foto ? asset('storage/' . $sewa->produk->foto) : asset('assets/img/produk1.png') }}"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    <button type="button" class="cart-qty-btn refill-qty-inc"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="refill-flat-empty">
                            <i class="fa-solid fa-box-open fa-2x mb-3 text-secondary"></i>
                            <p class="mb-0 text-secondary">Tidak ada sewa aktif yang dapat direfill.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa-solid fa-cart-plus me-2"></i>Lanjut ke Checkout Refill
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        (function() {
            const list = document.querySelector('.refill-flat-list');
            if (!list) return;

            // klik baris (di luar area qty) -> toggle pilih
            list.querySelectorAll('.refill-flat-item').forEach(function(item) {
                if (item.classList.contains('is-disabled')) return;

                const checkbox = item.querySelector('.refill-select-input');

                item.addEventListener('click', function(e) {
                    if (e.target.closest('.refill-flat-qty') || e.target.closest('.refill-flat-select'))
                        return;
                    checkbox.checked = !checkbox.checked;
                    item.classList.toggle('is-selected', checkbox.checked);
                });

                checkbox.addEventListener('change', function() {
                    item.classList.toggle('is-selected', checkbox.checked);
                });
            });

            // tombol +/- qty
            list.querySelectorAll('.refill-flat-qty').forEach(function(qtyWrap) {
                const input = qtyWrap.querySelector('.refill-quantity');
                const dec = qtyWrap.querySelector('.refill-qty-dec');
                const inc = qtyWrap.querySelector('.refill-qty-inc');
                if (!input) return;

                dec.addEventListener('click', function() {
                    const val = Math.max(Number(input.min) || 1, Number(input.value) - 1);
                    input.value = val;
                });
                inc.addEventListener('click', function() {
                    const val = Math.min(Number(input.max) || 1, Number(input.value) + 1);
                    input.value = val;
                });
            });
        })();

        document.getElementById('refill-form')?.addEventListener('submit', function(event) {
            event.preventDefault();

            const selected = Array.from(document.querySelectorAll('.refill-select-input:checked'));
            if (!selected.length) {
                alert('Pilih minimal satu produk untuk direfill.');
                return;
            }

            const cart = {};
            selected.forEach(function(checkbox) {
                const quantityInput = document.querySelector(
                    '.refill-quantity[data-detail-id="' + checkbox.dataset.detailId + '"]');
                const quantity = Number(quantityInput.value);
                const max = Number(quantityInput.max);

                if (!quantity || quantity < 1 || quantity > max) return;

                cart['refil-' + checkbox.dataset.detailId] = {
                    id: 'refil-' + checkbox.dataset.detailId,
                    id_produk: quantityInput.dataset.productId,
                    name: quantityInput.dataset.name,
                    variant: quantityInput.dataset.variant,
                    price: Number(quantityInput.dataset.price),
                    image: quantityInput.dataset.image,
                    quantity: quantity,
                    stock: quantity,
                    berat: 0,
                    is_pinjam: false,
                    tipe_transaksi: 'refil',
                };
            });

            if (!Object.keys(cart).length) {
                alert('Jumlah refill tidak valid.');
                return;
            }

            // Refill memakai cart sementara agar tidak mengisi cart belanja utama.
            sessionStorage.setItem('penjualan_gas_refill_checkout', JSON.stringify(cart));
            window.location.href = @json(route('user.checkout'));
        });
    </script>
@endsection
