<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UlasanController as AdminUlasanController;
use App\Http\Controllers\Site\AuthPageController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CheckoutController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ProfilePageController;
use App\Http\Controllers\Site\ReviewController;
use App\Http\Controllers\Site\ShopController;
use App\Http\Controllers\Site\TrackingController;
use App\Http\Controllers\Site\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ganti Bahasa (ID / EN)
|--------------------------------------------------------------------------
| Menyimpan pilihan bahasa ke session, lalu kembali ke halaman asal.
*/

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

/*
|--------------------------------------------------------------------------
| Halaman publik (TrendUp store)
|--------------------------------------------------------------------------
| Bisa diakses tanpa login.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// Halaman asli memakai query string (?id=...) lewat JS, jadi rute ini dipertahankan
// sama seperti aslinya (bukan {id} sebagai route parameter).
Route::get('/produk-detail', [ProductController::class, 'show'])->name('product.detail');

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::get('/tracking/search', [TrackingController::class, 'search'])->name('tracking.search');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

Route::get('/login', [AuthPageController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthPageController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthPageController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthPageController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthPageController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Halaman yang wajib login (checkout, wishlist)
|--------------------------------------------------------------------------
| Kalau belum login, otomatis diarahkan ke halaman /login.
*/

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order-success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    Route::post('/produk/{product}/ulasan', [ReviewController::class, 'store'])->name('review.store');

    Route::get('/profile', [ProfilePageController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfilePageController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfilePageController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Halaman admin (Dashboard TrendUp)
|--------------------------------------------------------------------------
| Wajib login DAN role-nya harus 'admin'. User biasa yang mencoba akses
| akan mendapat error 403 (Forbidden).
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::put('/produk/{product}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{product}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{category}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{category}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
    Route::put('/pesanan/{order}', [PesananController::class, 'updateStatus'])->name('pesanan.update');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan');
    Route::patch('/pelanggan/{user}/status', [PelangganController::class, 'toggleStatus'])->name('pelanggan.toggle-status');
    Route::get('/ulasan', [AdminUlasanController::class, 'index'])->name('ulasan');
    Route::delete('/ulasan/{review}', [AdminUlasanController::class, 'destroy'])->name('ulasan.destroy');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::delete('/laporan/{report}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/toko', [PengaturanController::class, 'updateToko'])->name('pengaturan.toko');
    Route::put('/pengaturan/pengiriman', [PengaturanController::class, 'updatePengiriman'])->name('pengaturan.pengiriman');
    Route::put('/pengaturan/notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi');
    Route::put('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::post('/pengaturan/bank', [PengaturanController::class, 'storeBank'])->name('pengaturan.bank.store');
    Route::put('/pengaturan/bank/{bank}', [PengaturanController::class, 'updateBank'])->name('pengaturan.bank.update');
    Route::delete('/pengaturan/bank/{bank}', [PengaturanController::class, 'destroyBank'])->name('pengaturan.bank.destroy');
});