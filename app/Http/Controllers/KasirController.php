<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Membership;
use App\Models\TransaksiPenjualan;
use App\Models\DetailPenjualan;
use App\Models\StokGudang;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    // Scan/cari produk by barcode atau nama
    public function cariProduk(Request $request)
    {
        $keyword = $request->keyword;
        
        $produk = Produk::where('barcode', $keyword)
            ->orWhere('nama_produk', 'LIKE', "%{$keyword}%")
            ->with(['kategori', 'stokGudang'])
            ->first();

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        // Cek stok tersedia
        $stokTersedia = $produk->stokGudang->sum('jumlah_stok');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'barcode' => $produk->barcode,
                'harga_jual' => $produk->harga_jual,
                'kategori' => $produk->kategori->nama_kategori ?? '-',
                'stok' => $stokTersedia,
                'diskon' => $produk->diskon ?? 0
            ]
        ]);
    }

    // Cari membership by nama
    public function cariMembership(Request $request)
    {
        $keyword = $request->keyword;
        
        $membership = Membership::where('nama_membership', 'LIKE', "%{$keyword}%")
            ->orWhere('nama_membership', $keyword)
            ->first();

        if (!$membership) {
            return response()->json([
                'success' => false,
                'message' => 'Membership tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $membership->id,
                'nama' => $membership->nama_membership,
                'diskon_persen' => $membership->diskon_persen ?? 0
            ]
        ]);
    }

    // Proses transaksi
    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric',
            'membership_id' => 'nullable|exists:membership,id',
            'subtotal' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'total_bayar' => 'required|numeric',
            'uang_dibayar' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            // Buat transaksi penjualan
            $transaksi = TransaksiPenjualan::create([
                'no_invoice' => $this->generateNoInvoice(),
                'tanggal' => now(),
                'membership_id' => $request->membership_id,
                'pelanggan_id' => $request->membership_id, // Asumsi membership = pelanggan
                'subtotal' => $request->subtotal,
                'diskon' => $request->diskon ?? 0,
                'total_bayar' => $request->total_bayar,
                'metode_bayar' => $request->metode_bayar ?? 'tunai',
                'status' => 'selesai'
            ]);

            // Simpan detail penjualan
            foreach ($request->items as $item) {
                DetailPenjualan::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['qty'],
                    'harga_jual' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga']
                ]);

                // Kurangi stok
                $this->kurangiStok($item['produk_id'], $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data' => [
                    'no_invoice' => $transaksi->no_invoice,
                    'kembalian' => $request->uang_dibayar - $request->total_bayar,
                    'transaksi_id' => $transaksi->id
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // Generate nomor invoice
    private function generateNoInvoice()
    {
        $tanggal = date('Ymd');
        $lastInvoice = TransaksiPenjualan::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->no_invoice, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'INV' . $tanggal . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Kurangi stok produk
    private function kurangiStok($produkId, $qty)
    {
        $stokGudang = StokGudang::where('produk_id', $produkId)
            ->where('jumlah_stok', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $sisaQty = $qty;
        foreach ($stokGudang as $stok) {
            if ($sisaQty <= 0) break;

            if ($stok->jumlah_stok >= $sisaQty) {
                $stok->jumlah_stok -= $sisaQty;
                $stok->save();
                $sisaQty = 0;
            } else {
                $sisaQty -= $stok->jumlah_stok;
                $stok->jumlah_stok = 0;
                $stok->save();
            }
        }
    }

    // Cetak struk
    public function cetakStruk($id)
    {
        $transaksi = TransaksiPenjualan::with(['detailPenjualan.produk', 'membership'])
            ->findOrFail($id);

        return view('pos.struk', compact('transaksi'));
    }
}