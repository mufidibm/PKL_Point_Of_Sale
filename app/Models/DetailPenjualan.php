<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualans';

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Auto calculate subtotal
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->subtotal = $model->jumlah * $model->harga_satuan;
        });
    }
}
