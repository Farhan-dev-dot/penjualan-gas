@extends('layouts.customer.app')

@section('content')
    <main class="home-page">
        <div class="container py-5" style="min-height: 100vh">
            <div class="text-center mb-5">
                <h2 class="produk-heading fw-bold mt-3">
                    Produk Kami
                </h2>

                <p class=" lead text-secondary mb-0 mx-auto text-secondary mx-auto" style="max-width: 650px;">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus, facere.
                </p>
            </div>

            <section class="filter-produk-bar">
                <div class="filter-produk-row">

                    {{-- Tombol utama: buka offcanvas filter lengkap --}}
                    <button class="btn filter-pill filter-pill-main" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasFilterProduk" aria-controls="offcanvasFilterProduk">
                        <i class="fa-solid fa-sliders"></i> Filter
                    </button>

                    {{-- Jenis Gas --}}
                    <div class="dropdown filter-pill-group">
                        <button
                            class="btn filter-pill dropdown-toggle {{ !empty($selectedJenisGas) ? 'filter-pill-active' : '' }}"
                            type="button" data-bs-toggle="dropdown">
                            {{ $selectedJenisGas[0] ?? 'Jenis Gas' }}
                        </button>
                        <ul class="dropdown-menu">
                            @forelse($jenisGasList ?? [] as $jenis)
                                <li>
                                    <a class="dropdown-item {{ in_array($jenis, $selectedJenisGas) ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['jenis_gas' => [$jenis]]) }}">
                                        {{ $jenis }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item-text text-muted small">Belum ada data</span></li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Harga --}}
                    <div class="dropdown filter-pill-group">
                        <button
                            class="btn filter-pill dropdown-toggle {{ request('harga_min') || request('harga_max') ? 'filter-pill-active' : '' }}"
                            type="button" data-bs-toggle="dropdown">
                            Harga
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['harga_min' => null, 'harga_max' => 25000]) }}">
                                    Di bawah Rp 25.000
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['harga_min' => 25000, 'harga_max' => 100000]) }}">
                                    Rp 25.000 – Rp 100.000
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['harga_min' => 100000, 'harga_max' => 500000]) }}">
                                    Rp 100.000 – Rp 500.000
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ request()->fullUrlWithQuery(['harga_min' => 500000, 'harga_max' => null]) }}">
                                    Di atas Rp 500.000
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Berat Tabung --}}
                    <div class="dropdown filter-pill-group">
                        <button
                            class="btn filter-pill dropdown-toggle {{ !empty($selectedBerat) ? 'filter-pill-active' : '' }}"
                            type="button" data-bs-toggle="dropdown">
                            Berat
                        </button>
                        <ul class="dropdown-menu">
                            @forelse($beratList ?? [] as $item)
                                <li>
                                    <a class="dropdown-item {{ in_array((string) $item->berat, $selectedBerat) ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['berat' => [$item->berat], 'satuan' => $item->satuan]) }}">
                                        {{ rtrim(rtrim(number_format($item->berat, 1, ',', '.'), '0'), ',') }}
                                        {{ $item->satuan }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item-text text-muted small">Belum ada data</span></li>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Tombol reset — muncul hanya kalau ada filter aktif --}}
                    @if (request()->hasAny(['jenis_gas', 'harga_min', 'harga_max', 'berat']))
                        <a href="{{ route('produk.index') }}" class="filter-pill-clear">
                            <i class="fa-solid fa-xmark"></i> Reset
                        </a>
                    @endif

                </div>
            </section>

            <div class="row g-4">
                <div class="col-lg-12">
                    <section class="produkall">
                        {{-- ============================= --}}
                        {{-- SKELETON --}}
                        {{-- ============================= --}}

                        <div id="produk-skeleton">

                            <div class="row g-4">

                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="col-6 col-md-6 col-lg-4 col-xl-3">

                                        <div class="product-card" aria-hidden="true">

                                            {{-- IMAGE --}}
                                            <div class="placeholder-glow">

                                                <span class="placeholder w-100 d-block product-skeleton-image">
                                                </span>

                                            </div>




                                            {{-- CONTENT --}}
                                            <div class="mt-3">

                                                {{-- TITLE --}}
                                                <div class="placeholder-glow mb-2">

                                                    <span class="placeholder col-12"></span>

                                                    <span class="placeholder col-8"></span>

                                                </div>


                                                {{-- CATEGORY --}}
                                                <div class="placeholder-glow">

                                                    <span class="placeholder col-6"></span>

                                                </div>



                                                {{-- PRICE --}}
                                                <div class="placeholder-glow mt-3">

                                                    <span class="placeholder col-5"></span>

                                                </div>


                                                {{-- BUTTON --}}
                                                <div class="placeholder-glow mt-3">

                                                    <span class="placeholder w-100 d-block" style="height: 40px;">
                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                @endfor

                            </div>

                        </div>

                        {{-- ============================= --}}
                        {{-- CARD PRODUK ASLI --}}
                        {{-- ============================= --}}

                        <div id="produk-content" class="d-none">

                            <div class="row g-2 g-md-4">

                                @forelse($produk as $item)
                                    <div class="col-6 col-md-6 col-lg-4 col-xl-3">

                                        {{-- Klik card membuka modal detail.
                                            Trigger TIDAK dipasang lewat data-bs-toggle supaya
                                            tombol Tambah di dalam card bisa dikecualikan. --}}
                                        <div class="product-card h-100 product-detail-card"
                                            data-product-id="{{ $item->id_produk }}"
                                            data-product-name="{{ $item->jenis_gas }}"
                                            data-product-variant="{{ rtrim(rtrim(number_format($item->berat, 1, ',', '.'), '0'), ',') }} {{ $item->satuan }}"
                                            data-product-price="{{ $item->harga }}"
                                            data-product-price-label="Rp {{ number_format($item->harga, 0, ',', '.') }}"
                                            data-product-stock="{{ $item->stok_isi }}"
                                            data-product-berat="{{ $item->berat }}"
                                            data-product-image="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/produk1.png') }}"
                                            data-product-desc="{{ $item->deskripsi ?: 'Tabung gas berkualitas, aman digunakan untuk kebutuhan rumah tangga maupun usaha. Sudah melalui pemeriksaan sebelum dikirim.' }}">

                                            {{-- IMAGE --}}
                                            <div class="product-image-wrapper">

                                                @if ($item->foto)
                                                    <img src="{{ asset('storage/' . $item->foto) }}" class="product-image"
                                                        alt="{{ $item->jenis_gas }}">
                                                @else
                                                    <img src="{{ asset('assets/img/produk1.png') }}" class="product-image"
                                                        alt="{{ $item->jenis_gas }}">
                                                @endif

                                            </div>


                                            {{-- PRODUCT INFO --}}
                                            <div class="mt-3 p-3">

                                                {{-- NAMA PRODUK --}}
                                                <h5 class="product-title">
                                                    {{ $item->jenis_gas }}
                                                </h5>


                                                {{-- KATEGORI --}}
                                                <div class="product-category ">



                                                    {{ rtrim(rtrim(number_format($item->berat, 1, ',', '.'), '0'), ',') }}
                                                    {{ $item->satuan }}

                                                </div>


                                                {{-- HARGA --}}
                                                <div class="product-price">

                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}

                                                </div>


                                                {{-- TAMBAH --}}
                                                <button type="button" class="btn btn-primary product-add-btn"
                                                    data-product-id="{{ $item->id_produk }}"
                                                    data-product-name="{{ $item->jenis_gas }}"
                                                    data-product-variant="{{ rtrim(rtrim(number_format($item->berat, 1, ',', '.'), '0'), ',') }} {{ $item->satuan }}"
                                                    data-product-price="{{ $item->harga }}"
                                                    data-product-stock="{{ $item->stok_isi }}"
                                                    data-product-image="{{ $item->foto ? asset('storage/' . $item->foto) : asset('assets/img/produk1.png') }}"
                                                    data-product-berat="{{ $item->berat }}"
                                                    data-login-url="{{ route('login') }}"
                                                    {{ $item->stok_isi <= 0 ? 'disabled' : '' }}>

                                                    <i class="fa-solid fa-cart-plus me-1"></i>

                                                    {{ $item->stok_isi > 0 ? 'Tambah' : 'Stok Habis' }}
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                @empty

                                    <div class="col-12">

                                        <div class="text-center py-5">

                                            <i class="fa-solid fa-box-open fa-3x text-secondary mb-3"></i>

                                            <h5>Produk tidak ditemukan</h5>

                                            <p class="text-secondary">
                                                Tidak ada produk yang sesuai dengan filter.
                                            </p>

                                        </div>

                                    </div>
                                @endforelse

                            </div>

                        </div>


                        <div class="mt-2">

                            <nav aria-label="Navigasi halaman produk">
                                <div class="d-flex justify-content-center">
                                    {{ $produk->links() }}
                                </div>
                            </nav>
                        </div>

                    </section>
                </div>

            </div>
        </div>

        <div class="offcanvas offcanvas-start offcanvas-filter-produk" tabindex="-1" id="offcanvasFilterProduk"
            aria-labelledby="offcanvasFilterProdukLabel">

            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasFilterProdukLabel">
                    <i class="bi bi-funnel-fill"></i> Filter Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <form action="{{ route('produk.index') }}" method="GET" id="formFilterProduk">
                <div class="offcanvas-body">

                    {{-- ================= URUTKAN ================= --}}
                    <div class="filter-section">
                        <label class="filter-section-label" for="sortFilter">Urutkan</label>
                        <select class="form-select filter-select" id="sortFilter" name="sort">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga
                                Terendah
                            </option>
                            <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga
                                Tertinggi</option>
                            <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Terlaris
                            </option>
                        </select>
                    </div>

                    {{-- ================= JENIS GAS ================= --}}
                    <div class="filter-section">
                        <div class="filter-section-label">Jenis Gas</div>
                        <div class="filter-pill-check-group">
                            @forelse($jenisGasList ?? [] as $jenis)
                                <div class="filter-pill-check">
                                    <input type="checkbox" class="btn-check" name="jenis_gas[]"
                                        id="jenis_{{ $loop->index }}" value="{{ $jenis }}"
                                        {{ in_array($jenis, $selectedJenisGas) ? 'checked' : '' }}>
                                    <label class="filter-check-label" for="jenis_{{ $loop->index }}">
                                        {{ $jenis }}
                                    </label>
                                </div>
                            @empty
                                <p class="filter-empty-text">Belum ada data jenis gas.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- ================= BERAT TABUNG ================= --}}
                    <div class="filter-section">
                        <div class="filter-section-label">Berat Tabung</div>
                        <div class="filter-pill-check-group">
                            @forelse($beratList ?? [] as $item)
                                <div class="filter-pill-check">
                                    <input type="checkbox" class="btn-check" name="berat[]"
                                        id="berat_{{ $loop->index }}" value="{{ $item->berat }}"
                                        {{ in_array((string) $item->berat, $selectedBerat) ? 'checked' : '' }}>
                                    <label class="filter-check-label" for="berat_{{ $loop->index }}">
                                        {{ rtrim(rtrim(number_format($item->berat, 1, ',', '.'), '0'), ',') }}
                                        {{ $item->satuan }}
                                    </label>
                                </div>
                            @empty
                                <p class="filter-empty-text">Belum ada data berat.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- ================= RENTANG HARGA ================= --}}
                    <div class="filter-section">
                        <div class="filter-section-label">Rentang Harga</div>
                        <div class="filter-price-range">
                            <div class="filter-price-input">
                                <span>Rp</span>
                                <input type="number" min="0" class="form-control" name="harga_min"
                                    placeholder="Minimum" value="{{ request('harga_min') }}">
                            </div>
                            <span class="filter-price-sep">—</span>
                            <div class="filter-price-input">
                                <span>Rp</span>
                                <input type="number" min="0" class="form-control" name="harga_max"
                                    placeholder="Maksimum" value="{{ request('harga_max') }}">
                            </div>
                        </div>
                    </div>

                    {{-- ================= KETERSEDIAAN STOK ================= --}}
                    <div class="filter-section">
                        <div class="filter-section-label">Ketersediaan Stok</div>
                        <div class="filter-pill-check-group">
                            @foreach ($stokOptions as $value => $label)
                                <div class="filter-pill-check">
                                    <input type="radio" class="btn-check" name="stok"
                                        id="stok_{{ $loop->index }}" value="{{ $value }}"
                                        {{ request('stok', '') == $value ? 'checked' : '' }}>
                                    <label class="filter-check-label" for="stok_{{ $loop->index }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="filter-hint-text">
                            <i class="bi bi-info-circle"></i>
                            Produk dengan stok habis otomatis disembunyikan dari pencarian.
                        </p>
                    </div>

                </div>

                {{-- ================= FOOTER AKSI ================= --}}
                <div class="offcanvas-filter-footer">
                    <a href="{{ route('produk.index') }}" class="btn btn-filter-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-filter-apply">
                        <i class="bi bi-check2"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- =========================================================
         MODAL DETAIL PRODUK
    ========================================================== --}}
    <div class="produk-modal-overlay" id="produkModalOverlay">
        <div class="produk-modal" role="dialog" aria-modal="true" aria-labelledby="produkModalTitle">
            <button type="button" class="produk-modal-close" id="produkModalClose" aria-label="Tutup">&times;</button>

            <div class="produk-modal-gallery">
                <div class="produk-modal-main-image">
                    <img id="produkModalImage" src="" alt="">
                </div>
            </div>

            <div class="produk-modal-info">
                <span class="produk-modal-category">Tabung Gas</span>

                <h2 class="produk-modal-title" id="produkModalTitle"></h2>

                <div class="produk-modal-meta">
                    <i class="fa-solid fa-box"></i>
                    <span id="produkModalStock"></span>
                </div>

                <div class="produk-modal-price" id="produkModalPrice"></div>

                <p class="produk-modal-desc" id="produkModalDesc"></p>

                <div class="produk-modal-section-label">Berat / Varian</div>
                <div class="produk-modal-variants">
                    <span class="produk-modal-variant active" id="produkModalVariant"></span>
                </div>

                <div class="produk-modal-footer">
                    <div class="cart-qty-control">
                        <button type="button" class="cart-qty-btn" id="produkModalQtyMinus">
                            <i class="fa-solid fa-minus"></i>
                        </button>

                        <input type="text" class="cart-qty-input" id="produkModalQty" value="1" readonly>

                        <button type="button" class="cart-qty-btn" id="produkModalQtyPlus">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                    <button type="button" class="produk-modal-add-btn" id="produkModalAddBtn">
                        <i class="fa-solid fa-cart-plus"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==========================================
            // HALAMAN PRODUK = TRANSAKSI BARU
            // ==========================================
            // Kalau user kembali ke sini (mis. membatalkan refill di checkout),
            // seluruh state refill dibersihkan supaya tidak ada sisa tipe_transaksi,
            // jenis_sewa, atau keranjang refill yang bocor ke pembelian berikutnya.
            if (window.RefillState) {
                window.RefillState.clear();
            }

            // ==========================================
            // SKELETON LOADING
            // ==========================================
            const skeleton = document.getElementById('produk-skeleton');
            const content = document.getElementById('produk-content');

            setTimeout(function() {
                skeleton.classList.add('d-none');
                content.classList.remove('d-none');
            }, 1500);


            // ==========================================
            // CEK STATUS LOGIN & KTP
            // ==========================================
            // Kunci yang sama dipakai cart offcanvas (layouts/customer/cart-script.blade.php),
            // supaya modal dan tombol Tambah menulis ke keranjang yang sama.
            const cartKey = window.cartKey || 'penjualan_gas_cart';

            const isLoggedIn = @json(auth()->check());
            const hasUploadedKtp = @json(auth()->check() && filled(auth()->user()?->foto_ktp));
            const loginUrl = @json(route('login'));
            const profileUrl = @json(route('user.profile'));

            function getCart() {
                try {
                    return JSON.parse(localStorage.getItem(cartKey)) || {};
                } catch (error) {
                    return {};
                }
            }

            /**
             * Tambah produk ke keranjang belanja utama.
             * Dipakai bersama oleh tombol Tambah di card dan tombol di modal.
             */
            function tambahKeKeranjang(produk, jumlah) {
                const cart = getCart();
                const kunci = produk.productId;
                const stok = Number(produk.productStock) || 0;
                const tambahan = Math.max(1, Number(jumlah) || 1);

                if (stok <= 0) {
                    return 0;
                }

                const item = cart[kunci] || {
                    id: kunci,
                    name: produk.productName,
                    variant: produk.productVariant,
                    price: Number(produk.productPrice),
                    stock: stok,
                    image: produk.productImage,
                    berat: Number(produk.productBerat),
                    quantity: 0,
                    is_pinjam: false,
                    tipe_transaksi: 'isi_ulang'
                };

                // Item ini selalu pembelian biasa, bukan refill.
                item.tipe_transaksi = 'isi_ulang';
                item.stock = stok;

                const sebelum = Number(item.quantity) || 0;

                item.quantity = Math.min(sebelum + tambahan, stok);
                cart[kunci] = item;

                localStorage.setItem(cartKey, JSON.stringify(cart));

                // renderCart() ada di cart offcanvas. Panggil kalau tersedia
                // supaya badge & isi offcanvas langsung ikut ter-update.
                window.renderCart?.();

                return item.quantity - sebelum;
            }

            // ==========================================
            // MODAL DETAIL PRODUK (KLIK CARD)
            // ==========================================
            // Dibuka dari JS, bukan `data-bs-toggle`, supaya klik tombol Tambah
            // di dalam card tidak ikut membuka modal.
            const modalOverlay = document.getElementById('produkModalOverlay');
            const modalImage = document.getElementById('produkModalImage');
            const modalQty = document.getElementById('produkModalQty');
            const modalAddBtn = document.getElementById('produkModalAddBtn');

            let produkAktif = null;

            function bukaModal(card) {
                produkAktif = card.dataset;

                const nama = produkAktif.productName || 'Produk';
                const stok = Number(produkAktif.productStock || 0);

                modalImage.src = produkAktif.productImage || '';
                modalImage.alt = nama;

                document.getElementById('produkModalTitle').textContent = nama;
                document.getElementById('produkModalVariant').textContent =
                    produkAktif.productVariant || '-';

                document.getElementById('produkModalPrice').textContent =
                    produkAktif.productPriceLabel || '';

                document.getElementById('produkModalDesc').textContent =
                    produkAktif.productDesc || '';

                document.getElementById('produkModalStock').textContent =
                    stok > 0 ? 'Stok tersedia: ' + stok : 'Stok habis';

                modalQty.value = 1;

                modalAddBtn.disabled = stok <= 0;

                modalOverlay.classList.add('is-open');
                document.body.classList.add('modal-open-lock');
            }

            function tutupModal() {
                modalOverlay.classList.remove('is-open');
                document.body.classList.remove('modal-open-lock');
            }

            /**
             * Hitung lebar scrollbar dan simpan sebagai CSS variable.
             * Dipakai body.modal-open-lock untuk mengganjal ruang yang hilang
             * saat overflow: hidden dipasang, agar navbar tidak melebar.
             */
            function setKompensasiScrollbar() {
                const lebar = window.innerWidth - document.documentElement.clientWidth;

                document.documentElement.style.setProperty(
                    '--scrollbar-compensation',
                    lebar > 0 ? lebar + 'px' : '0px'
                );
            }

            setKompensasiScrollbar();
            window.addEventListener('resize', setKompensasiScrollbar);

            document.querySelectorAll('.product-detail-card').forEach(function(card) {

                card.addEventListener('click', function(event) {

                    // Tombol Tambah punya aksi sendiri, jangan buka modal.
                    if (event.target.closest('.product-add-btn')) {
                        return;
                    }

                    // Klik pada elemen interaktif lain (mis. tombol tutup) juga
                    // tidak boleh membuka modal.
                    if (event.target.closest('button, a, input, select')) {
                        return;
                    }

                    bukaModal(card);
                });

            });

            document.getElementById('produkModalClose')
                .addEventListener('click', tutupModal);

            // Klik area gelap di luar modal menutup modal.
            modalOverlay.addEventListener('click', function(event) {
                if (event.target === modalOverlay) {
                    tutupModal();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    tutupModal();
                }
            });

            // ==========================================
            // QTY DI DALAM MODAL
            // ==========================================
            // Batas atas qty mengikuti stok produk yang sedang dibuka.
            function maksimalQty() {
                return Math.max(1, Number(produkAktif?.productStock || 0) || 1);
            }

            function setQty(nilai) {
                const angka = Math.min(maksimalQty(), Math.max(1, Number(nilai) || 1));

                modalQty.value = angka;
            }

            document.getElementById('produkModalQtyMinus').addEventListener('click', function() {
                setQty((Number(modalQty.value) || 1) - 1);
            });

            document.getElementById('produkModalQtyPlus').addEventListener('click', function() {
                setQty((Number(modalQty.value) || 1) + 1);
            });

            // Qty tidak boleh diketik manual, tapi tetap dijaga kalau nilainya diubah.
            modalQty.addEventListener('change', function() {
                setQty(modalQty.value);
            });

            // ==========================================
            // TAMBAH DARI DALAM MODAL
            // ==========================================
            modalAddBtn.addEventListener('click', function() {
                if (!produkAktif || modalAddBtn.disabled) {
                    return;
                }

                if (!isLoggedIn) {
                    window.location.href = loginUrl;
                    return;
                }

                if (!hasUploadedKtp) {
                    alert('Tolong lengkapi profil dan unggah foto KTP terlebih dahulu.');
                    window.location.href = profileUrl;
                    return;
                }

                const ditambahkan = tambahKeKeranjang(produkAktif, modalQty.value);

                if (ditambahkan <= 0) {
                    alert('Stok produk ini sudah mencapai batas maksimal di keranjang.');
                    return;
                }

                tutupModal();
            });

            // ==========================================
            // TOMBOL TAMBAH PRODUK (DI CARD)
            // ==========================================
            document.querySelectorAll('.product-add-btn').forEach(function(button) {

                button.addEventListener('click', function(event) {

                    // Hentikan bubbling supaya handler global di cart-script
                    // tidak ikut menambahkan item yang sama.
                    event.stopPropagation();

                    // Klik ini sudah ditangani penuh di sini.
                    event.preventDefault();

                    if (!isLoggedIn) {
                        window.location.href = loginUrl;
                        return;
                    }

                    if (!hasUploadedKtp) {
                        alert('Tolong lengkapi profil dan unggah foto KTP terlebih dahulu.');
                        window.location.href = profileUrl;
                        return;
                    }

                    const ditambahkan = tambahKeKeranjang(this.dataset, 1);

                    if (ditambahkan <= 0) {
                        alert('Stok produk ini sudah mencapai batas maksimal di keranjang.');
                    }

                });

            });

        });
    </script>
@endsection
