<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualans';

    protected $fillable = [
        'no_invoice',
        'tanggal',
        'karyawan_id',
        'pelanggan_id',
        'subtotal',
        'diskon',
        'total_bayar',
        'metode_bayar',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_bayar' => 'decimal:2',
    ];

    // Relationships
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'transaksi_id');
    }

    public function returPenjualan()
    {
        return $this->hasMany(ReturPenjualan::class, 'transaksi_id');
    }

    // Auto generate invoice number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->no_invoice)) {
                $model->no_invoice = 'INV-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
