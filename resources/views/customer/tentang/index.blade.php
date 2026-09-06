@extends('layouts.customer.app')

@section('content')
    <main class="home-page">

        {{-- Hero Carousel --}}
        <div id="carouselExampleIndicators" class="carousel slide">

            {{-- Indicators --}}
            <div class="carousel-indicators">

                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1">
                </button>

                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2">
                </button>

                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 3">
                </button>

            </div>


            {{-- Carousel Inner --}}
            <div class="carousel-inner">

                {{-- ========================= --}}
                {{-- SLIDE 1 --}}
                {{-- ========================= --}}
                <div class="carousel-item active position-relative">

                    {{-- Background --}}
                    <img src="{{ asset('assets/img/1.png') }}" class="d-block w-100 slide-1-image"
                        alt="PT Berkat Bidara Berwibawa">

                    {{-- Overlay --}}
                    <div class="slide-1-overlay"></div>

                    {{-- Content --}}
                    <div class="slide-1-content">



                        {{-- Heading --}}
                        <h1 class="slide-1-title">
                            Mitra Energi
                            <span>Terpercaya</span>
                            untuk Bisnis Anda
                        </h1>

                        {{-- Description --}}
                        <p class="slide-1-description">
                            Kami hadir sebagai mitra penyedia energi yang mengutamakan
                            kualitas, ketersediaan, dan pelayanan profesional untuk
                            mendukung kelancaran operasional bisnis Anda.
                        </p>

                        {{-- Bottom Info --}}
                        <div class="slide-1-info">

                            <div class="slide-1-info-item">
                                <div class="slide-1-info-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>

                                <div>
                                    <strong>Kemitraan</strong>
                                    <small>Jangka Panjang</small>
                                </div>
                            </div>

                            <div class="slide-1-divider"></div>

                            <div class="slide-1-info-item">
                                <div class="slide-1-info-icon">
                                    <i class="fas fa-truck"></i>
                                </div>

                                <div>
                                    <strong>Pasokan</strong>
                                    <small>Terpercaya</small>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================= --}}
                {{-- SLIDE 2 --}}
                {{-- ========================= --}}
                <div class="carousel-item position-relative">

                    {{-- Background --}}
                    <img src="{{ asset('assets/img/2.png') }}" class="d-block w-100 slide-2-image"
                        alt="Sektor Industri PT Berkat Bidara Berwibawa">

                    {{-- Overlay --}}
                    <div class="slide-2-overlay"></div>

                    {{-- Content --}}
                    <div class="slide-2-content">

                        {{-- Header --}}
                        <div class="slide-2-header">



                            <h2 class="slide-2-title">
                                Solusi Energi untuk
                                <span>Berbagai Industri</span>
                            </h2>

                            <p class="slide-2-description">
                                Kami menyediakan solusi dan pasokan energi yang
                                dapat disesuaikan dengan kebutuhan operasional
                                berbagai sektor bisnis.
                            </p>

                        </div>


                        {{-- Industry List --}}
                        <div class="slide-2-industry-list">

                            {{-- Manufacturing --}}
                            <div class="slide-2-industry-item">

                                <div class="slide-2-icon">
                                    <i class="fas fa-industry"></i>
                                </div>

                                <div class="slide-2-item-content">

                                    <span class="slide-2-number">
                                        01
                                    </span>

                                    <h3>
                                        Manufacturing
                                    </h3>

                                    <p>
                                        Mendukung proses produksi dengan
                                        pasokan energi yang konsisten.
                                    </p>

                                </div>

                            </div>


                            {{-- Metal & Mining --}}
                            <div class="slide-2-industry-item">

                                <div class="slide-2-icon">
                                    <i class="fas fa-hard-hat"></i>
                                </div>

                                <div class="slide-2-item-content">

                                    <span class="slide-2-number">
                                        02
                                    </span>

                                    <h3>
                                        Metal & Mining
                                    </h3>

                                    <p>
                                        Mendukung kebutuhan energi untuk
                                        aktivitas pertambangan dan pengolahan.
                                    </p>

                                </div>

                            </div>


                            {{-- Food & Beverage --}}
                            <div class="slide-2-industry-item">

                                <div class="slide-2-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>

                                <div class="slide-2-item-content">

                                    <span class="slide-2-number">
                                        03
                                    </span>

                                    <h3>
                                        Food & Beverage
                                    </h3>

                                    <p>
                                        Menunjang proses produksi dan
                                        operasional industri makanan.
                                    </p>

                                </div>

                            </div>


                            {{-- Healthcare --}}
                            <div class="slide-2-industry-item">

                                <div class="slide-2-icon">
                                    <i class="fas fa-heartbeat"></i>
                                </div>

                                <div class="slide-2-item-content">

                                    <span class="slide-2-number">
                                        04
                                    </span>

                                    <h3>
                                        Kesehatan
                                    </h3>

                                    <p>
                                        Mendukung kebutuhan energi pada
                                        fasilitas dan layanan kesehatan.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ========================= --}}
                {{-- SLIDE 3 --}}
                {{-- ========================= --}}
                <div class="carousel-item position-relative">

                    {{-- Background --}}
                    <img src="{{ asset('assets/img/3.png') }}" class="d-block w-100 slide-3-image"
                        alt="PT Berkat Bidara Berwibawa">

                    {{-- Dark Overlay --}}
                    <div class="slide-3-overlay"></div>

                    {{-- Main Content --}}
                    <div class="slide-3-content">

                        <div class="slide-3-content-inner">



                            {{-- Main Heading --}}
                            <h2 class="slide-3-title">
                                Menghubungkan
                                <br>
                                <span>Energi</span> dengan
                                <br>
                                Pertumbuhan.
                            </h2>

                            {{-- Description --}}
                            <p class="slide-3-description">
                                Dari kebutuhan operasional hingga pengembangan bisnis,
                                kami menghadirkan solusi energi yang dirancang untuk
                                membantu mitra bergerak lebih jauh.
                            </p>

                            {{-- Bottom Statement --}}
                            <div class="slide-3-bottom">

                                <div class="slide-3-bottom-line"></div>

                                <span>
                                    TRUSTED ENERGY PARTNER
                                </span>

                            </div>

                        </div>

                    </div>

                    {{-- Vertical Text --}}
                    <div class="slide-3-vertical">
                        PT BERKAT BIDARA BERWIBAWA
                    </div>

                </div>

            </div>
            {{-- END carousel-inner --}}


            {{-- ========================= --}}
            {{-- PREVIOUS --}}
            {{-- ========================= --}}
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">

                <span class="carousel-control-prev-icon" aria-hidden="true">
                </span>

                <span class="visually-hidden">
                    Previous
                </span>

            </button>


            {{-- ========================= --}}
            {{-- NEXT --}}
            {{-- ========================= --}}
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">

                <span class="carousel-control-next-icon" aria-hidden="true">
                </span>

                <span class="visually-hidden">
                    Next
                </span>

            </button>

        </div>

        <div class="container py-5">
            {{-- =========================================================
     VISI & MISI
========================================================= --}}

            <section class="about-misvi-section">

                <div class="container">

                    <div class="about-misvi-grid">

                        {{-- =================================================
                 VISI
            ================================================== --}}

                        <div class="about-misvi-vision">

                            <div class="about-misvi-vision-card">

                                <div class="about-misvi-vision-body">

                                    {{-- Icon --}}
                                    <div class="about-misvi-vision-icon">

                                        <i class="fa-solid fa-eye"></i>

                                    </div>




                                    {{-- Title --}}
                                    <h2 class="about-misvi-vision-title">
                                        Visi Kami
                                    </h2>


                                    {{-- Description --}}
                                    <p class="about-misvi-vision-description">
                                        Menjadi perusahaan distribusi LPG terpercaya yang
                                        menghadirkan pelayanan profesional, inovatif, dan
                                        berkelanjutan untuk memenuhi kebutuhan energi
                                        masyarakat Indonesia.
                                    </p>


                                    {{-- Decorative Line --}}
                                    <div class="about-misvi-vision-line"></div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                 MISI
            ================================================== --}}

                        <div class="about-misvi-mission">

                            <div class="about-misvi-mission-card">

                                <div class="about-misvi-mission-body">

                                    {{-- Mission Header --}}
                                    <div class="about-misvi-mission-header">

                                        <div class="about-misvi-mission-icon">

                                            <i class="fa-solid fa-rocket"></i>

                                        </div>


                                        <div>



                                            <h2 class="about-misvi-mission-title text-white">
                                                Misi Kami
                                            </h2>

                                        </div>

                                    </div>


                                    {{-- Mission List --}}
                                    <div class="about-misvi-list">


                                        {{-- Mission 01 --}}
                                        <div class="about-misvi-item">

                                            <div class="about-misvi-item-number">
                                                01
                                            </div>

                                            <div class="about-misvi-item-content">

                                                <span class="about-misvi-item-label">
                                                    Pelayanan Prima
                                                </span>

                                                <p>
                                                    Memberikan layanan distribusi LPG
                                                    yang cepat, aman, dan tepat waktu
                                                    dengan mengutamakan kepuasan
                                                    pelanggan.
                                                </p>

                                            </div>

                                        </div>


                                        {{-- Mission 02 --}}
                                        <div class="about-misvi-item">

                                            <div class="about-misvi-item-number">
                                                02
                                            </div>

                                            <div class="about-misvi-item-content">

                                                <span class="about-misvi-item-label">
                                                    Kualitas & Keamanan
                                                </span>

                                                <p>
                                                    Menjamin setiap produk LPG yang
                                                    didistribusikan memenuhi standar
                                                    kualitas serta keamanan yang berlaku.
                                                </p>

                                            </div>

                                        </div>


                                        {{-- Mission 03 --}}
                                        <div class="about-misvi-item">

                                            <div class="about-misvi-item-number">
                                                03
                                            </div>

                                            <div class="about-misvi-item-content">

                                                <span class="about-misvi-item-label">
                                                    Inovasi Digital
                                                </span>

                                                <p>
                                                    Mengembangkan sistem pemesanan online
                                                    untuk mempermudah pelanggan dalam
                                                    memperoleh layanan kami.
                                                </p>

                                            </div>

                                        </div>


                                        {{-- Mission 04 --}}
                                        <div class="about-misvi-item">

                                            <div class="about-misvi-item-number">
                                                04
                                            </div>

                                            <div class="about-misvi-item-content">

                                                <span class="about-misvi-item-label">
                                                    Tanggung Jawab Sosial
                                                </span>

                                                <p>
                                                    Berkontribusi dalam mendukung kebutuhan
                                                    energi masyarakat melalui pelayanan
                                                    yang profesional dan berkelanjutan.
                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>
        {{-- ============================= --}}
        {{-- SEJARAH PERUSAHAAN --}}
        {{-- ============================= --}}

        <section class="sejarah py-5">

            <div class="container">

                {{-- Header --}}
                <div class="text-center mb-5">
                    <h2 class="fw-bold mt-3">
                        Sejarah Perusahaan
                    </h2>

                    <p class="text-secondary mx-auto" style="max-width: 650px;">
                        Perjalanan PT Berdiri Berkat Berwibawa dalam menghadirkan
                        solusi penyediaan dan distribusi gas yang terpercaya
                        bagi kebutuhan bisnis dan industri.
                    </p>

                </div>


                {{-- ============================= --}}
                {{-- HISTORY --}}
                {{-- ============================= --}}

                <div class="history-section">

                    {{-- ================= --}}
                    {{-- ITEM KIRI ATAS (langkah 1) --}}
                    {{-- ================= --}}

                    <div class="history-item history-left history-left-top">

                        <div class="history-icon">
                            {{-- <span class="history-step">01</span> --}}
                            <i class="fa-solid fa-building"></i>
                        </div>

                        <div class="history-content">

                            <h5>
                                Awal Berdiri
                            </h5>

                            <p>
                                PT Berdiri Berkat Berwibawa memulai perjalanan
                                dengan fokus menyediakan kebutuhan gas yang
                                aman, berkualitas, dan terpercaya.
                            </p>

                        </div>

                    </div>


                    {{-- ================= --}}
                    {{-- ITEM KANAN ATAS (langkah 2) --}}
                    {{-- ================= --}}

                    <div class="history-item history-right history-right-top">

                        <div class="history-icon">
                            {{-- <span class="history-step">02</span> --}}
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>

                        <div class="history-content">

                            <h5>
                                Memperluas Layanan
                            </h5>

                            <p>
                                Menghadirkan layanan distribusi yang lebih
                                cepat dan fleksibel untuk memenuhi kebutuhan
                                berbagai sektor usaha.
                            </p>

                        </div>

                    </div>


                    {{-- ================= --}}
                    {{-- GAMBAR TENGAH --}}
                    {{-- ================= --}}

                    <div class="history-center">

                        <div class="history-image-wrapper">

                            <img src="{{ asset('assets/img/sejarah.png') }}" class="history-image"
                                alt="Sejarah PT Berdiri Berkat Berwibawa">

                        </div>

                    </div>


                    {{-- ================= --}}
                    {{-- ITEM KIRI BAWAH (langkah 3) --}}
                    {{-- ================= --}}

                    <div class="history-item history-left history-left-bottom">

                        <div class="history-icon">
                            {{-- <span class="history-step">03</span> --}}
                            <i class="fa-solid fa-chart-line"></i>
                        </div>

                        <div class="history-content">

                            <h5>
                                Terus Berkembang
                            </h5>

                            <p>
                                Seiring meningkatnya kebutuhan pelanggan,
                                perusahaan terus mengembangkan layanan dan
                                memperluas jaringan distribusi.
                            </p>

                        </div>

                    </div>


                    {{-- ================= --}}
                    {{-- ITEM KANAN BAWAH (langkah 4) --}}
                    {{-- ================= --}}

                    <div class="history-item history-right history-right-bottom">

                        <div class="history-icon">
                            {{-- <span class="history-step">04</span> --}}
                            <i class="fa-solid fa-handshake"></i>
                        </div>

                        <div class="history-content">

                            <h5>
                                Dipercaya Pelanggan
                            </h5>

                            <p>
                                Kini kami terus membangun hubungan jangka
                                panjang dengan pelanggan melalui pelayanan
                                profesional dan pasokan yang konsisten.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </main>
@endsection
