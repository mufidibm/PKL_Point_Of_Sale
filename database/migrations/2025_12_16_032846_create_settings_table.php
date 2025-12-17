<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('logo')->nullable(); // Tambahan untuk logo
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('footer_note1')->nullable();
            $table->text('footer_note2')->nullable();
            $table->string('thank_you_message')->default('*** TERIMA KASIH ***');
            $table->timestamps();
        });

        \DB::table('settings')->insert([
            'nama_toko' => 'NAMA TOKO ANDA',
            'logo' => null,
            'alamat' => 'Jl. Alamat Toko No. 123',
            'telepon' => '(021) 1234-5678',
            'email' => 'toko@email.com',
            'footer_note1' => 'Barang yang sudah dibeli tidak dapat dikembalikan',
            'footer_note2' => 'Selamat Berbelanja Kembali',
            'thank_you_message' => '*** TERIMA KASIH ***',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
