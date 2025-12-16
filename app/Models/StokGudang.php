<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    use HasFactory;
    protected $table = 'stok_gudangs';

    protected $fillable = [
        'produk_id',
        'gudang_id',
        'jumlah_stok',
    ];

    public $timestamps = false;

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function gudang()
    {
    return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    // Auto update timestamp
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($model) {
            $model->updated_at = now();
        });
    }
}