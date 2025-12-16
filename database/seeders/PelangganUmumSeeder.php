<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class PelangganUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari atau buat pelanggan "Umum"
        $pelangganUmum = Pelanggan::updateOrCreate(
            ['nama' => 'Umum'], // kondisi pencarian
            [
                'no_telepon' => '-',
                'alamat'     => '-',
                'membership_id' => null, 
            ]
        );

        if ($pelangganUmum->wasRecentlyCreated) {
            $this->command->info("Pelanggan 'Umum' berhasil dibuat dengan ID: {$pelangganUmum->id}");
        } else {
            $this->command->info("Pelanggan 'Umum' sudah ada (ID: {$pelangganUmum->id})");
        }
    }
}