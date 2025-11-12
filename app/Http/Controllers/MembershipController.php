<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::latest()->get();
        return view('admin.membership.index', compact('memberships'));
    }

    public function create()
    {
        return view('admin.membership.create');
    }

    public function store(Request $request)
    {
        // store
        $request->validate([
            'nama_membership' => 'required|string|max:100|unique:memberships,nama_membership',
            'diskon_persen'   => 'required|numeric|min:0|max:100',
        ]);

        Membership::create([
            'nama_membership' => $request->nama_membership,
            'diskon_persen'   => $request->diskon_persen,
        ]);

        return redirect()->route('membership.index')
            ->with('success', 'Membership berhasil ditambahkan.');
    }

    public function edit(Membership $membership)
    {
        return view('admin.membership.edit', compact('membership'));
    }

    public function update(Request $request, Membership $membership)
    {
// update
$request->validate([
    'nama_membership' => 'required|string|max:100|unique:memberships,nama_membership,' . $membership->id,
    'diskon_persen'   => 'required|numeric|min:0|max:100',
]);

$membership->update([
    'nama_membership' => $request->nama_membership,
    'diskon_persen'   => $request->diskon_persen,
]);

        return redirect()->route('membership.index')
            ->with('success', 'Membership berhasil diperbarui.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();
        return redirect()->route('membership.index')
            ->with('success', 'Membership berhasil dihapus.');
    }
}

