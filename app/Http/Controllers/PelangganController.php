<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Membership;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with('membership')->latest()->get();
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        $memberships = Membership::all();
        return view('admin.pelanggan.create', compact('memberships'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'membership_id' => 'nullable|exists:membership,id',
        ]);

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        $memberships = Membership::all();
        return view('admin.pelanggan.edit', compact('pelanggan', 'memberships'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'alamat' => 'required|string',
            'membership_id' => 'nullable|exists:membership,id',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}