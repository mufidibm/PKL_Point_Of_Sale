<?php

namespace App\Http\Controllers;

use App\Models\ReturPenjualan;
use App\Models\TransaksiPenjualan;
use App\Models\Produk;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        $returs = ReturPenjualan::with(['transaksi', 'produk'])
            ->orderBy('tanggal_retur', 'desc')
            ->paginate(10);
        
        return view('admin.retur.returpenjualan.index', compact('returs'));
    }

    public function create()
    {
        $transaksis = TransaksiPenjualan::with('detailPembelian.produk')
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('admin.retur.returpenjualan.create', compact('transaksis'));
    }

    public function getDetailTransaksi($id)
    {
        $transaksi = TransaksiPenjualan::with('detailPembelian.produk')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $transaksi->detailPembelian->map(function($detail) {
                return [
                    'id_produk' => $detail->id_produk,
                    'nama_produk' => $detail->produk->nama_produk,
                    'jumlah' => $detail->jumlah,
                    'harga_beli' => $detail->harga_beli,
                    'subtotal' => $detail->subtotal
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id_transaksi',
            'produk_id' => 'required|exists:produks,id_produk',
            'tanggal_retur' => 'required|date',
            'jumlah_retur' => 'required|numeric|min:1',
            'nilai_retur' => 'required|numeric|min:0',
            'alasan' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah jumlah retur tidak melebihi jumlah pembelian
            $detail = DB::table('detail_pembelians')
                ->where('id_pembelian', $request->transaksi_id)
                ->where('id_produk', $request->produk_id)
                ->first();

            if (!$detail) {
                return redirect()->back()
                    ->with('error', 'Produk tidak ditemukan dalam transaksi ini')
                    ->withInput();
            }

            // Hitung total retur yang sudah ada
            $totalReturSebelumnya = ReturPenjualan::where('transaksi_id', $request->transaksi_id)
                ->where('produk_id', $request->produk_id)
                ->sum('jumlah_retur');

            if (($totalReturSebelumnya + $request->jumlah_retur) > $detail->jumlah) {
                return redirect()->back()
                    ->with('error', 'Jumlah retur melebihi jumlah pembelian')
                    ->withInput();
            }

            // Simpan retur penjualan
            $retur = ReturPenjualan::create($request->all());

            // Update stok produk (kembalikan stok)
            $stok = StokGudang::where('id_produk', $request->produk_id)
                ->where('id_cabang', $detail->id_cabang ?? 1)
                ->first();

            if ($stok) {
                $stok->increment('jumlah_stok', $request->jumlah_retur);
            }

            DB::commit();

            return redirect()->route('returpenjualan.index')
                ->with('success', 'Retur penjualan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan retur: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $retur = ReturPenjualan::with(['transaksi', 'produk'])->findOrFail($id);
        return view('admin.retur.returpenjualan.show', compact('retur'));
    }

    public function edit($id)
    {
        $retur = ReturPenjualan::findOrFail($id);
        $transaksis = TransaksiPenjualan::where('status', 'selesai')
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();
        $produks = Produk::all();
        
        return view('admin.retur.returpenjualan.edit', compact('retur', 'transaksis', 'produks'));
    }

    public function update(Request $request, $id)
    {   
        $request->validate([
            'tanggal_retur' => 'required|date',
            'jumlah_retur' => 'required|numeric|min:1',
            'nilai_retur' => 'required|numeric|min:0',
            'alasan' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $retur = ReturPenjualan::findOrFail($id);
            $selisihJumlah = $request->jumlah_retur - $retur->jumlah_retur;

            // Update stok jika jumlah berubah
            if ($selisihJumlah != 0) {
                $stok = StokGudang::where('id_produk', $retur->produk_id)->first();
                if ($stok) {
                    if ($selisihJumlah > 0) {
                        $stok->increment('jumlah_stok', abs($selisihJumlah));
                    } else {
                        $stok->decrement('jumlah_stok', abs($selisihJumlah));
                    }
                }
            }

            $retur->update([
                'tanggal_retur' => $request->tanggal_retur,
                'jumlah_retur' => $request->jumlah_retur,
                'nilai_retur' => $request->nilai_retur,
                'alasan' => $request->alasan
            ]);

            DB::commit();

            return redirect()->route('returpenjualan.index')
                ->with('success', 'Retur penjualan berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate retur: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $retur = ReturPenjualan::findOrFail($id);

            // Kembalikan stok
            $stok = StokGudang::where('id_produk', $retur->produk_id)->first();
            if ($stok) {
                $stok->decrement('jumlah_stok', $retur->jumlah_retur);
            }

            $retur->delete();

            DB::commit();

            return redirect()->route('returpenjualan.index')
                ->with('success', 'Retur penjualan berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus retur: ' . $e->getMessage());
        }
    }
}