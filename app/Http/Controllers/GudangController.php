<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index()
    {
        $gudangs = Gudang::latest()->get();
        return view('admin.gudang.index', compact('gudangs'));
    }

    public function create()
    {
        return view('admin.gudang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gudang' => 'required|string|max:255|unique:gudang,nama_gudang',
            'lokasi'      => 'required|string',
        ]);

        Gudang::create($request->only(['nama_gudang', 'lokasi']));

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function edit(Gudang $gudang)
    {
        return view('admin.gudang.edit', compact('gudang'));
    }

    public function update(Request $request, Gudang $gudang)
    {
        $request->validate([
            'nama_gudang' => 'required|string|max:255|unique:gudang,nama_gudang,' . $gudang->id,
            'lokasi'      => 'required|string',
        ]);

        $gudang->update($request->only(['nama_gudang', 'lokasi']));

        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Gudang $gudang)
    {
        $gudang->delete();
        return redirect()->route('gudang.index')
            ->with('success', 'Gudang berhasil dihapus.');
    }
}