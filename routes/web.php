<?php

use App\Http\Controllers\admin\AuthController as AdminAuthController;
use App\Http\Controllers\admin\HomeController as AdminHomeController;
use App\Http\Controllers\admin\PelangganController;
use App\Http\Controllers\admin\PembelianController as AdminPembelianController;
use App\Http\Controllers\admin\ProdukController as AdminProdukController;
use App\Http\Controllers\admin\TransaksiController;
use App\Http\Controllers\customer\AuthController;
use App\Http\Controllers\customer\HomeController;
use App\Http\Controllers\customer\KontakController;
use App\Http\Controllers\customer\PasswordResetController;
use App\Http\Controllers\customer\PembelianController;
use App\Http\Controllers\customer\ProdukController;
use App\Http\Controllers\customer\TentangController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| CUSTOMER - PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
Route::get('/tentang-kami', [TentangController::class, 'index'])->name('tentang.index');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak.index');


/*
|--------------------------------------------------------------------------
| CUSTOMER - AUTH (GUEST ONLY, guard: web)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:web')->group(function () {

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');

    // Forgot Password
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});


/*
|--------------------------------------------------------------------------
| CUSTOMER - REGISTER OTP
|--------------------------------------------------------------------------
*/

Route::prefix('register')->name('register.')->group(function () {

    Route::get('/verify', [AuthController::class, 'showRegisterOtpForm'])->name('otp.form');
    Route::post('/verify', [AuthController::class, 'verifyRegisterOtp'])->name('otp.submit');

    Route::post('/verify/resend', [AuthController::class, 'resendRegisterOtp'])
        ->middleware('throttle:3,1')
        ->name('otp.resend');
});


/*
|--------------------------------------------------------------------------
| CUSTOMER - GOOGLE AUTHENTICATION
|--------------------------------------------------------------------------
|
| Tidak pakai guest/auth karena dipakai untuk login Google
| maupun menghubungkan akun Google ke user yang sedang login.
|
*/

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::prefix('auth/google')->name('google.')->group(function () {

    Route::get('/confirm', [AuthController::class, 'showConfirmGoogleLinkForm'])->name('confirm.form');
    Route::post('/confirm', [AuthController::class, 'confirmGoogleLink'])->name('confirm.submit');

    Route::post('/confirm/resend', [AuthController::class, 'resendGoogleCode'])
        ->middleware('throttle:3,1')
        ->name('confirm.resend');
});


/*
|--------------------------------------------------------------------------
| CUSTOMER - AUTHENTICATED (guard: web)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:web')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('user')->name('user.')->group(function () {

        // Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/form-chekout', [PembelianController::class, 'showChekout'])->name("checkout");
        Route::post('/checkout/transaction', [PembelianController::class, 'createTransaction'])->name('checkout.transaction');
        Route::get('/pesanan/{id_pembelian}/payment-token', [PembelianController::class, 'paymentToken'])->name('pesanan.payment-token');
        Route::post('/pesanan/{id_pembelian}/sync-payment-status', [PembelianController::class, 'syncPaymentStatus'])
            ->middleware('throttle:10,1')
            ->name('pesanan.sync-payment-status');
        Route::get('/pesanan-saya', [PembelianController::class, 'showPesanan'])->name("pesanan");
        Route::get('/pesanan/{id_pembelian}/refill-tabung', [PembelianController::class, 'showRefill'])->name('refill');
        Route::get('pesanan/detail/{id_pembelian}', [PembelianController::class, 'showPesananDetail'])
            ->name('pesanan-detail');
        Route::get('/form-profile', [AuthController::class, "showProfile"])->name('profile');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profil.update');
        Route::put('/profile/ktp', [AuthController::class, 'updateKtp'])->name('profil.ktp.update');
        Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profil.password.update');
    });
});


/*
|-----------------------------------------------------------------------    ---
| ADMIN
|--------------------------------------------------------------------------
|
| Guard terpisah ('admin') supaya sesi login admin tidak
| bercampur dengan sesi login customer (guard 'web').
|
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Guest admin (belum login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'FormLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated admin
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        // dashboard
        Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard');
        // pelanggan

        Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan');
        Route::patch('/pelanggan/{id}/status', [PelangganController::class, 'updateStatus'])->name('pelanggan.status');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // produk
        Route::get('/produk', [AdminProdukController::class, 'ShowProduk'])->name('produk');
        Route::post('/produk', [AdminProdukController::class, 'store'])->name('produk.store');
        Route::put('/produk/{produk}', [AdminProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [AdminProdukController::class, 'destroy'])->name('produk.destroy');

        // pembelian
        Route::get('/pembelian', [AdminPembelianController::class, 'ShowPembelian'])->name('pembelian');
        Route::patch('/pembelian/{pembelian}/payment-status', [AdminPembelianController::class, 'updatePaymentStatus'])
            ->name('pembelian.payment-status');


        // transaksi
        // barang masuk
        Route::get('/barang_masuk', [TransaksiController::class, 'showBarangMasuk'])->name('barang-masuk');
        Route::post('/barang_masuk', [TransaksiController::class, 'storeBarangMasuk'])->name('barang-masuk.store');
        Route::get('/barang_keluar', [TransaksiController::class, 'showBarangKeluar'])->name('barang-keluar');
    });
});
