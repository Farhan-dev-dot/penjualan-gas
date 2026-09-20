{{--
    ============================================================
    SCRIPT KERANJANG (GLOBAL)
    ============================================================

    File ini HANYA berisi <script>, tanpa markup, supaya aman
    di-include dari <head> layout. Markup offcanvas-nya ada di
    layouts/customer/cart.blade.php.

    Dipisah karena halaman produk butuh window.renderCart sudah
    terpasang sebelum script halaman itu berjalan.
--}}
<script>
    // Disetel langsung (bukan di dalam DOMContentLoaded) supaya script halaman
    // lain yang berjalan lebih dulu sudah bisa membaca kunci keranjang ini.
    window.cartKey = 'penjualan_gas_cart';

    document.addEventListener('DOMContentLoaded', function() {
        const cartKey = window.cartKey;
        const isLoggedIn = @json(auth()->check());
        const hasUploadedKtp = @json(auth()->check() && filled(auth()->user()?->foto_ktp));
        const profileUrl = @json(route('user.profile'));
        const cartBody = document.getElementById('local-cart-body');
        const money = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(cartKey)) || {};
            } catch (error) {
                return {};
            }
        }

        function saveCart(cart) {
            localStorage.setItem(cartKey, JSON.stringify(cart));
            renderCart();
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>'"]/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#039;',
                '"': '&quot;'
            })[character]);
        }

        function renderCart() {
            if (!cartBody) return;

            const cart = getCart();
            const items = Object.values(cart);
            const count = items.reduce((total, item) => total + item.quantity, 0);
            const subtotal = items.reduce((total, item) => total + item.price * item.quantity, 0);

            document.querySelectorAll('.cart-count-badge, .cart-nav-badge').forEach(element => {
                element.textContent = count;
            });

            if (!items.length) {
                cartBody.innerHTML = `
                    <div class="cart-empty flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center">
                        <div class="cart-empty-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        <h6 class="fw-bold mb-1">Keranjang Anda Kosong</h6>
                        <p class="text-secondary small mb-4">Yuk mulai belanja kebutuhan gas Anda sekarang.</p>
                        <a href="{{ route('produk.index') }}" class="btn btn-cart-primary px-4">Lihat Produk</a>
                    </div>`;
                return;
            }

            cartBody.innerHTML = `
                <div class="cart-items flex-grow-1">
                    ${items.map(item => `
                        <div class="cart-item" data-cart-key="${escapeHtml(item.id)}">
                            <div class="cart-item-image">
                                <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">
                            </div>
                            <div class="cart-item-body">
                                <h6 class="cart-item-title">${escapeHtml(item.name)}</h6>
                                <span class="cart-item-variant">${escapeHtml(item.variant)}</span>
                                <div class="cart-item-footer mt-2">
                                    <div class="cart-qty-control">
                                        <button type="button" class="cart-qty-btn cart-minus" data-key="${escapeHtml(item.id)}" aria-label="Kurangi">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <input type="text" class="cart-qty-input" value="${item.quantity}" readonly>
                                        <button type="button" class="cart-qty-btn cart-plus" data-key="${escapeHtml(item.id)}" aria-label="Tambah">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <span class="cart-item-price">${money(item.price * item.quantity)}</span>
                                </div>
                            </div>
                            <button type="button" class="cart-item-remove" data-key="${escapeHtml(item.id)}" aria-label="Hapus item">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>`).join('')}
                </div>
                <div class="cart-summary">
                    <div class="cart-summary-row"><span>Subtotal Produk</span><span class="cart-subtotal">${money(subtotal)}</span></div>
                    <div class="cart-summary-divider"></div>
                    <div class="cart-summary-row cart-summary-total"><span>Total</span><span class="cart-total">${money(subtotal)}</span></div>
                    <a href="${hasUploadedKtp ? @json(route('user.checkout')) : profileUrl}" class="btn btn-cart-primary w-100 mt-3">${hasUploadedKtp ? 'Checkout Sekarang' : 'Unggah KTP untuk Checkout'} <i class="fa-solid fa-arrow-right ms-2"></i></a>
                </div>`;
        }

        // Dipakai halaman lain (mis. halaman produk) untuk merender ulang
        // keranjang setelah mengubah localStorage dari luar file ini.
        window.renderCart = renderCart;

        document.addEventListener('click', function(event) {
            const addButton = event.target.closest('.product-add-btn');
            const actionButton = event.target.closest('.cart-plus, .cart-minus, .cart-item-remove');

            if (addButton && !addButton.disabled) {
                if (!isLoggedIn) {
                    window.location.href = @json(route('login'));
                    return;
                }

                if (!hasUploadedKtp) {
                    alert('Tolong lengkapi profil dan unggah foto KTP terlebih dahulu.');
                    window.location.href = profileUrl;
                    return;
                }

                const product = addButton.dataset;
                const cart = getCart();
                const item = cart[product.productId];

                // Keranjang belanja utama tidak pernah menyimpan item refill.
                // Refill punya alurnya sendiri (halaman refill -> sessionStorage),
                // jadi pembelian biasa selalu bertipe 'isi_ulang'.
                cart[product.productId] = item || {
                    id: product.productId,
                    name: product.productName,
                    variant: product.productVariant,
                    price: Number(product.productPrice),
                    stock: Number(product.productStock),
                    image: product.productImage,
                    berat: Number(product.productBerat),
                    quantity: 0,
                    is_pinjam: false,
                    tipe_transaksi: 'isi_ulang'
                };

                // Bersihkan sisa penanda refill kalau ada (mis. data dari versi lama).
                if (cart[product.productId].tipe_transaksi === 'refil') {
                    cart[product.productId].tipe_transaksi = 'isi_ulang';
                }

                delete cart[product.productId].refill_mode;

                cart[product.productId].quantity = Math.min(cart[product.productId].quantity + 1,
                    Number(product.productStock));
                saveCart(cart);
            }

            if (actionButton) {
                const key = actionButton.dataset.key;
                const cart = getCart();
                const item = cart[key];

                if (!item) return;
                if (actionButton.classList.contains('cart-plus')) {
                    if (!hasUploadedKtp) {
                        alert('Tolong lengkapi profil dan unggah foto KTP terlebih dahulu.');
                        window.location.href = profileUrl;
                        return;
                    }

                    item.quantity = Math.min(item.quantity + 1, item.stock);
                }
                if (actionButton.classList.contains('cart-minus')) item.quantity = Math.max(item
                    .quantity - 1, 1);
                if (actionButton.classList.contains('cart-item-remove')) delete cart[key];
                saveCart(cart);
            }
        });

        document.addEventListener('change', function(event) {
            if (!event.target.classList.contains('cart-pinjam-checkbox')) return;
            const cart = getCart();
            const item = cart[event.target.dataset.key];
            if (!item) return;
            item.is_pinjam = event.target.checked;
            saveCart(cart);
        });

        renderCart();
    });
</script>
