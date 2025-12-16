<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;
    protected $table = 'transaksi_penjualans';

    protected $fillable = [
        'no_invoice',
        'tanggal',
        'karyawan_id',
        'pelanggan_id',
        'membership_id',  // ← tambahkan
        'subtotal',
        'uang_dibayar',
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

    public function membership()
    {
        return $this->belongsTo(Membership::class, 'membership_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'transaksi_id');
    }

    public function returPenjualans()
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
