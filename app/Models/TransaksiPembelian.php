<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    protected $table = 'transaksi_pembelians';

    protected $fillable = [
        'no_po',
        'tanggal',
        'supplier_id',
        'karyawan_id',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total_biaya' => 'decimal:2',
    ];

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class, 'transaksi_id');
    }

    public function returPembelian()
    {
        return $this->hasMany(ReturPembelian::class, 'transaksi_id');
    }

    // Auto generate PO number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->no_po)) {
                $model->no_po = 'PO-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
