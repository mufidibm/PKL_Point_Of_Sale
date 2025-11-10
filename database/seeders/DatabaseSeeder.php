<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        Karyawan::create([
            'nama' => 'Admin System',
            'jabatan' => 'Manajer',
            'no_telepon' => '081234567890',
            'user_id' => $admin->id
        ]);

        // Kasir
        $kasir = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kasir'
        ]);

        Karyawan::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Kasir',
            'no_telepon' => '081234567891',
            'user_id' => $kasir->id
        ]);

        // Gudang
        $gudang = User::create([
            'name' => 'Staff Gudang',
            'email' => 'gudang@pos.com',
            'password' => Hash::make('password'),
            'role' => 'gudang'
        ]);

        Karyawan::create([
            'nama' => 'Joko Widodo',
            'jabatan' => 'Supervisor Gudang',
            'no_telepon' => '081234567892',
            'user_id' => $gudang->id
        ]);
    }
}
