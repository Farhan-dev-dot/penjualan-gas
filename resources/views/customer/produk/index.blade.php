@extends('layouts.app')

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
            <div class="row g-3">
                <div class="col-lg-3">

                    <div class="card shadow-sm">

                        <div class="card-body">

                            <h4 class="fw-bold">Filter</h4>

                            <hr>

                            <h6>Kategori</h6>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">
                                    Household
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">
                                    Industrial
                                </label>
                            </div>

                            <hr>

                            <h6>Berat</h6>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">
                                    3 Kg
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">
                                    5.5 Kg
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label class="form-check-label">
                                    12 Kg
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <section class="produkall">
                        {{-- ============================= --}}
                        {{-- SKELETON --}}
                        {{-- ============================= --}}

                        <div id="produk-skeleton">

                            <div class="row g-4">

                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="col-xl-3 col-lg-4 col-md-6">

                                        <div class="product-card" aria-hidden="true">

                                            {{-- IMAGE --}}
                                            <div class="placeholder-glow">

                                                <span class="placeholder w-100 d-block product-skeleton-image">
                                                </span>

                                            </div>


                                            {{-- VARIANT --}}
                                            <div class="d-flex gap-2 mt-2 placeholder-glow">

                                                <span class="placeholder product-skeleton-variant"></span>

                                                <span class="placeholder product-skeleton-variant"></span>

                                                <span class="placeholder product-skeleton-variant"></span>

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

                            <div class="row g-4">

                                @for ($i = 1; $i <= 8; $i++)
                                    <div class="col-xl-3 col-lg-4 col-md-6">

                                        <div class="product-card h-100">

                                            {{-- ================= --}}
                                            {{-- IMAGE --}}
                                            {{-- ================= --}}

                                            <div class="product-image-wrapper">

                                                <img src="{{ asset('assets/img/1.png') }}" class="product-image"
                                                    alt="LPG 3 Kg">

                                            </div>


                                            {{-- ================= --}}
                                            {{-- VARIANTS --}}
                                            {{-- ================= --}}

                                            <div class="product-variants">

                                                <div class="product-variant active">

                                                    <img src="{{ asset('assets/img/1.png') }}" alt="LPG 3 Kg">

                                                </div>

                                                <div class="product-variant">

                                                    <img src="{{ asset('assets/img/1.png') }}" alt="LPG 3 Kg">

                                                </div>

                                                <div class="product-variant">

                                                    <img src="{{ asset('assets/img/1.png') }}" alt="LPG 3 Kg">

                                                </div>

                                            </div>


                                            {{-- ================= --}}
                                            {{-- PRODUCT INFO --}}
                                            {{-- ================= --}}

                                            <div class="mt-3">

                                                {{-- Product Name --}}
                                                <h5 class="product-title">
                                                    LPG 3 Kg
                                                </h5>


                                                {{-- Category --}}
                                                <div class="product-category">
                                                    Household · Gas LPG
                                                </div>



                                                {{-- Price --}}
                                                <div class="product-price">

                                                    Rp 20.000

                                                </div>


                                                {{-- ================= --}}
                                                {{-- TAMBAH --}}
                                                {{-- ================= --}}

                                                <button type="button" class="btn btn-primary product-add-btn">

                                                    <i class="fa-solid fa-cart-plus me-1"></i>

                                                    Tambah

                                                </button>

                                            </div>

                                        </div>

                                    </div>
                                @endfor

                            </div>

                        </div>
                        <div class="mt-2">

                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                    </section>
                </div>

            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const skeleton = document.getElementById('produk-skeleton');
            const content = document.getElementById('produk-content');

            setTimeout(function() {

                skeleton.classList.add('d-none');
                content.classList.remove('d-none');

            }, 1500);

        });
    </script>
@endsection
