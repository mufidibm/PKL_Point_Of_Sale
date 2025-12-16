<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;
    protected $table = 'memberships';

    protected $fillable = [
        'nama_membership',
        'diskon_persen',
    ];

    protected $casts = [
        'diskon_persen' => 'decimal:2',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class);
    }
}
