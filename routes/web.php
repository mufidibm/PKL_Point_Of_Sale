<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StokController;
use App\Http\Controllers\DashboardController;
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



Auth::routes(); // route bawaan laravel/ui (login, register, dll)

// ✅ Dashboard (default setelah login)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// ✅ Group route admin (harus login)
Route::middleware(['auth'])->group(function () {

    // Master Data
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('gudang', GudangController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('membership', MembershipController::class);
    Route::resource('stok', StokController::class);
    Route::resource('laporan', LaporanController::class);

    // Transaksi
    Route::resource('penjualan', TransaksiPenjualanController::class);
    Route::resource('pembelian', TransaksiPembelianController::class);

    // Retur
    Route::resource('retur-penjualan', ReturPenjualanController::class);
    Route::resource('retur-pembelian', ReturPembelianController::class);
});

//pelanggan
Route::resource('pelanggan', PelangganController::class);

//Gudang
Route::resource('gudang', GudangController::class);

//Stok gudang
Route::resource('stokgudang', StokController::class);

//Karyawan
Route::resource('karyawan', KaryawanController::class);

//Membership
Route::resource('membership', MembershipController::class);