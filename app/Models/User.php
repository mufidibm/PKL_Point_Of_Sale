<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    public function isGudang()
    {
        return $this->role === 'gudang';
    }

    public function getFotoProfilUrlAttribute()
    {
        return $this->foto_profil
            ? asset('storage/foto-profil/' . $this->foto_profil)
            : asset('adminlte/dist/img/user2-160x160.jpg');
    }
}
