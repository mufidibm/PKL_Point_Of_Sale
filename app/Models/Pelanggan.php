<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'no_telepon',
        'alamat',
        'membership_id',
    ];

    // Relationships
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualan::class);
    }
}