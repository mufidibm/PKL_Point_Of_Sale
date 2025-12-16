<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use App\Models\DetailPembelian;
use App\Models\Supplier;
use App\Models\Karyawan;
use App\Models\Produk;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPembelian::with(['supplier', 'karyawan'])->latest()->get();
        return view('admin.transaksi.pembelian.index', compact('transaksis'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $karyawans = Karyawan::all();
        $produks = Produk::with('stokGudang')->get();
        return view('admin.transaksi.pembelian.create', compact('suppliers', 'karyawans', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['jumlah'] * $item['harga_beli'];
            }

            $transaksi = TransaksiPembelian::create([
                'supplier_id' => $request->supplier_id,
                'karyawan_id' => $request->karyawan_id,
                'tanggal' => $request->tanggal,
                'total_biaya' => $total,
            ]);

            foreach ($request->items as $item) {
                DetailPembelian::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $item['jumlah'] * $item['harga_beli'],
                ]);

                // Update stok
                StokGudang::updateOrCreate(
                    ['produk_id' => $item['produk_id'], 'gudang_id' => 1], // ganti gudang_id jika dinamis
                    ['jumlah_stok' => DB::raw("jumlah_stok + {$item['jumlah']}")]
                );
            }
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function edit(TransaksiPembelian $transaksiPembelian)
{
    $transaksiPembelian->load('detailPembelian.produk');
    $suppliers = Supplier::all();
    $karyawans = Karyawan::all();
    $produks = Produk::with('stokGudang')->get();
    return view('admin.transaksi.pembelian.edit', compact(
        'transaksiPembelian', 'suppliers', 'karyawans', 'produks'
    ));
}

public function update(Request $request, TransaksiPembelian $transaksiPembelian)
{
    $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'karyawan_id' => 'required|exists:karyawans,id',
        'tanggal' => 'required|date',
        'items.*.produk_id' => 'required|exists:produks,id',
        'items.*.jumlah' => 'required|integer|min:1',
        'items.*.harga_beli' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $transaksiPembelian) {
        // 1. Kurangi stok lama (kembalikan)
        foreach ($transaksiPembelian->detailPembelian as $oldDetail) {
            StokGudang::where('produk_id', $oldDetail->produk_id)
                      ->decrement('jumlah_stok', $oldDetail->jumlah);
        }

        // 2. Hapus detail lama
        $transaksiPembelian->detailPembelian()->delete();

        // 3. Hitung total biaya baru
        $totalBiaya = 0;
        foreach ($request->items as $item) {
            $totalBiaya += $item['jumlah'] * $item['harga_beli'];
        }

        // 4. Update transaksi utama
        $transaksiPembelian->update([
            'supplier_id' => $request->supplier_id,
            'karyawan_id' => $request->karyawan_id,
            'tanggal' => $request->tanggal,
            'total_biaya' => $totalBiaya,
        ]);

        // 5. Buat detail baru
        foreach ($request->items as $item) {
            DetailPembelian::create([
                'transaksi_id' => $transaksiPembelian->id,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_beli' => $item['harga_beli'],
                'subtotal' => $item['jumlah'] * $item['harga_beli'],
            ]);

            // 6. Tambah stok baru
            StokGudang::updateOrCreate(
                ['produk_id' => $item['produk_id'], 'gudang_id' => 1], // sesuaikan gudang
                ['jumlah_stok' => DB::raw("jumlah_stok + {$item['jumlah']}")]
            );
        }
    });

    return redirect()
        ->route('pembelian.index')
        ->with('success', 'Transaksi pembelian berhasil diperbarui.');
}

    public function show(TransaksiPembelian $transaksiPembelian)
    {
        $transaksiPembelian->load('detailPembelian.produk', 'supplier', 'karyawan');
        return view('admin.transaksi.pembelian.show', compact('transaksiPembelian'));
    }
}