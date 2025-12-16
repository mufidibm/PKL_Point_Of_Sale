<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::firstOrCreate([]); // pastikan ada 1 row
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'footer_note1' => 'nullable|string',
            'footer_note2' => 'nullable|string',
            'thank_you_message' => 'required|string|max:255',
        ]);

        $settings = Setting::first();
        $settings->update($request->all());

        cache()->forget('settings');

        return redirect()->back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}