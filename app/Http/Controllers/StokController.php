<?php

namespace App\Http\Controllers;

use App\Models\StokGudang;
use App\Models\Produk;
use App\Models\Gudang;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $stoks = StokGudang::with(['produk', 'gudang'])->orderBy('updated_at', 'desc')->get();
        return view('admin.stokgudang.index', compact('stoks'));
    }

    public function create()
    {
        $produks = Produk::all();
        $gudangs = Gudang::all();
        return view('admin.stokgudang.create', compact('produks', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id'   => 'required|exists:produk,id',
            'gudang_id'   => 'required|exists:gudang,id',
            'jumlah_stok' => 'required|integer|min:0',
        ]);

        // Cek apakah kombinasi produk + gudang sudah ada
        $exists = StokGudang::where('produk_id', $request->produk_id)
                            ->where('gudang_id', $request->gudang_id)
                            ->exists();

        if ($exists) {
            return back()->withErrors(['produk_id' => 'Stok untuk produk ini di gudang ini sudah ada. Gunakan edit.']);
        }

        StokGudang::create($request->all());

        return redirect()->route('stokgudang.index')
            ->with('success', 'Stok gudang berhasil ditambahkan.');
    }

    public function edit(StokGudang $stokgudang)
    {
        $produks = Produk::all();
        $gudangs = Gudang::all();
        return view('admin.stokgudang.edit', compact('stokgudang', 'produks', 'gudangs'));
    }

    public function update(Request $request, StokGudang $stokgudang)
    {
        $request->validate([
            'jumlah_stok' => 'required|integer|min:0',
        ]);

        $stokgudang->update($request->only('jumlah_stok'));

        return redirect()->route('stokgudang.index')
            ->with('success', 'Stok gudang berhasil diperbarui.');
    }

    public function destroy(StokGudang $stokgudang)
    {
        $stokgudang->delete();
        return redirect()->route('stokgudang.index')
            ->with('success', 'Stok gudang berhasil dihapus.');
    }
}