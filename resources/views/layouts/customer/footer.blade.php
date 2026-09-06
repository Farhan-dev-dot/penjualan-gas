<footer class="site-footer">
    <div class="container">
        <div class="row gy-4">

            {{-- Logo & deskripsi --}}
            <div class="col-lg-6">
                <div class="footer-logo">
                    <span class="logo-wrap">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                    </span>

                    <div class="footer-brand">
                        <h1>BBB</h1>
                    </div>
                </div>
                <p class="footer-desc">
                    PT Berkat Bidara Berwibawa menyediakan layanan filling station
                    untuk Oxygen (O₂), Nitrogen (N₂), Argon (Ar), Acetylene (C₂H₂),
                    Carbon Dioxide (CO₂), dan berbagai gas industri lainnya.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            {{-- Navigasi --}}
            <div class="col-lg-3 col-6">
                <div class="footer-heading">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/produk') }}">Produk</a></li>
                    <li><a href="{{ url('/tentang-kami') }}">Tentang Kami</a></li>
                    {{-- <li><a href="{{ url('/kontak-kami') }}">Kontak Kami</a></li> --}}
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-3 col-6">
                <div class="footer-heading">Hubungi Kami</div>
                <ul class="footer-links footer-contact">
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Jl. Contoh Alamat No. 123, Jakarta, Indonesia</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <span>+62 812-3456-7890</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <span>info@berdiriberkat.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom text-center">
            &copy; {{ date('Y') }} Berdiri Berkat Berwibawa. Seluruh hak cipta dilindungi.
            <span class="mx-1">·</span>
            <a href="#">Kebijakan Privasi</a>
            <span class="mx-1">·</span>
            <a href="#">Syarat & Ketentuan</a>
        </div>
    </div>
</footer>
