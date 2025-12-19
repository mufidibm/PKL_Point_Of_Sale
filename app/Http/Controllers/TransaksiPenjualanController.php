<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use App\Models\Produk;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $transaksis = TransaksiPenjualan::with(['pelanggan.membership', 'karyawan'])->latest()->get();
        return view('admin.transaksi.penjualan.index', compact('transaksis'));
    }

   public function create()
{
    $pelanggans = Pelanggan::with('membership')->get();
    $karyawans = Karyawan::all();
    $produks = Produk::with('stokGudang')->get();
    $pelangganUmum = Pelanggan::where('nama', 'Umum')->first() 
    ?? Pelanggan::create(['nama' => 'Umum']); // otomatis buat kalau belum ada

    return view('admin.transaksi.penjualan.create', compact(
        'pelanggans', 'karyawans', 'produks', 'pelangganUmum'
    ));
}


    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal' => 'required|date',
            'metode_bayar' => 'required|in:tunai,kartu,transfer',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['jumlah'] * $item['harga_jual'];
            }

            $pelanggan = Pelanggan::find($request->pelanggan_id);
            $diskon = $pelanggan?->membership?->diskon_persen ?? 0;
            $diskonAmount = $subtotal * ($diskon / 100);
            $totalBayar = $subtotal - $diskonAmount;

            $transaksi = TransaksiPenjualan::create([
                'pelanggan_id' => $request->pelanggan_id,
                'karyawan_id' => $request->karyawan_id,
                'tanggal' => $request->tanggal,
                'subtotal' => $subtotal,
                'diskon' => $diskonAmount,
                'total_bayar' => $totalBayar,
                'metode_bayar' => $request->metode_bayar,
            ]);

            foreach ($request->items as $item) {
                DetailPenjualan::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_jual'],
                    'subtotal' => $item['jumlah'] * $item['harga_jual'],
                ]);

                // Kurangi stok
                $stok = StokGudang::where('produk_id', $item['produk_id'])->first();
                if ($stok && $stok->jumlah_stok >= $item['jumlah']) {
                    $stok->decrement('jumlah_stok', $item['jumlah']);
                } else {
                    throw new \Exception("Stok produk tidak cukup.");
                }
            }
        });

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan.');
    }

public function edit(TransaksiPenjualan $transaksiPenjualan)
{
    $transaksiPenjualan->load('detailPenjualan.produk');
    $pelanggans = Pelanggan::with('membership')->get();
    $karyawans = Karyawan::all();
    $produks = Produk::with('stokGudang')->get();
    $pelangganUmum = Pelanggan::where('nama', 'Umum')->firstOrFail(); // tambah ini

    return view('admin.transaksi.penjualan.edit', compact(
        'transaksiPenjualan', 'pelanggans', 'karyawans', 'produks', 'pelangganUmum'
    ));
}
public function update(Request $request, TransaksiPenjualan $transaksiPenjualan)
{
    $request->validate([
        'pelanggan_id' => 'nullable|exists:pelanggans,id',
        'karyawan_id' => 'required|exists:karyawans,id',
        'tanggal' => 'required|date',
        'metode_bayar' => 'required|in:tunai,kartu,transfer',
        'items.*.produk_id' => 'required|exists:produks,id',
        'items.*.jumlah' => 'required|integer|min:1',
        'items.*.harga_jual' => 'required|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $transaksiPenjualan) {
        // 1. Kembalikan stok dari detail lama
        foreach ($transaksiPenjualan->detailPenjualan as $oldDetail) {
            StokGudang::where('produk_id', $oldDetail->produk_id)
                      ->increment('jumlah_stok', $oldDetail->jumlah);
        }

        // 2. Hapus detail lama
        $transaksiPenjualan->detailPenjualan()->delete();

        // 3. Hitung subtotal baru
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['jumlah'] * $item['harga_jual'];
        }

        // 4. Hitung diskon dari membership
        $diskonPersen = 0;
        if ($request->pelanggan_id) {
            $pelanggan = Pelanggan::find($request->pelanggan_id);
            $diskonPersen = $pelanggan?->membership?->diskon_persen ?? 0;
        }
        $diskonAmount = $subtotal * ($diskonPersen / 100);
        $totalBayar = $subtotal - $diskonAmount;

        // 5. Update transaksi utama
        $transaksiPenjualan->update([
            'pelanggan_id' => $request->pelanggan_id,
            'karyawan_id' => $request->karyawan_id,
            'tanggal' => $request->tanggal,
            'subtotal' => $subtotal,
            'diskon' => $diskonAmount,
            'total_bayar' => $totalBayar,
            'metode_bayar' => $request->metode_bayar,
        ]);

        // 6. Buat detail baru
        foreach ($request->items as $item) {
            DetailPenjualan::create([
                'transaksi_id' => $transaksiPenjualan->id,
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_jual'],
                'subtotal' => $item['jumlah'] * $item['harga_jual'],
            ]);

            // 7. Kurangi stok baru
            $stok = StokGudang::where('produk_id', $item['produk_id'])->first();
            if ($stok && $stok->jumlah_stok >= $item['jumlah']) {
                $stok->decrement('jumlah_stok', $item['jumlah']);
            } else {
                throw new \Exception("Stok produk ID {$item['produk_id']} tidak mencukupi.");
            }
        }
    });

    return redirect()
        ->route('penjualan.index')
        ->with('success', 'Transaksi penjualan berhasil diperbarui.');
}

public function show(TransaksiPenjualan $transaksiPenjualan)
{
    $transaksiPenjualan->load('detailPenjualan.produk', 'pelanggan.membership', 'karyawan');
    return view('admin.transaksi.penjualan.show', compact('transaksiPenjualan'));
}
}