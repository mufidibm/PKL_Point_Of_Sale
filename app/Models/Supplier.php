<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';

    protected $fillable = [
        'nama',
        'no_telepon',
        'alamat',
    ];

    // Relationships
    public function transaksiPembelian()
    {
        return $this->hasMany(TransaksiPembelian::class);
    }
}
