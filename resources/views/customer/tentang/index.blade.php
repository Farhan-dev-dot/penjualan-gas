@extends('layouts.app')

@section('content')
    <main class="home-page">

        {{-- Hero Carousel --}}
        <div id="carouselExampleIndicators" class="carousel slide">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active position-relative">
                    <div class="card text-bg-dark border-0">
                        <img src="{{ asset('assets/img/1.png') }}" class="d-block w-100">
                    </div>
                </div>
                <div class="carousel-item position-relative">
                    <div class="card text-bg-dark border-0">
                        <img src="{{ asset('assets/img/2.png') }}" class="d-block w-100">
                    </div>
                </div>
                <div class="carousel-item position-relative">
                    <div class="card text-bg-dark border-0">
                        <img src="{{ asset('assets/img/3.png') }}" class="d-block w-100" alt="">
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container py-5">

            <div class="row g-4">

                <!-- Visi -->
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        <div class="card-body p-4">

                            <div class="bg-primary bg-opacity-10 rounded-3 d-inline-flex p-3 mb-4">

                                <i class="fa-solid fa-eye text-primary fs-4"></i>

                            </div>

                            <h2 class="fw-bold mb-3">
                                Visi Kami
                            </h2>

                            <p class="text-secondary mb-0">
                                Menjadi perusahaan distribusi LPG terpercaya yang
                                menghadirkan pelayanan profesional, inovatif, dan
                                berkelanjutan untuk memenuhi kebutuhan energi masyarakat
                                Indonesia.
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Misi -->
                <div class="col-lg-8">

                    <div class="card bg-primary text-white border-0 shadow rounded-4 h-100">

                        <div class="card-body p-4">

                            <div class="d-flex align-items-center mb-4">

                                <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3">

                                    <i class="fa-solid fa-rocket fs-4"></i>

                                </div>

                                <h2 class="fw-bold mb-0">
                                    Misi Kami
                                </h2>

                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <div class="card border-0 bg-white bg-opacity-10 text-white h-100">

                                        <div class="card-body">

                                            <small class="fw-bold text-uppercase">
                                                Pelayanan Prima
                                            </small>

                                            <p class="mt-3 mb-0">
                                                Memberikan layanan distribusi LPG yang
                                                cepat, aman, dan tepat waktu dengan
                                                mengutamakan kepuasan pelanggan.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="card border-0 bg-white bg-opacity-10 text-white h-100">

                                        <div class="card-body">

                                            <small class="fw-bold text-uppercase">
                                                Kualitas & Keamanan
                                            </small>

                                            <p class="mt-3 mb-0">
                                                Menjamin setiap produk LPG yang
                                                didistribusikan memenuhi standar kualitas
                                                serta keamanan yang berlaku.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="card border-0 bg-white bg-opacity-10 text-white h-100">

                                        <div class="card-body">

                                            <small class="fw-bold text-uppercase">
                                                Inovasi Digital
                                            </small>

                                            <p class="mt-3 mb-0">
                                                Mengembangkan sistem pemesanan online
                                                untuk mempermudah pelanggan dalam
                                                memperoleh layanan kami.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="card border-0 bg-white bg-opacity-10 text-white h-100">

                                        <div class="card-body">


                                            <small class="fw-bold text-uppercase">
                                                Tanggung Jawab Sosial
                                            </small>

                                            <p class="mt-3 mb-0">
                                                Berkontribusi dalam mendukung kebutuhan
                                                energi masyarakat melalui pelayanan yang
                                                profesional dan berkelanjutan.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

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
