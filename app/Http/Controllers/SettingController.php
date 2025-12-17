<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'footer_note1' => 'nullable|string',
            'footer_note2' => 'nullable|string',
            'thank_you_message' => 'required|string|max:255',
        ]);

        $settings = Setting::first();
        $data = $request->except('logo');

        // Handle upload logo
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            $settings->deleteLogo();

            // Upload logo baru
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('logos', $filename, 'public');
            $data['logo'] = $path;
        }

        $settings->update($data);
        cache()->forget('settings');

        return redirect()->back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }

    // Method untuk hapus logo
    public function deleteLogo()
    {
        $settings = Setting::first();
        
        if ($settings->logo) {
            $settings->deleteLogo();
            $settings->update(['logo' => null]);
            cache()->forget('settings');
            
            return redirect()->back()->with('success', 'Logo berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Tidak ada logo untuk dihapus.');
    }
}