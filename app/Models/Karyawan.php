<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';

    protected $fillable = [
        'nama',
        'jabatan',
        'no_telepon',
        'user_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaksiPenjualan()
    {
        return $this->hasMany(TransaksiPenjualan::class);
    }

    public function transaksiPembelian()
    {
        return $this->hasMany(TransaksiPembelian::class);
    }
}
