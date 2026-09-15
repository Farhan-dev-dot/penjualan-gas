@extends('layouts.customer.app')

@section('content')
    <main class="home-page">

        <div class="home-bg-group-1">
            <!-- Hero Section -->
            <div class="container py-2">
                <section class="home-bg-hero">
                    <div class="container">
                        <div class="row align-items-center g-5">
                            <div class="col-lg-6">


                                <h1 class="mb-4">
                                    Solusi Gas untuk Bisnis Anda
                                    <span>Cepat, Tepat, dan Terpercaya</span>
                                </h1>

                                <p class="lead mb-4">
                                    Pastikan kebutuhan gas bisnis Anda selalu terpenuhi. Kami menghadirkan
                                    layanan distribusi yang cepat, harga kompetitif, dan dukungan pelanggan
                                    profesional untuk berbagai kebutuhan industri maupun komersial.
                                </p>

                                <div class="d-flex flex-wrap gap-3 mb-5">
                                    <a href="{{ route('produk.index') }}" class="btn btn-primary px-4">
                                        Lihat Produk
                                        <i class="fa-solid fa-arrow-right ms-2"></i>
                                    </a>
                                    <a href="#hubungin-kami" class="btn btn-outline-primary px-4">
                                        Kontak Kami
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="hero-image-wrap">
                                    <div class="hero-blob"></div>
                                    <div class="card border-0 hero-card">
                                        <div class="card-body p-3">
                                            <img src="{{ asset('assets/img/home.png') }}" class="img-fluid rounded-3"
                                                alt="Gas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Products Section -->
            <section class="section-produk-kami home-bg-produk">
                <div class="container produk-container">
                    <h2 class="produk-heading">Produk Terbaik Kami</h2>
                    <p class="lead text-secondary mb-0 mx-auto" style="max-width: 600px; text-align: center;">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Doloribus, ea.
                    </p>

                    <div id="produk-skeleton">
                        <div class="produk-grid">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="product-card" aria-hidden="true" style="animation-delay: 0s; opacity:1;">
                                    <span class="product-skeleton-image"></span>
                                    <div class="product-skeleton-body">
                                        <div class="bar" style="width:40%"></div>
                                        <div class="bar" style="width:90%"></div>
                                        <div class="bar" style="width:60%"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div id="produk-content" class="d-none">
                        <div class="produk-grid">

                            <div class="product-card">
                                <div class="product-card-image-wrap">
                                    <img src="{{ asset('assets/img/produk1.png') }}" class="product-card-image"
                                        alt="Armix / Arcil">
                                    <div class="shine"></div>
                                </div>
                                <div class="product-card-body">
                                    <h5 class="product-card-title">Gas CO₂ 23 Kg</h5>
                                    <div><span class="product-card-price">Mulai dari Rp880.000 / Tabung</span></div>
                                    <div class="product-card-action">
                                        <a href="#" class="btn-produk">
                                            Pesan Sekarang <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="product-card">
                                <div class="product-card-image-wrap">
                                    <img src="{{ asset('assets/img/produk2.png') }}" class="product-card-image"
                                        alt="Gas Industri">
                                    <div class="shine"></div>
                                </div>
                                <div class="product-card-body">
                                    <h5 class="product-card-title">Hidrogen 7 m³</h5>
                                    <div><span class="product-card-price">Mulai dari Rp1.500.000 / Tabung</span></div>
                                    <div class="product-card-action">
                                        <a href="#" class="btn-produk">
                                            Pesan Sekarang <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="product-card">
                                <div class="product-card-image-wrap">
                                    <img src="{{ asset('assets/img/produk3.png') }}" class="product-card-image"
                                        alt="Gas B2B">
                                    <div class="shine"></div>
                                </div>
                                <div class="product-card-body">
                                    <h5 class="product-card-title">LPG 50 Kg</h5>
                                    <div><span class="product-card-price">Mulai Dari Rp1.100.000/tabung</span></div>
                                    <div class="product-card-action">
                                        <a href="#" class="btn-produk">
                                            Pesan Sekarang <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="home-bg-group-2">

            <!-- Mitra Section -->
            <section class="home-bg-mitra mitra-kami py-5">
                <div class="container">
                    <!-- Header -->
                    <div class="mitra-header text-center mb-5">
                        <h2 class="fw-bold mb-3 display-5">
                            Dipercaya oleh <span class="accent">50+</span> Perusahaan
                        </h2>
                        <p class="mitra-sub">
                            Kami bangga bekerja sama dengan perusahaan-perusahaan terkemuka untuk mendistribusikan energi
                            berkualitas tinggi ke seluruh Indonesia.
                        </p>
                    </div>

                    <!-- Statistics Row -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card text-center">
                                <h3 class=" fw-bold mb-1">50+</h3>
                                <p class="text-secondary small mb-0">Mitra Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card text-center">
                                <h3 class=" fw-bold mb-1">99%</h3>
                                <p class="text-secondary small mb-0">Kepuasan Klien</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card text-center">
                                <h3 class=" fw-bold mb-1">24/7</h3>
                                <p class="text-secondary small mb-0">Layanan Siaga</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="stat-card text-center">
                                <h3 class=" fw-bold mb-1">5 Tahun</h3>
                                <p class="text-secondary small mb-0">Pengalaman</p>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Container -->
                    <div class="mitra-carousel-wrapper">
                        <div class="mitra-carousel">
                            <div class="mitra-track">
                                <!-- Partner 1 -->
                                <div class="mitra-item">
                                    <div class="mitra-card">
                                        <div class="mitra-logo-wrapper">
                                            <img src="{{ asset('assets/img/logo-pertamina.png') }}" class="mitra-logo"
                                                alt="RetailMart">
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner 2 -->
                                <div class="mitra-item">
                                    <div class="mitra-card">
                                        <div class="mitra-logo-wrapper">
                                            <img src="{{ asset('assets/img/kfc.png') }}" class="mitra-logo"
                                                alt="IndustroCorp">
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner 3 -->
                                <div class="mitra-item">
                                    <div class="mitra-card">
                                        <div class="mitra-logo-wrapper">
                                            <img src="{{ asset('assets/img/solaria.png') }}" class="mitra-logo"
                                                alt="FoodChains">
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner 4 -->
                                <div class="mitra-item">
                                    <div class="mitra-card">
                                        <div class="mitra-logo-wrapper">
                                            <img src="{{ asset('assets/img/rs.png') }}" class="mitra-logo"
                                                alt="CafeGroup">
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner 5 -->
                                <div class="mitra-item">
                                    <div class="mitra-card">
                                        <div class="mitra-logo-wrapper">
                                            <img src="{{ asset('assets/img/panasonic.png') }}" class="mitra-logo"
                                                alt="LogisticsPlus">
                                        </div>
                                    </div>
                                </div>

                                <!-- Partner 6 -->
                                <div class="mitra-item">
                                    <div class="mitra-card">
                                        <div class="mitra-logo-wrapper">
                                            <img src="{{ asset('assets/img/kimia.jpg') }}" class="mitra-logo"
                                                alt="Partner Alpha">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section class="section-hubungi-kami py-5" id="hubungin-kami">
                <div class="container">
                    <div class="text-center mb-5">
                        {{-- <span class="contact-eyebrow">Kontak</span> --}}
                        <h2 class="contact-title mb-3">Hubungi Kami</h2>
                        <p class="lead text-secondary mb-0 mx-auto" style="max-width: 560px;">
                            Punya pertanyaan seputar produk atau ingin melakukan pemesanan? Tim kami siap membantu Anda.
                        </p>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card border-0 contact-form-card h-100">
                                <div class="card-body p-4 p-lg-5">
                                    <h3 class="fw-bold mb-2">Kirim Pesan</h3>
                                    <p class="text-secondary mb-4">
                                        Silakan isi formulir di bawah ini. Tim kami akan segera menghubungi Anda.
                                    </p>
                                    <form action="">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                                                <input type="text" class="form-control form-control-lg contact-input"
                                                    id="nama" name="nama" placeholder="Masukkan nama">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label fw-semibold">Email</label>
                                                <input type="email" class="form-control form-control-lg contact-input"
                                                    id="email" name="email" placeholder="nama@email.com">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="subjek" class="form-label fw-semibold">Subjek</label>
                                            <input type="text" class="form-control form-control-lg contact-input"
                                                id="subjek" name="subjek" placeholder="Masukkan subjek pesan">
                                        </div>
                                        <div class="mb-4">
                                            <label for="pesan" class="form-label fw-semibold">Pesan</label>
                                            <textarea class="form-control contact-input" id="pesan" rows="6" placeholder="Tuliskan pesan Anda..."></textarea>
                                        </div>
                                        <button class="btn btn-primary btn-lg w-100">
                                            <i class="fa-solid fa-paper-plane me-2"></i>
                                            Kirim Pesan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="row g-3 h-100">
                                <div class="col-md-6 col-lg-12">

                                    <div class="contact-info-card">
                                        <div class="contact-icon-wrap contact-icon-accent">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1">Customer Support</h5>
                                            <p class="mb-0">+62 812 3456 7890</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-12">
                                    <div class="contact-info-card">
                                        <div class="contact-icon-wrap contact-icon-accent">
                                            <i class="fa-solid fa-shop"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1">Sales & Order</h5>
                                            <p class="mb-0 text-secondary">+62 812 3456 7890</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="contact-info-card">
                                        <div class="contact-icon-wrap contact-icon-accent">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1">Email</h5>
                                            <p class="mb-0 text-secondary">berdirikarya@gmail.com</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="contact-info-card contact-info-map p-0">
                                        <div class="p-4 pb-0">
                                            <div class="d-flex align-items-start">
                                                <div class="contact-icon-wrap contact-icon-accent me-3">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold mb-2">Alamat</h5>
                                                    <p class="text-secondary mb-3">
                                                        Jl. Raya Bekasi No. 123, <br>
                                                        Kramat Jati, Jakarta Timur <br>
                                                        DKI Jakarta 13510, Indonesia
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="contact-map-frame">
                                            <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8464937017725!2d106.8617783747511!3d-6.283900793705008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f262402cb223%3A0xcb09a05752df7c62!2sJl.%20SMP%20126%2C%20Kec.%20Kramat%20jati%2C%20Kota%20Jakarta%20Timur%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1786012220612!5m2!1sid!2sid"
                                                width="100%" height="180" style="border:0;" allowfullscreen=""
                                                loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const skeleton = document.getElementById('produk-skeleton');
            const content = document.getElementById('produk-content');

            setTimeout(() => {
                skeleton.classList.add('d-none');
                content.classList.remove('d-none');
            }, 1000);
        });

        document.addEventListener('DOMContentLoaded', function() {

            /* =========================================================
               ELEMENT
               ========================================================= */

            const carousel = document.querySelector(
                '.mitra-kami .mitra-carousel'
            );

            const track = document.querySelector(
                '.mitra-kami .mitra-track'
            );

            if (!carousel || !track) {
                return;
            }


            /* =========================================================
               CONFIG
               ========================================================= */

            const AUTO_SLIDE_DELAY = 3000;

            const ANIMATION_DURATION = 700;


            /* =========================================================
               ORIGINAL ITEMS
               ========================================================= */

            let originalItems = Array.from(
                track.querySelectorAll('.mitra-item')
            );

            if (originalItems.length === 0) {
                return;
            }


            /* =========================================================
               VARIABLE
               ========================================================= */

            let currentIndex = 0;

            let autoSlideTimer = null;

            let isHovering = false;

            let isAnimating = false;


            /* =========================================================
               HITUNG ITEM YANG TERLIHAT
               ========================================================= */

            function getVisibleItems() {

                const carouselWidth =
                    carousel.clientWidth;

                const itemWidth =
                    originalItems[0].offsetWidth;

                const trackStyle =
                    window.getComputedStyle(track);

                const gap =
                    parseFloat(trackStyle.gap) || 0;


                if (!itemWidth) {
                    return 1;
                }


                return Math.max(
                    1,
                    Math.floor(
                        (carouselWidth + gap) /
                        (itemWidth + gap)
                    )
                );
            }


            /* =========================================================
               CLONE ITEMS
               ========================================================= */

            function createClones() {

                /*
                 * Hapus clone sebelumnya
                 */

                track
                    .querySelectorAll('.mitra-clone')
                    .forEach(function(clone) {

                        clone.remove();

                    });


                const visibleItems =
                    getVisibleItems();


                /*
                 * Kita clone item sebanyak
                 * jumlah item yang terlihat.
                 */

                for (
                    let i = 0; i < visibleItems; i++
                ) {

                    const clone =
                        originalItems[
                            i % originalItems.length
                        ].cloneNode(true);


                    clone.classList.add(
                        'mitra-clone'
                    );


                    track.appendChild(clone);
                }
            }


            /* =========================================================
               GET ALL ITEMS
               ========================================================= */

            function getAllItems() {

                return Array.from(
                    track.querySelectorAll(
                        '.mitra-item'
                    )
                );
            }


            /* =========================================================
               GET ITEM WIDTH
               ========================================================= */

            function getItemWidth() {

                const item =
                    getAllItems()[0];

                if (!item) {
                    return 0;
                }


                const itemStyle =
                    window.getComputedStyle(
                        track
                    );


                const gap =
                    parseFloat(
                        itemStyle.gap
                    ) || 0;


                return item.offsetWidth + gap;
            }


            /* =========================================================
               MOVE CAROUSEL
               ========================================================= */

            function moveCarousel(
                animate = true
            ) {

                const itemWidth =
                    getItemWidth();


                if (!itemWidth) {
                    return;
                }


                if (animate) {

                    track.style.transition =
                        `transform ${ANIMATION_DURATION}ms cubic-bezier(0.4, 0, 0.2, 1)`;

                } else {

                    track.style.transition =
                        'none';
                }


                const translateX = -(currentIndex * itemWidth);


                track.style.transform =
                    `translate3d(${translateX}px, 0, 0)`;
            }


            /* =========================================================
               NEXT SLIDE
               ========================================================= */

            function nextSlide() {

                if (isAnimating) {
                    return;
                }


                isAnimating = true;

                currentIndex++;


                /*
                 * Geser satu item
                 */

                moveCarousel(true);


                /*
                 * Setelah animasi selesai
                 */

                setTimeout(function() {

                    const visibleItems =
                        getVisibleItems();


                    /*
                     * Jumlah original item
                     */

                    const totalOriginal =
                        originalItems.length;


                    /*
                     * Jika sudah melewati
                     * item original terakhir
                     */

                    if (
                        currentIndex >=
                        totalOriginal
                    ) {

                        /*
                         * Reset ke awal
                         *
                         * Tidak terlihat oleh user
                         * karena posisinya sama.
                         */

                        currentIndex = 0;

                        moveCarousel(false);
                    }


                    isAnimating = false;

                }, ANIMATION_DURATION);
            }


            /* =========================================================
               START AUTO SLIDE
               ========================================================= */

            function startAutoSlide() {

                stopAutoSlide();


                autoSlideTimer =
                    setInterval(function() {

                        if (!isHovering) {

                            nextSlide();

                        }

                    }, AUTO_SLIDE_DELAY);
            }


            /* =========================================================
               STOP AUTO SLIDE
               ========================================================= */

            function stopAutoSlide() {

                if (
                    autoSlideTimer !== null
                ) {

                    clearInterval(
                        autoSlideTimer
                    );

                    autoSlideTimer = null;
                }
            }


            /* =========================================================
               HOVER
               ========================================================= */

            carousel.addEventListener(
                'mouseenter',
                function() {

                    isHovering = true;

                    stopAutoSlide();

                }
            );


            carousel.addEventListener(
                'mouseleave',
                function() {

                    isHovering = false;

                    startAutoSlide();

                }
            );


            /* =========================================================
               RESIZE
               ========================================================= */

            let resizeTimer = null;


            window.addEventListener(
                'resize',
                function() {

                    clearTimeout(
                        resizeTimer
                    );


                    resizeTimer =
                        setTimeout(function() {

                            /*
                             * Buat ulang clone
                             */

                            createClones();


                            /*
                             * Kembali ke posisi awal
                             */

                            currentIndex = 0;


                            moveCarousel(false);

                        }, 200);
                }
            );


            /* =========================================================
               INITIALIZE
               ========================================================= */

            createClones();

            moveCarousel(false);

            startAutoSlide();

        });
    </script>
@endsection
