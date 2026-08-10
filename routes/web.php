<?php

use App\Http\Controllers\customer\AuthController;
use App\Http\Controllers\customer\HomeController;
use App\Http\Controllers\customer\KontakController;
use App\Http\Controllers\customer\PasswordResetController;
use App\Http\Controllers\customer\ProdukController;
use App\Http\Controllers\customer\TentangController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('customer.home.index');
});


//customer routes
Route::get('/', [HomeController::class, 'index'])->name('/');

Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

Route::get('/tentang-kami', [TentangController::class, 'index'])->name('tentang.index');

Route::get('/kontak-kami', [KontakController::class, 'index'])->name('kontak.index');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
