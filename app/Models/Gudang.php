<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use HasFactory;
    protected $table = 'gudangs';

    protected $fillable = [
        'nama_gudang',
        'lokasi',
    ];

    // Relationships
    public function stokGudang()
    {
        return $this->hasMany(StokGudang::class, 'gudang_id');
    }
}
