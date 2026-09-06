@extends('layouts.customer.app')

@section('content')
    <main class="py-5" style="min-height: 100vh; background-color: #f8f9fa;">
        <div class="container">

            {{-- HEADER --}}
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Checkout</h2>
                <p class="text-secondary mb-0">
                    Lengkapi detail pesanan Anda di bawah ini.
                </p>
            </div>

            <form id="checkout-form" action="{{ route('user.checkout.transaction') }}" method="POST">

                @csrf

                <div class="row g-4">

                    {{-- =====================================================
                    KIRI
                ====================================================== --}}
                    <div class="col-lg-8">

                        {{-- =================================================
                        1. INFO PELANGGAN
                    ================================================== --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">

                                <h5 class="fw-bold mb-4 d-flex align-items-center">
                                    <i class="fa-regular fa-user text-primary me-2"></i>
                                    1. Info Pelanggan & Alamat Pengiriman
                                </h5>

                                <div class="row g-3">

                                    {{-- Nama --}}
                                    <div class="col-md-6">
                                        <label for="nama_penerima" class="form-label">
                                            Nama Pelanggan/Perusahaan
                                        </label>

                                        <input type="text" name="nama_penerima" id="nama_penerima"
                                            value="{{ old('nama_penerima', $detailSebelumnya?->nama_penerima) }}"
                                            class="form-control bg-light border-0 py-3" placeholder="Nama Lengkap" required>
                                    </div>

                                    {{-- Telepon --}}
                                    <div class="col-md-6">
                                        <label for="telepon_penerima" class="form-label">
                                            Telepon
                                        </label>

                                        <input type="text" name="telepon_penerima" id="telepon_penerima"
                                            value="{{ old('telepon_penerima', $detailSebelumnya?->telepon_penerima) }}"
                                            class="form-control bg-light border-0 py-3" placeholder="Nomor Telepon"
                                            required>
                                    </div>

                                    {{-- Provinsi --}}
                                    <div class="col-md-6">
                                        <label for="provinsi" class="form-label">
                                            Provinsi
                                        </label>

                                        <select name="provinsi" id="provinsi" class="form-select bg-light border-0 py-3"
                                            data-old="{{ old('provinsi', $detailSebelumnya?->provinsi) }}" required>
                                            <option value="">
                                                Pilih Provinsi
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Kota --}}
                                    <div class="col-md-6">
                                        <label for="kota" class="form-label">
                                            Kota/Kabupaten
                                        </label>

                                        <select name="kota" id="kota" class="form-select bg-light border-0 py-3"
                                            data-old="{{ old('kota', $detailSebelumnya?->kota) }}" required disabled>
                                            <option value="">
                                                Pilih Kota/Kabupaten
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Kecamatan --}}
                                    <div class="col-md-6">
                                        <label for="kecamatan" class="form-label">
                                            Kecamatan
                                        </label>

                                        <select name="kecamatan" id="kecamatan" class="form-select bg-light border-0 py-3"
                                            data-old="{{ old('kecamatan', $detailSebelumnya?->kecamatan) }}" required disabled>
                                            <option value="">
                                                Pilih Kecamatan
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kelurahan" class="form-label">
                                            Kelurahan
                                        </label>

                                        <select name="kelurahan" id="kelurahan" class="form-select bg-light border-0 py-3"
                                            data-old="{{ old('kelurahan', $detailSebelumnya?->kelurahan) }}" required disabled>
                                            <option value="">
                                                Pilih Kelurahan
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="col-12">
                                        <label for="alamat_penerima" class="form-label">
                                            Alamat Pengiriman
                                        </label>

                                        <textarea name="alamat_penerima" id="alamat_penerima" class="form-control bg-light border-0 py-3" rows="3"
                                            placeholder="Alamat Pengiriman" required>{{ old('alamat_penerima', $detailSebelumnya?->alamat_penerima) }}</textarea>
                                    </div>

                                    {{-- Catatan --}}
                                    <div class="col-12">
                                        <label for="catatan" class="form-label">
                                            Catatan Pesanan
                                        </label>

                                        <textarea name="catatan" id="catatan" class="form-control bg-light border-0 py-3" rows="2"
                                            placeholder="Catatan tambahan (opsional)">{{ old('catatan', $detailSebelumnya?->catatan) }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>


                        {{-- =================================================
                        2. SEWA TABUNG
                    ================================================== --}}
                        <div id="sewa-tabung-card" class="card border-0 shadow-sm rounded-4 mb-4">

                            <div class="card-body p-4">

                                <h5 class="fw-bold mb-4 d-flex align-items-center">
                                    <i class="fa-solid fa-gas-pump text-primary me-2"></i>
                                    2. Sewa Tabung
                                </h5>

                                <div class="alert alert-info border-0 rounded-3 small">

                                    <i class="fa-solid fa-circle-info me-2"></i>

                                    Biaya sewa tabung adalah
                                    <strong>Rp 10.000 / tabung / hari</strong>.

                                    Untuk sewa bulanan,
                                    1 bulan dihitung 30 hari.

                                </div>

                                <div class="row g-3">

                                    {{-- Jenis Sewa --}}
                                    <div class="col-md-6">

                                        <label for="jenis_sewa" class="form-label fw-semibold">
                                            Jenis Sewa
                                        </label>

                                        <select name="jenis_sewa" id="jenis_sewa"
                                            class="form-select bg-light border-0 py-3">
                                            <option value="">
                                                Tidak Menyewa
                                            </option>

                                            <option value="harian">
                                                Harian
                                            </option>

                                            <option value="bulanan">
                                                Bulanan
                                            </option>
                                        </select>

                                    </div>


                                    {{-- Jumlah Tabung --}}
                                    <div class="col-md-6">

                                        <label for="jumlah_tabung" class="form-label fw-semibold">
                                            Jumlah Tabung
                                        </label>

                                        <input type="number" name="jumlah_tabung" id="jumlah_tabung"
                                            class="form-control bg-light border-0 py-3" min="1" value="1"
                                            readonly disabled>

                                    </div>


                                    {{-- Tanggal Mulai --}}
                                    <div class="col-md-6">

                                        <label for="tanggal_mulai" class="form-label fw-semibold">
                                            Tanggal Mulai
                                        </label>

                                        <input type="date" name="mulai_sewa" id="tanggal_mulai"
                                            class="form-control bg-light border-0 py-3" disabled>

                                    </div>


                                    {{-- Durasi --}}
                                    <div class="col-md-6">

                                        <label for="durasi_sewa" class="form-label fw-semibold">
                                            Durasi
                                        </label>

                                        <div class="input-group">

                                            <input type="number" name="durasi" id="durasi_sewa"
                                                class="form-control bg-light border-0 py-3" min="1" value="1"
                                                disabled>

                                            <span class="input-group-text border-0 bg-light" id="durasi-label">
                                                Hari
                                            </span>

                                        </div>

                                    </div>


                                    {{-- Tanggal Selesai --}}
                                    <div class="col-md-6">

                                        <label for="tanggal_selesai" class="form-label fw-semibold">
                                            Tanggal Selesai
                                        </label>

                                        <input type="date" name="akhir_sewa" id="tanggal_selesai"
                                            class="form-control bg-light border-0 py-3" readonly disabled>

                                    </div>


                                    {{-- Biaya Sewa --}}
                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Biaya Sewa
                                        </label>

                                        <div class="form-control bg-light border-0 py-3 fw-bold text-primary"
                                            id="biaya_sewa">
                                            Rp 0
                                        </div>

                                    </div>

                                </div>


                                {{-- Hidden biaya --}}
                                <input type="hidden" name="biaya_sewa" id="biaya_sewa_input" value="0">

                            </div>
                        </div>

                    </div>


                    {{-- =====================================================
                    KANAN
                ====================================================== --}}
                    <div class="col-lg-4">

                        <div class="card border-0 shadow-sm rounded-4" style="position: sticky; top: 90px;">

                            <div class="card-body p-4">

                                <h5 class="fw-bold mb-4">
                                    Ringkasan Pesanan
                                </h5>


                                {{-- Items --}}
                                <div id="checkout-items" class="d-flex flex-column gap-3 mb-3">
                                    <p class="text-secondary small mb-0">
                                        Memuat keranjang...
                                    </p>
                                </div>


                                <hr>


                                {{-- Subtotal --}}
                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-secondary">
                                        Subtotal
                                    </span>

                                    <span id="checkout-subtotal">
                                        Rp 0
                                    </span>

                                </div>


                                {{-- Pinjam --}}
                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-secondary">
                                        Pinjam Tabung
                                    </span>

                                    <span id="checkout-pinjam">
                                        Rp 0
                                    </span>

                                </div>


                                {{-- Sewa --}}
                                <div class="d-flex justify-content-between mb-3">

                                    <span class="text-secondary">
                                        Sewa Tabung
                                    </span>

                                    <span id="checkout-sewa">
                                        Rp 0
                                    </span>

                                </div>


                                <hr>


                                {{-- Total --}}
                                <div class="d-flex justify-content-between align-items-center mb-4">

                                    <span class="fw-bold fs-5">
                                        Total
                                    </span>

                                    <span class="fw-bold fs-4 text-primary">
                                        <span id="checkout-total">
                                            Rp 0
                                        </span>
                                    </span>

                                </div>


                                {{-- Cart --}}
                                <input type="hidden" name="cart_items" id="cart-items-input">


                                {{-- Submit --}}
                                <button type="submit" id="checkout-submit-btn"
                                    class="btn btn-primary w-100 py-3 rounded-3 fw-semibold">

                                    <i class="fa-solid fa-lock me-2"></i>

                                    Buat Pesanan

                                </button>


                                <p class="text-center text-secondary small mt-3 mb-0">

                                    <i class="fa-solid fa-shield-halved me-1"></i>

                                    Proses Checkout Aman

                                </p>

                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>
    </main>


    {{-- =====================================================
    MIDTRANS
====================================================== --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | CONFIG
            |--------------------------------------------------------------------------
            */

            const cartKey = 'penjualan_gas_cart';

            const refillCartKey = 'penjualan_gas_refill_checkout';

            const API =
                'https://semyluase.github.io/api-indonesia/static/api';


            /*
            |--------------------------------------------------------------------------
            | ELEMENT CHECKOUT
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById('checkout-form');

            const submitBtn =
                document.getElementById('checkout-submit-btn');

            const checkoutItems =
                document.getElementById('checkout-items');

            const checkoutSubtotal =
                document.getElementById('checkout-subtotal');

            const checkoutPinjam =
                document.getElementById('checkout-pinjam');

            const checkoutSewa =
                document.getElementById('checkout-sewa');

            const checkoutTotal =
                document.getElementById('checkout-total');

            const cartItemsInput =
                document.getElementById('cart-items-input');


            /*
            |--------------------------------------------------------------------------
            | ELEMENT WILAYAH
            |--------------------------------------------------------------------------
            */

            const provinsi =
                document.getElementById('provinsi');

            const kota =
                document.getElementById('kota');

            const kecamatan =
                document.getElementById('kecamatan');

            const kelurahan =
                document.getElementById('kelurahan');

            const oldProvinsi =
                provinsi.dataset.old || '';

            const oldKota =
                kota.dataset.old || '';

            const oldKecamatan =
                kecamatan.dataset.old || '';

            const oldKelurahan =
                kelurahan.dataset.old || '';


            /*
            |--------------------------------------------------------------------------
            | ELEMENT SEWA
            |--------------------------------------------------------------------------
            */

            const jenisSewa =
                document.getElementById('jenis_sewa');

            const jumlahTabung =
                document.getElementById('jumlah_tabung');

            const tanggalMulai =
                document.getElementById('tanggal_mulai');

            const durasiSewa =
                document.getElementById('durasi_sewa');

            const durasiLabel =
                document.getElementById('durasi-label');

            const tanggalSelesai =
                document.getElementById('tanggal_selesai');

            const biayaSewaEl =
                document.getElementById('biaya_sewa');

            const biayaSewaInput =
                document.getElementById('biaya_sewa_input');

            const sewaTabungCard =
                document.getElementById('sewa-tabung-card');


            /*
            |--------------------------------------------------------------------------
            | HARGA SEWA
            |--------------------------------------------------------------------------
            */

            const HARGA_PER_HARI = 10000;

            const HARI_PER_BULAN = 30;


            /*
            |--------------------------------------------------------------------------
            | FORMAT RUPIAH
            |--------------------------------------------------------------------------
            */

            function money(value) {

                return 'Rp ' +
                    new Intl.NumberFormat('id-ID')
                    .format(Number(value) || 0);
            }


            /*
            |--------------------------------------------------------------------------
            | FORMAT BERAT
            |--------------------------------------------------------------------------
            */

            function formatBerat(value) {

                return new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 2
                }).format(Number(value) || 0) + ' kg';
            }


            /*
            |--------------------------------------------------------------------------
            | BACA CART
            |--------------------------------------------------------------------------
            */

            let cart = {};
            let isRefillCheckout = false;

            try {
                const refillCart = sessionStorage.getItem(refillCartKey);

                isRefillCheckout = Boolean(refillCart);
                cart = JSON.parse(refillCart || localStorage.getItem(cartKey)) || {};

            } catch (error) {

                console.error(
                    'Gagal membaca cart:',
                    error
                );

                cart = {};
            }


            const items =
                Object.values(cart);

            const hasRefil =
                items.some(function(item) {
                    return item.tipe_transaksi === 'refil';
                });


            /*
            |--------------------------------------------------------------------------
            | SUBTOTAL
            |--------------------------------------------------------------------------
            */

            const subtotal =
                items.reduce(function(total, item) {

                    return total +
                        (
                            Number(item.price || 0) *
                            Number(item.quantity || 0)
                        );

                }, 0);


            /*
            |--------------------------------------------------------------------------
            | BIAYA PINJAM TABUNG
            |--------------------------------------------------------------------------
            */

            const pinjam =
                items.reduce(function(total, item) {

                    if (item.is_pinjam) {

                        return total +
                            (
                                2000 *
                                Number(item.quantity || 0)
                            );
                    }

                    return total;

                }, 0);


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN PRODUK
            |--------------------------------------------------------------------------
            */

            if (items.length > 0) {

                checkoutItems.innerHTML =
                    items.map(function(item) {

                        const harga =
                            Number(item.price || 0);

                        const quantity =
                            Number(item.quantity || 0);

                        let beratHTML = '';

                        if (item.berat) {

                            beratHTML = `
                        <div class="text-secondary small">
                            Berat:
                            ${formatBerat(item.berat)}
                            / tabung
                        </div>
                    `;
                        }


                        return `
                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <div class="fw-semibold small">
                                ${item.name || 'Produk'}
                            </div>

                            <div class="text-secondary small">
                                Jumlah: ${quantity}
                            </div>

                            ${beratHTML}

                        </div>

                        <div class="fw-semibold small text-nowrap">
                            ${money(harga * quantity)}
                        </div>

                    </div>
                `;

                    }).join('');

            } else {

                checkoutItems.innerHTML = `
            <p class="text-secondary small mb-0">
                Belum ada produk di keranjang.
            </p>
        `;
            }


            /*
            |--------------------------------------------------------------------------
            | SET DATA CART
            |--------------------------------------------------------------------------
            */

            cartItemsInput.value =
                JSON.stringify(items);


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN SUBTOTAL & PINJAM
            |--------------------------------------------------------------------------
            */

            checkoutSubtotal.textContent =
                money(subtotal);

            checkoutPinjam.textContent =
                money(pinjam);


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            let biayaSewaSaatIni = 0;


            function refreshTotal() {

                const total =
                    subtotal +
                    pinjam +
                    biayaSewaSaatIni;

                checkoutTotal.textContent =
                    money(total);
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE SEWA
            |--------------------------------------------------------------------------
            */

            window.updateCheckoutSewa =
                function(biaya) {

                    biayaSewaSaatIni =
                        Number(biaya) || 0;

                    checkoutSewa.textContent =
                        money(biayaSewaSaatIni);

                    refreshTotal();
                };


            checkoutSewa.textContent =
                money(0);

            refreshTotal();


            /*
            |--------------------------------------------------------------------------
            | WILAYAH INDONESIA
            |--------------------------------------------------------------------------
            */

            loadProvinsi();


            function loadProvinsi() {

                provinsi.innerHTML =
                    '<option value="">Memuat provinsi...</option>';

                provinsi.disabled = true;


                fetch(`${API}/provinces.json`)

                    .then(function(response) {

                        if (!response.ok) {

                            throw new Error(
                                'Gagal mengambil provinsi'
                            );
                        }

                        return response.json();

                    })

                    .then(function(data) {

                        provinsi.innerHTML =
                            '<option value="">Pilih Provinsi</option>';


                        data.forEach(function(item) {

                            const option =
                                document.createElement('option');

                            option.value =
                                item.id;

                            option.textContent =
                                item.name;

                            provinsi.appendChild(option);

                        });


                        provinsi.disabled = false;


                        if (oldProvinsi) {

                            pilihWilayah(
                                provinsi,
                                oldProvinsi,
                                true
                            );
                        }

                    })

                    .catch(function(error) {

                        console.error(error);

                        provinsi.innerHTML =
                            '<option value="">Gagal memuat provinsi</option>';

                        provinsi.disabled = true;
                    });
            }


            /*
            |--------------------------------------------------------------------------
            | PROVINSI CHANGE
            |--------------------------------------------------------------------------
            */

            provinsi.addEventListener(
                'change',
                function() {

                    resetSelect(
                        kota,
                        'Pilih Kota/Kabupaten'
                    );

                    resetSelect(
                        kecamatan,
                        'Pilih Kecamatan'
                    );

                    resetSelect(
                        kelurahan,
                        'Pilih Kelurahan'
                    );


                    if (!this.value) {

                        return;
                    }


                    kota.innerHTML =
                        '<option value="">Memuat kota...</option>';

                    kota.disabled = true;


                    fetch(
                            `${API}/regencies/${this.value}.json`
                        )

                        .then(function(response) {

                            if (!response.ok) {

                                throw new Error(
                                    'Gagal mengambil kota'
                                );
                            }

                            return response.json();

                        })

                        .then(function(data) {

                            kota.innerHTML =
                                '<option value="">Pilih Kota/Kabupaten</option>';


                            data.forEach(function(item) {

                                const option =
                                    document.createElement('option');

                                option.value =
                                    item.id;

                                option.textContent =
                                    item.name;

                                kota.appendChild(option);

                            });


                            kota.disabled = false;


                            if (oldKota) {

                                pilihWilayah(
                                    kota,
                                    oldKota,
                                    true
                                );
                            }

                        })

                        .catch(function(error) {

                            console.error(error);

                            kota.innerHTML =
                                '<option value="">Gagal memuat kota</option>';

                            kota.disabled = true;
                        });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | KOTA CHANGE
            |--------------------------------------------------------------------------
            */

            kota.addEventListener(
                'change',
                function() {

                    resetSelect(
                        kecamatan,
                        'Pilih Kecamatan'
                    );

                    resetSelect(
                        kelurahan,
                        'Pilih Kelurahan'
                    );

                    if (!this.value) {

                        return;
                    }

                    kecamatan.innerHTML =
                        '<option value="">Memuat kecamatan...</option>';

                    kecamatan.disabled = true;

                    fetch(
                            `${API}/districts/${this.value}.json`
                        )

                        .then(function(response) {

                            if (!response.ok) {

                                throw new Error(
                                    'Gagal mengambil kecamatan'
                                );
                            }

                            return response.json();

                        })

                        .then(function(data) {

                            kecamatan.innerHTML =
                                '<option value="">Pilih Kecamatan</option>';

                            data.forEach(function(item) {

                                const option =
                                    document.createElement('option');

                                option.value =
                                    item.id;

                                option.textContent =
                                    item.name;

                                kecamatan.appendChild(option);

                            });

                            kecamatan.disabled = false;

                            if (oldKecamatan) {

                                pilihWilayah(
                                    kecamatan,
                                    oldKecamatan,
                                    true
                                );
                            }

                        })

                        .catch(function(error) {

                            console.error(error);

                            kecamatan.innerHTML =
                                '<option value="">Gagal memuat kecamatan</option>';

                            kecamatan.disabled = true;
                        });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | KECAMATAN CHANGE
            |--------------------------------------------------------------------------
            */

            kecamatan.addEventListener(
                'change',
                function() {

                    resetSelect(
                        kelurahan,
                        'Pilih Kelurahan'
                    );

                    if (!this.value) {

                        return;
                    }

                    kelurahan.innerHTML =
                        '<option value="">Memuat kelurahan...</option>';

                    kelurahan.disabled = true;

                    fetch(
                            `${API}/villages/${this.value}.json`
                        )

                        .then(function(response) {

                            if (!response.ok) {

                                throw new Error(
                                    'Gagal mengambil kelurahan'
                                );
                            }

                            return response.json();

                        })

                        .then(function(data) {

                            kelurahan.innerHTML =
                                '<option value="">Pilih Kelurahan</option>';

                            data.forEach(function(item) {

                                const option =
                                    document.createElement('option');

                                option.value =
                                    item.id;

                                option.textContent =
                                    item.name;

                                kelurahan.appendChild(option);

                            });

                            kelurahan.disabled = false;

                            if (oldKelurahan) {

                                pilihWilayah(
                                    kelurahan,
                                    oldKelurahan,
                                    false
                                );
                            }

                        })

                        .catch(function(error) {

                            console.error(error);

                            kelurahan.innerHTML =
                                '<option value="">Gagal memuat kelurahan</option>';

                            kelurahan.disabled = true;
                        });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | RESET SELECT
            |--------------------------------------------------------------------------
            */

            function resetSelect(
                select,
                placeholder
            ) {

                select.innerHTML =
                    `<option value="">${placeholder}</option>`;

                select.disabled = true;
            }


            /*
            |--------------------------------------------------------------------------
            | PILIH DATA LAMA
            |--------------------------------------------------------------------------
            */

            function pilihWilayah(
                select,
                nama,
                triggerChange
            ) {

                if (!nama) {

                    return;
                }


                const namaNormal =
                    nama
                    .trim()
                    .toLowerCase();


                const option =
                    Array.from(
                        select.options
                    ).find(function(item) {

                        return item.textContent
                            .trim()
                            .toLowerCase() ===
                            namaNormal;

                    });


                if (!option) {

                    return;
                }


                select.value =
                    option.value;


                if (triggerChange) {

                    select.dispatchEvent(
                        new Event('change')
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            const today =
                new Date();


            const todayStr =
                today.getFullYear() +
                '-' +
                String(today.getMonth() + 1)
                .padStart(2, '0') +
                '-' +
                String(today.getDate())
                .padStart(2, '0');


            tanggalMulai.min =
                todayStr;


            /*
            |--------------------------------------------------------------------------
            | JUMLAH TABUNG DARI CART
            |--------------------------------------------------------------------------
            */

            const jumlahTabungDariCart =
                items.reduce(function(total, item) {

                    return total +
                        (
                            Number(item.quantity) || 0
                        );

                }, 0);


            function syncJumlahTabung() {

                jumlahTabung.value =
                    Math.max(
                        1,
                        jumlahTabungDariCart
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | TOGGLE SEWA
            |--------------------------------------------------------------------------
            */

            function toggleFields() {

                const isSewa =
                    !hasRefil && jenisSewa.value !== '';

                sewaTabungCard.classList.toggle('d-none', hasRefil);

                if (hasRefil) {
                    jenisSewa.value = '';
                }


                jumlahTabung.disabled = !isSewa;

                tanggalMulai.disabled = !isSewa;

                durasiSewa.disabled = !isSewa;

                tanggalSelesai.disabled = !isSewa;


                if (!isSewa) {

                    syncJumlahTabung();

                    tanggalMulai.value =
                        '';

                    durasiSewa.value =
                        1;

                    tanggalSelesai.value =
                        '';

                    durasiLabel.textContent =
                        'Hari';

                    hitungBiaya();

                    return;
                }


                if (!tanggalMulai.value) {

                    tanggalMulai.value =
                        todayStr;
                }


                syncJumlahTabung();


                durasiSewa.min =
                    1;


                if (
                    jenisSewa.value === 'bulanan'
                ) {

                    durasiLabel.textContent =
                        'Bulan';

                } else {

                    durasiLabel.textContent =
                        'Hari';
                }


                hitungBiaya();
            }


            /*
            |--------------------------------------------------------------------------
            | HITUNG BIAYA SEWA
            |--------------------------------------------------------------------------
            */

            function hitungBiaya() {

                if (jenisSewa.value === '') {

                    biayaSewaEl.textContent =
                        money(0);

                    biayaSewaInput.value =
                        0;


                    window.updateCheckoutSewa(0);

                    return;
                }


                const jumlah =
                    Math.max(
                        1,
                        parseInt(
                            jumlahTabung.value
                        ) || 1
                    );


                const durasi =
                    Math.max(
                        1,
                        parseInt(
                            durasiSewa.value
                        ) || 1
                    );


                let totalHari;


                if (
                    jenisSewa.value === 'bulanan'
                ) {

                    totalHari =
                        durasi *
                        HARI_PER_BULAN;

                } else {

                    totalHari =
                        durasi;
                }


                const biaya =
                    jumlah *
                    totalHari *
                    HARGA_PER_HARI;


                /*
                | Tampilkan biaya
                */

                biayaSewaEl.textContent =
                    money(biaya);

                biayaSewaInput.value =
                    biaya;


                /*
                | Hitung tanggal selesai
                */

                if (tanggalMulai.value) {

                    const mulai =
                        new Date(
                            tanggalMulai.value +
                            'T00:00:00'
                        );


                    const selesai =
                        new Date(mulai);


                    selesai.setDate(
                        selesai.getDate() +
                        totalHari -
                        1
                    );


                    const tahun =
                        selesai.getFullYear();

                    const bulan =
                        String(
                            selesai.getMonth() + 1
                        ).padStart(2, '0');

                    const hari =
                        String(
                            selesai.getDate()
                        ).padStart(2, '0');


                    tanggalSelesai.value =
                        `${tahun}-${bulan}-${hari}`;
                }


                /*
                | Update total
                */

                window.updateCheckoutSewa(
                    biaya
                );
            }


            /*
            |--------------------------------------------------------------------------
            | EVENT SEWA
            |--------------------------------------------------------------------------
            */

            jenisSewa.addEventListener(
                'change',
                toggleFields
            );


            jumlahTabung.addEventListener(
                'input',
                hitungBiaya
            );


            jumlahTabung.addEventListener(
                'change',
                hitungBiaya
            );


            tanggalMulai.addEventListener(
                'input',
                hitungBiaya
            );


            tanggalMulai.addEventListener(
                'change',
                hitungBiaya
            );


            durasiSewa.addEventListener(
                'input',
                hitungBiaya
            );


            durasiSewa.addEventListener(
                'change',
                hitungBiaya
            );


            /*
            |--------------------------------------------------------------------------
            | INITIAL SEWA
            |--------------------------------------------------------------------------
            */

            toggleFields();


            /*
            |--------------------------------------------------------------------------
            | SUBMIT CHECKOUT
            |--------------------------------------------------------------------------
            */

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // =========================
                // CEK CART
                // =========================
                if (!items || items.length === 0) {
                    alert('Keranjang masih kosong.');
                    return;
                }

                // =========================
                // CART ITEMS
                // =========================
                cartItemsInput.value = JSON.stringify(items);

                // =========================
                // DISABLE BUTTON
                // =========================
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Memproses...
    `;

                try {

                    const formData = new FormData(form);

                    // =========================
                    // PROVINSI → NAMA
                    // =========================
                    if (provinsi.value) {
                        const selectedProvinsi =
                            provinsi.options[provinsi.selectedIndex];

                        if (selectedProvinsi) {
                            formData.set(
                                'provinsi',
                                selectedProvinsi.textContent.trim()
                            );
                        }
                    }

                    // =========================
                    // KOTA → NAMA
                    // =========================
                    if (kota.value) {
                        const selectedKota =
                            kota.options[kota.selectedIndex];

                        if (selectedKota) {
                            formData.set(
                                'kota',
                                selectedKota.textContent.trim()
                            );
                        }
                    }

                    // =========================
                    // KECAMATAN → NAMA
                    // =========================
                    if (kecamatan.value) {
                        const selectedKecamatan =
                            kecamatan.options[kecamatan.selectedIndex];

                        if (selectedKecamatan) {
                            formData.set(
                                'kecamatan',
                                selectedKecamatan.textContent.trim()
                            );
                        }
                    }

                    // =========================
                    // KELURAHAN → NAMA
                    // =========================
                    if (kelurahan.value) {
                        const selectedKelurahan =
                            kelurahan.options[kelurahan.selectedIndex];

                        if (selectedKelurahan) {
                            formData.set(
                                'kelurahan',
                                selectedKelurahan.textContent.trim()
                            );
                        }
                    }

                    // =========================
                    // DEBUG DATA YANG DIKIRIM
                    // =========================
                    console.log('Data checkout:', {
                        nama_penerima: formData.get('nama_penerima'),
                        telepon_penerima: formData.get('telepon_penerima'),
                        email_penerima: formData.get('email_penerima'),
                        provinsi: formData.get('provinsi'),
                        kota: formData.get('kota'),
                        kecamatan: formData.get('kecamatan'),
                        kelurahan: formData.get('kelurahan'),
                        alamat_penerima: formData.get('alamat_penerima'),
                        jenis_sewa: formData.get('jenis_sewa'),
                        jumlah_tabung: formData.get('jumlah_tabung'),
                        mulai_sewa: formData.get('mulai_sewa'),
                        durasi: formData.get('durasi'),
                        cart_items: formData.get('cart_items')
                    });

                    // =========================
                    // REQUEST KE LARAVEL
                    // =========================
                    const response = await fetch(form.action, {
                        method: 'POST',

                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },

                        body: formData
                    });

                    // =========================
                    // AMBIL RESPONSE
                    // =========================
                    const data = await response.json();

                    console.log('Response Laravel:', data);

                    // =========================
                    // JIKA ERROR
                    // =========================
                    if (!response.ok || !data.success) {

                        let pesan = data.message || 'Gagal membuat transaksi.';

                        // Tampilkan ERROR ASLI Laravel
                        if (data.error) {
                            pesan += '\n\nDetail error:\n' + data.error;
                        }

                        // Tampilkan validation error
                        if (data.errors) {

                            const validationErrors =
                                Object.values(data.errors)
                                .flat()
                                .join('\n');

                            pesan += '\n\nValidasi:\n' + validationErrors;
                        }

                        console.error('Checkout error:', data);

                        alert(pesan);

                        aktifkanTombol();

                        return;
                    }

                    // =========================
                    // BERHASIL
                    // =========================
                    console.log(
                        'Snap Token:',
                        data.snap_token
                    );

                    if (!data.snap_token) {

                        alert(
                            'Transaksi berhasil dibuat tetapi Snap Token tidak ditemukan.'
                        );

                        aktifkanTombol();

                        return;
                    }

                    // =========================
                    // MIDTRANS
                    // =========================
                    snap.pay(data.snap_token, {

                        onSuccess: function(result) {

                            console.log(
                                'Pembayaran berhasil:',
                                result
                            );

                            if (isRefillCheckout) {
                                sessionStorage.removeItem(refillCartKey);
                            } else {
                                localStorage.removeItem(cartKey);
                            }

                            window.location.href = '/';
                        },

                        onPending: function(result) {

                            console.log(
                                'Pembayaran pending:',
                                result
                            );

                            if (isRefillCheckout) {
                                sessionStorage.removeItem(refillCartKey);
                            } else {
                                localStorage.removeItem(cartKey);
                            }

                            window.location.href = '/';
                        },

                        onError: function(result) {

                            console.error(
                                'Midtrans error:',
                                result
                            );

                            alert(
                                'Pembayaran gagal. Silakan coba lagi.'
                            );

                            aktifkanTombol();
                        },

                        onClose: function() {

                            console.log(
                                'Popup Midtrans ditutup.'
                            );

                            aktifkanTombol();
                        }

                    });

                } catch (error) {

                    console.error(
                        'Checkout exception:',
                        error
                    );

                    alert(
                        'Terjadi kesalahan koneksi atau server.\n\n' +
                        error.message
                    );

                    aktifkanTombol();
                }
            });


            /*
            |--------------------------------------------------------------------------
            | AKTIFKAN KEMBALI TOMBOL
            |--------------------------------------------------------------------------
            */

            function aktifkanTombol() {

                submitBtn.disabled =
                    false;

                submitBtn.innerHTML =
                    '<i class="fa-solid fa-lock me-2"></i> Buat Pesanan';
            }

        });
    </script>
@endsection
