<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'gudangs';

    protected $fillable = [
        'nama_gudang',
        'lokasi',
    ];

    // Relationships
    public function stokGudang()
    {
        return $this->hasMany(StokGudang::class);
    }
}
