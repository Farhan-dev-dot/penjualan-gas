{{--
    ============================================================
    PEMBERSIH STATE REFILL (KLIEN)
    ============================================================

    Semua data sementara untuk alur refill hanya hidup di sessionStorage:
        penjualan_gas_refill_checkout  -> item refill yang menunggu checkout
        penjualan_gas_refill_mode      -> penanda halaman produk sedang mode refill

    Data itu tidak boleh bertahan setelah user membatalkan atau keluar dari
    alur refill, karena kalau tersisa aplikasi akan menganggap transaksi
    berikutnya sebagai refill (mis. field jenis_sewa jadi hilang/terkunci).

    Partial ini menyediakan window.RefillState.clear() supaya pembersihan
    dipakai dari satu tempat saja, bukan disalin berulang di tiap halaman.
--}}
<script>
    (function() {
        const REFILL_CART_KEY = 'penjualan_gas_refill_checkout';
        const REFILL_MODE_KEY = 'penjualan_gas_refill_mode';

        function clearRefillState(options) {
            const opts = Object.assign({
                keepMode: false
            }, options || {});

            try {
                sessionStorage.removeItem(REFILL_CART_KEY);

                if (!opts.keepMode) {
                    sessionStorage.removeItem(REFILL_MODE_KEY);
                }
            } catch (error) {
                console.error('Gagal membersihkan state refill:', error);
            }
        }

        function refillCart() {
            try {
                const raw = sessionStorage.getItem(REFILL_CART_KEY);
                const parsed = raw ? JSON.parse(raw) : null;

                return parsed && typeof parsed === 'object' &&
                    Object.keys(parsed).length ? parsed : null;
            } catch (error) {
                return null;
            }
        }

        window.RefillState = {
            cartKey: REFILL_CART_KEY,
            modeKey: REFILL_MODE_KEY,
            clear: clearRefillState,
            cart: refillCart,
        };
    })();
</script>
