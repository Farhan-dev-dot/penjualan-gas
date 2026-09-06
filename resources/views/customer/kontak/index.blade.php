@extends('layouts.customer.app')

@section('content')
    <div class="container py-5">
        <div class="text-center">
            <h1 class="display-3 fw-bold mb-3">
                Hubungi Kami
            </h1>
            <p class="lead text-secondary mb-0">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda, maxime.
            </p>
        </div>
        <div class="row py-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-lg-5">

                        <h3 class="fw-bold mb-2">Kirim Pesan</h3>
                        <p class="text-secondary mb-4">
                            Silakan isi formulir di bawah ini. Tim kami akan segera menghubungi Anda.
                        </p>
                        <form action="">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label fw-semibold">
                                        Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="nama" name="nama"
                                        placeholder="Masukkan nama">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email"
                                        placeholder="nama@email.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subjek" class="form-label fw-semibold">
                                    Subjek
                                </label>
                                <input type="text" class="form-control form-control-lg" id="subjek" name="subjek"
                                    placeholder="Masukkan subjek pesan">
                            </div>

                            <div class="mb-4">
                                <label for="pesan" class="form-label fw-semibold">
                                    Pesan
                                </label>
                                <textarea class="form-control" id="pesan" rows="6" placeholder="Tuliskan pesan Anda..."></textarea>
                            </div>

                            <button class="btn btn-primary btn-lg w-100 rounded-3">
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                Kirim Pesan
                            </button>

                        </form>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-start">
                                    <div class="bg-primary bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                        <i class="fa-solid fa-phone fs-4 text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1">Customer Support</h5>
                                    <p class="text-secondary mb-0">+62 812 3456 7890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-start">
                                    <div class="bg-success bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                        <i class="fa-solid fa-shop fs-4 text-success"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1">Sales & Order</h5>
                                    <p class="text-secondary mb-0">+62 812 3456 7890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-start">
                                    <div class="bg-danger bg-opacity-10 rounded-3 d-inline-flex p-3 mb-3">
                                        <i class="fa-solid fa-shop fs-4 text-danger-emphasis"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1">Email</h5>
                                    <p class="text-secondary mb-0">berdirikarya@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">

                                <div class="d-flex align-items-start">
                                    <!-- Icon -->
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                                        <i class="fa-solid fa-location-dot fs-5 text-warning"></i>
                                    </div>

                                    <!-- Text -->
                                    <div>
                                        <h5 class="fw-bold mb-2">Alamat</h5>
                                        <p class="text-secondary mb-0">
                                            Jl. Raya Bekasi No. 123, <br>
                                            Kramat Jati, Jakarta Timur <br>
                                            DKI Jakarta 13510, Indonesia
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.8464937017725!2d106.8617783747511!3d-6.283900793705008!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f262402cb223%3A0xcb09a05752df7c62!2sJl.%20SMP%20126%2C%20Kec.%20Kramat%20jati%2C%20Kota%20Jakarta%20Timur%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1786012220612!5m2!1sid!2sid"
                            width="600" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
