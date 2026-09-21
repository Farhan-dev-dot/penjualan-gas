(function () {
    const REFILL_CART_KEY = "penjualan_gas_refill_checkout";
    const REFILL_MODE_KEY = "penjualan_gas_refill_mode";

    function clearRefillState(options) {
        const opts = Object.assign(
            {
                keepMode: false,
            },
            options || {},
        );

        try {
            sessionStorage.removeItem(REFILL_CART_KEY);

            if (!opts.keepMode) {
                sessionStorage.removeItem(REFILL_MODE_KEY);
            }
        } catch (error) {
            console.error("Gagal membersihkan state refill:", error);
        }
    }

    function refillCart() {
        try {
            const raw = sessionStorage.getItem(REFILL_CART_KEY);
            const parsed = raw ? JSON.parse(raw) : null;

            return parsed &&
                typeof parsed === "object" &&
                Object.keys(parsed).length
                ? parsed
                : null;
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
