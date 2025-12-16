<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_toko',
        'alamat',
        'telepon',
        'email',
        'footer_note1',
        'footer_note2',
        'thank_you_message',
    ];

    // Karena hanya ada 1 baris data, kita buat helper
    public static function getSettings()
    {
        return cache()->remember('settings', 3600, function () {
            return self::first() ?? new self();
        });
    }
}