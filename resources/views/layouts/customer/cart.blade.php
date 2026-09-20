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

{{-- Markup saja. Logika keranjang ada di layouts/customer/cart-script.blade.php
     yang di-include dari <head> layout, supaya window.renderCart dan
     window.cartKey sudah siap sebelum script halaman berjalan. --}}
