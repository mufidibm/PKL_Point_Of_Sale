<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\TransaksiPenjualan;
use App\Models\TransaksiPembelian;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // pastikan harus login
    }

    public function index()
    {
        // Ambil data ringkasan (bisa kamu ubah sesuai kebutuhan)
        $totalProduk = Produk::count();
        $totalPelanggan = Pelanggan::count();
        $totalPenjualan = TransaksiPenjualan::count();
        $totalPembelian = TransaksiPembelian::count();

        return view('dashboard', compact(
            'totalProduk',
            'totalPelanggan',
            'totalPenjualan',
            'totalPembelian'
        ));
    }
}
