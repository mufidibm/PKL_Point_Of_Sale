<?php

namespace App\Http\Controllers;

use App\Models\ReturPembelian;
use App\Models\TransaksiPembelian;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPembelianController extends Controller
{
    public function index()
    {
        $returs = ReturPembelian::with(['transaksi.supplier', 'produk'])
            ->orderBy('tanggal_retur', 'desc')
            ->paginate(10);
        
        return view('admin.retur.returpembelian.index', compact('returs'));
    }

    public function create()
    {
        $transaksis = TransaksiPembelian::with(['supplier', 'detailTransaksi.produk'])
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('admin.retur.returpembelian.create', compact('transaksis'));
    }

    public function getDetailTransaksi($id)
    {
        $transaksi = TransaksiPembelian::with('detailTransaksi.produk')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'supplier' => [
                'nama' => $transaksi->supplier->nama_supplier ?? '-',
                'telepon' => $transaksi->supplier->telepon ?? '-'
            ],
            'data' => $transaksi->detailTransaksi->map(function($detail) {
                return [
                    'id_produk' => $detail->id_produk,
                    'nama_produk' => $detail->produk->nama_produk,
                    'kode_produk' => $detail->produk->kode_produk,
                    'jumlah' => $detail->jumlah,
                    'harga' => $detail->harga,
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
            $detail = DB::table('detail_transaksis')
                ->where('id_transaksi', $request->transaksi_id)
                ->where('id_produk', $request->produk_id)
                ->first();

            if (!$detail) {
                return redirect()->back()
                    ->with('error', 'Produk tidak ditemukan dalam transaksi ini')
                    ->withInput();
            }

            // Hitung total retur yang sudah ada
            $totalReturSebelumnya = ReturPembelian::where('transaksi_id', $request->transaksi_id)
                ->where('produk_id', $request->produk_id)
                ->sum('jumlah_retur');

            if (($totalReturSebelumnya + $request->jumlah_retur) > $detail->jumlah) {
                return redirect()->back()
                    ->with('error', 'Jumlah retur melebihi jumlah pembelian')
                    ->withInput();
            }

            // Simpan retur pembelian
            $retur = ReturPembelian::create($request->all());

            // Update stok produk (kurangi stok karena barang dikembalikan ke supplier)
            $transaksi = TransaksiPembelian::find($request->transaksi_id);
            $stok = StokGudang::where('id_produk', $request->produk_id)
                ->where('id_cabang', $transaksi->id_cabang ?? 1)
                ->first();

            if ($stok) {
                $stok->decrement('jumlah_stok', $request->jumlah_retur);
            }

            DB::commit();

            return redirect()->route('returpembelian.index')
                ->with('success', 'Retur pembelian berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan retur: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $retur = ReturPembelian::with(['transaksi.supplier', 'produk'])->findOrFail($id);
        return view('admin.retur.returpembelian.show', compact('retur'));
    }

    public function edit($id)
    {
        $retur = ReturPembelian::with(['transaksi', 'produk'])->findOrFail($id);
        $transaksis = TransaksiPembelian::with('supplier')
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->get();
        
        return view('admin.retur.returpembelian.edit', compact('retur', 'transaksis'));
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

            $retur = ReturPembelian::findOrFail($id);
            $selisihJumlah = $request->jumlah_retur - $retur->jumlah_retur;

            // Update stok jika jumlah berubah
            if ($selisihJumlah != 0) {
                $transaksi = TransaksiPembelian::find($retur->transaksi_id);
                $stok = StokGudang::where('id_produk', $retur->produk_id)
                    ->where('id_cabang', $transaksi->id_cabang ?? 1)
                    ->first();
                    
                if ($stok) {
                    if ($selisihJumlah > 0) {
                        // Jika jumlah retur bertambah, kurangi stok
                        $stok->decrement('jumlah_stok', abs($selisihJumlah));
                    } else {
                        // Jika jumlah retur berkurang, tambah stok
                        $stok->increment('jumlah_stok', abs($selisihJumlah));
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

            return redirect()->route('returpembelian.index')
                ->with('success', 'Retur pembelian berhasil diupdate');

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

            $retur = ReturPembelian::findOrFail($id);

            // Kembalikan stok (tambah stok karena retur dibatalkan)
            $transaksi = TransaksiPembelian::find($retur->transaksi_id);
            $stok = StokGudang::where('id_produk', $retur->produk_id)
                ->where('id_cabang', $transaksi->id_cabang ?? 1)
                ->first();
                
            if ($stok) {
                $stok->increment('jumlah_stok', $retur->jumlah_retur);
            }

            $retur->delete();

            DB::commit();

            return redirect()->route('returpembelian.index')
                ->with('success', 'Retur pembelian berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus retur: ' . $e->getMessage());
        }
    }
}