<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataTokoController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MidtransNotificationController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.store.index')->name('home');
Volt::route('/katalog', 'pages.store.catalog')->name('catalog');
Volt::route('/produk/{produk:slug}', 'pages.store.show')->name('products.show');
Volt::route('/keranjang', 'pages.store.cart')->name('cart');

Volt::route('/checkout', 'pages.store.checkout')
    ->middleware('auth')
    ->name('checkout');

Volt::route('/pesanan', 'pages.store.pesanan')
    ->middleware('auth')
    ->name('pesanan.index');

Volt::route('/pesanan/{pesanan:nomor}/sukses', 'pages.store.checkout-sukses')
    ->middleware('auth')
    ->name('pesanan.sukses');

Volt::route('/pesanan/{pesanan:nomor}/bayar', 'pages.store.pembayaran')
    ->middleware('auth')
    ->name('pesanan.bayar');

Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/produk', ProdukController::class);
    Route::resource('/kategori', KategoriController::class)->except(['show']);
    Route::patch('/kategori/{kategori}/toggle', [KategoriController::class, 'toggle'])->name('kategori.toggle');

    Route::get('/toko', [DataTokoController::class, 'edit'])->name('toko.edit');
    Route::put('/toko', [DataTokoController::class, 'update'])->name('toko.update');

    Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
    Route::put('/pengguna/{user}/role', [UserController::class, 'updateRole'])->name('pengguna.updateRole');
    Route::patch('/pengguna/{user}/aktif', [UserController::class, 'toggleAktif'])->name('pengguna.toggleAktif');

    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::post('/pesanan/{pesanan}/verifikasi', [PesananController::class, 'verifikasiPembayaran'])->name('pesanan.verifikasiPembayaran');
    Route::post('/pesanan/{pesanan}/tolak', [PesananController::class, 'tolakPembayaran'])->name('pesanan.tolak');
});

require __DIR__.'/auth.php';
