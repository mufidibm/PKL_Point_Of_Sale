<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('user')->latest()->get();
        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $users = User::where('role', 'karyawan')->orWhereNull('role')->get();
        return view('admin.karyawan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'user_id' => 'nullable|exists:users,id|unique:karyawan,user_id',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        $users = User::where('role', 'karyawan')
                     ->orWhere('id', $karyawan->user_id)
                     ->get();
        return view('admin.karyawan.edit', compact('karyawan', 'users'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'user_id' => 'nullable|exists:users,id|unique:karyawan,user_id,' . $karyawan->id,
        ]);

        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}