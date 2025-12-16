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

    
    public function cariProduk(Request $request)
    {
        $keyword = $request->keyword;
        
        $produk = Produk::where('barcode', $keyword)
            ->orWhere('nama_produk', 'LIKE', "%{$keyword}%")
            ->with(['kategori', 'stokGudang'])
            ->get();

        if ($produk->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $data = $produk->map(function($p) {
            $stokTersedia = $p->stokGudang ? $p->stokGudang->sum('jumlah_stok') : 0;
            
            return [
                'id' => $p->id,
                'nama_produk' => $p->nama_produk,
                'barcode' => $p->barcode,
                'harga_jual' => $p->harga_jual,
                'kategori' => $p->kategori ? $p->kategori->nama_kategori : '-',
                'stok' => $stokTersedia,
                'diskon' => $p->diskon ?? 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function cariMembership(Request $request)
    {
        $keyword = $request->keyword;
        
        $membership = Membership::where('nama_membership', 'LIKE', "%{$keyword}%")
            ->limit(10)
            ->get();

        if ($membership->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Membership tidak ditemukan'
            ], 404);
        }

        $data = $membership->map(function($m) {
            return [
                'id' => $m->id,
                'nama' => $m->nama_membership,
                'diskon_persen' => $m->diskon_persen ?? 0
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // PERBAIKAN: Validation disesuaikan dengan nama tabel
    public function prosesTransaksi(Request $request)
    {
        // Validasi dengan nama tabel yang benar
        $request->validate([
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:produks,id', // ← PERBAIKAN: produks bukan produk
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric',
            'membership_id' => 'nullable|exists:memberships,id', // ← PERBAIKAN: memberships
            'subtotal' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'total_bayar' => 'required|numeric',
            'uang_dibayar' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            // Data transaksi
            $dataTransaksi = [
                'no_invoice' => $this->generateNoInvoice(),
                'tanggal' => now(),
                'subtotal' => $request->subtotal,
                'diskon' => $request->diskon ?? 0,
                'total_bayar' => $request->total_bayar,
                'metode_bayar' => $request->metode_bayar ?? 'tunai',
                'status' => 'selesai',
                'karyawan_id' => auth()->id() // ← User yang login sebagai kasir
            ];

            // Tambahkan membership_id dan pelanggan_id hanya jika ada
            if ($request->membership_id) {
                $dataTransaksi['membership_id'] = $request->membership_id;
                $dataTransaksi['pelanggan_id'] = $request->membership_id;
            }
            
            // Buat transaksi penjualan
            $transaksi = TransaksiPenjualan::create($dataTransaksi);

            // Simpan detail penjualan
            foreach ($request->items as $item) {
                DetailPenjualan::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['qty'],
                    'harga_satuan' => $item['harga'],  // ← PERBAIKAN: tambah harga_satuan
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Transaction Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

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

    public function cetakStruk($id)
    {
        $transaksi = TransaksiPenjualan::with(['detailPenjualan.produk', 'membership'])
            ->findOrFail($id);

        return view('pos.struk', compact('transaksi'));
    }
}