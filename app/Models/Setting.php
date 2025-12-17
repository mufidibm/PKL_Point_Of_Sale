<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = [
        'nama_toko',
        'logo',
        'alamat',
        'telepon',
        'email',
        'footer_note1',
        'footer_note2',
        'thank_you_message'
    ];

    public static function getSettings()
    {
        return cache()->remember('settings', 3600, function () {
            return self::first() ?? new self();
        });
    }

    // Accessor untuk mendapatkan URL logo
    public function getLogoUrlAttribute()
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::url($this->logo);
        }
        return asset('images/default-logo.png'); // Logo default jika tidak ada
    }

    // Fungsi untuk hapus logo lama
    public function deleteLogo()
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            Storage::disk('public')->delete($this->logo);
        }
    }
}
