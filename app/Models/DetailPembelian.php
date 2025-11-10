<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelians';

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_beli',
        'subtotal',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function transaksi()
    {
        return $this->belongsTo(TransaksiPembelian::class, 'transaksi_id');
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
            $model->subtotal = $model->jumlah * $model->harga_beli;
        });
    }
}
