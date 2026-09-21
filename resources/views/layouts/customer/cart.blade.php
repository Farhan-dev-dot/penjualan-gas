<div class="offcanvas offcanvas-end cart-offcanvas" data-bs-backdrop="true" tabindex="-1" id="cartOffcanvas"
    aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="cartOffcanvasLabel">
            <i class="fa-solid fa-cart-shopping me-2"></i>
            Keranjang Belanja
            <span class="cart-count-badge">0</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-0" id="local-cart-body"></div>
</div>

{{-- Markup saja. Logika keranjang ada di public/assets/js/cart-script.js,
     di-load di akhir <body> pada layouts/customer/app.blade.php.
     window.CartConfig (data dari Blade seperti route & status login)
     didefinisikan tepat di bawah, sebelum cart-script.js dijalankan. --}}
<script>
    window.CartConfig = {
        isLoggedIn: @json(auth()->check()),
        hasUploadedKtp: @json(auth()->check() && filled(auth()->user()?->foto_ktp)),
        profileUrl: @json(route('user.profile')),
        checkoutUrl: @json(route('user.checkout')),
        loginUrl: @json(route('login')),
        produkUrl: @json(route('produk.index')),
    };
</script>
