<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model
{
    use HasFactory;
    protected $table = 'retur_pembelians';

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'tanggal_retur',
        'jumlah_retur',
        'nilai_retur',
        'alasan',
    ];

    protected $casts = [
        'tanggal_retur' => 'date',
        'nilai_retur' => 'decimal:2',
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
}
