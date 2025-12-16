<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\ReturPenjualanController;
use App\Http\Controllers\ReturPembelianController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

// === GUNAKAN LARAVEL BREEZE AUTHENTICATION ===
// File ini otomatis dibuat pas php artisan breeze:install
require __DIR__ . '/auth.php';

// Redirect root ke dashboard kalau sudah login, kalau belum otomatis ke login (Breeze handle)
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Dashboard (sama dengan root, tapi biar ada nama route)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// === SEMUA ROUTE ADMIN (hanya role admin) ===
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('user', UserController::class);

    // Master Data
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('stokgudang', StokController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('membership', MembershipController::class);

    // Transaksi
    Route::resource('penjualan', TransaksiPenjualanController::class);
    Route::resource('pembelian', TransaksiPembelianController::class);

    // Retur
    Route::resource('retur-penjualan', ReturPenjualanController::class);
    Route::resource('retur-pembelian', ReturPembelianController::class);
    // Laporan (sesuai yang kamu punya)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/{type}', [LaporanController::class, 'export'])->name('laporan.export');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    //POS
    Route::prefix('pos')->group(function () {
        Route::get('/kasir', [KasirController::class, 'index'])->name('pos.index');
        Route::get('/cari-produk', [KasirController::class, 'cariProduk']);
        Route::get('/cari-membership', [KasirController::class, 'cariMembership']);
        Route::post('/proses', [KasirController::class, 'prosesTransaksi']);
        Route::get('/cetak-struk/{id}', [KasirController::class, 'cetakStruk']);
    });
});

//pelanggan
Route::resource('pelanggan', PelangganController::class);

//Gudang
Route::resource('gudang', GudangController::class);

// === POS / KASIR (bisa diakses oleh kasir nanti tinggal tambah role:kasir) ===

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/hapus-foto', [ProfileController::class, 'hapusFoto'])
        ->name('profile.hapus-foto');
});

// Tambahkan route ini di dalam group admin middleware
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('returpenjualan', ReturPenjualanController::class);
    Route::get('returpenjualan/get-detail/{id}', [ReturPenjualanController::class, 'getDetailTransaksi']);
});

// Tambahkan route ini di dalam group admin middleware
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('returpembelian', ReturPembelianController::class);
    Route::get('returpembelian/get-detail/{id}', [ReturPembelianController::class, 'getDetailTransaksi']);
});
