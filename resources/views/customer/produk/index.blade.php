@extends('layouts.customer.app')

@section('content')
    <main class="home-page">
        <div class="container py-5" style="min-height: 100vh">
            <div class="text-center mb-5">
                <h2 class="fw-bold mt-3">
                    Produk Kami
                </h2>

                <p class="text-secondary mx-auto" style="max-width: 650px;">
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

                                        <div class="product-card h-100">

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

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
            // CEK STATUS LOGIN
            // ==========================================
            const isLoggedIn = @json(auth()->check());


            // ==========================================
            // TOMBOL TAMBAH PRODUK
            // ==========================================
            document.querySelectorAll('.product-add-btn').forEach(function(button) {

                button.addEventListener('click', function() {

                    // ==========================================
                    // BELUM LOGIN
                    // ==========================================
                    if (!isLoggedIn) {
                        window.location.href = "{{ route('login') }}";
                        return;
                    }


                    // ==========================================
                    // SUDAH LOGIN
                    // ==========================================
                    const productId = this.dataset.productId;
                    const productName = this.dataset.productName;
                    const productVariant = this.dataset.productVariant;
                    const productPrice = this.dataset.productPrice;
                    const productStock = this.dataset.productStock;
                    const productImage = this.dataset.productImage;


                    // ==========================================
                    // DATA PRODUK
                    // ==========================================
                    console.log('Tambah ke keranjang:', {
                        productId,
                        productName,
                        productVariant,
                        productPrice,
                        productStock,
                        productImage
                    });


                    // ==========================================
                    // PROSES TAMBAH KE CART
                    // ==========================================
                    // Tambahkan proses AJAX / fetch cart kamu di sini

                });

            });

        });
    </script>
@endsection
