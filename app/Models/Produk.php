<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'barcode',
        'harga_beli',
        'harga_jual',
        'satuan',
        'kategori_id',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function stokGudang()
    {
        return $this->hasMany(StokGudang::class);
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }

    public function getTotalStokAttribute()
    {
        return $this->stokGudang->sum('jumlah_stok');
    }

    // 🔥 Auto-generate barcode kalau kosong
    protected static function booted()
    {
        static::creating(function ($produk) {
            if (empty($produk->barcode)) {
                // contoh format: PRD-20251110-XXXX
                $produk->barcode = 'PRD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
            }
        });
    }
}
