<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Matikan pemeriksaan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate semua tabel (urutan penting: anak dulu, induk kemudian)
        \App\Models\DetailPenjualan::truncate();
        \App\Models\DetailPembelian::truncate();
        \App\Models\ReturPenjualan::truncate();
        \App\Models\ReturPembelian::truncate();
        \App\Models\StokGudang::truncate();
        \App\Models\TransaksiPenjualan::truncate();
        \App\Models\TransaksiPembelian::truncate();
        \App\Models\Produk::truncate();
        \App\Models\Pelanggan::truncate();
        \App\Models\Supplier::truncate();
        \App\Models\Karyawan::truncate();
        \App\Models\Membership::truncate();
        \App\Models\Kategori::truncate();
        \App\Models\Gudang::truncate();
        \App\Models\User::truncate();

        // Nyalakan kembali foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create('id_ID');

        // 1. Membership
        $memberships = [];
        for ($i = 0; $i < 3; $i++) {
            $memberships[] = \App\Models\Membership::create([
                'nama_membership' => $faker->randomElement(['Reguler', 'Silver', 'Gold']) . ' Membership',
                'diskon_persen' => $faker->randomElement([0.00, 5.00, 10.00, 15.00]),
            ]);
        }

        // 2. Kategori
        $kategoris = [];
        $namaKategori = ['Makanan', 'Minuman', 'Snack', 'Rokok', 'Sabun & Deterjen', 'Alat Tulis', 'Elektronik'];
        foreach ($namaKategori as $nama) {
            $kategoris[] = \App\Models\Kategori::create([
                'nama_kategori' => $nama,
            ]);
        }

        // 3. Gudang
        $gudangs = [];
        for ($i = 1; $i <= 2; $i++) {
            $gudangs[] = \App\Models\Gudang::create([
                'nama_gudang' => "Gudang " . $faker->city,
                'lokasi' => $faker->address,
            ]);
        }

        // 4. User & Karyawan (admin, kasir, gudang, owner)
        User::create([
            'name' => 'q',
            'email' => 'q@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'w',
            'email' => 'w@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'kasir',
        ]);

        User::create([
            'name' => 'a',
            'email' => 'a@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'gudang',
        ]);

        User::create([
            'name' => 's',
            'email' => 's@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'owner',
        ]);

        $roles = ['admin', 'kasir', 'gudang', 'owner'];
        $karyawans = [];
        foreach ($roles as $index => $role) {
            $user = \App\Models\User::create([
                'name' => $faker->name,
                'email' => strtolower($role) . "@toko.com",
                'password' => bcrypt('12345678'),
                'role' => $role,
            ]);

            $karyawans[] = \App\Models\Karyawan::create([
                'nama' => $user->name,
                'jabatan' => ucfirst($role),
                'no_telepon' => $faker->phoneNumber,
                'user_id' => $user->id,
            ]);
        }

        // Tambah 10 karyawan biasa (kasir & gudang)
        for ($i = 0; $i < 10; $i++) {
            $role = $faker->randomElement(['kasir', 'gudang']);
            $user = \App\Models\User::create([
                'name' => $faker->name,
                'email' => 'karyawan' . ($i + 5) . "@toko.com",
                'password' => bcrypt('password123'),
                'role' => $role,
            ]);

            $karyawans[] = \App\Models\Karyawan::create([
                'nama' => $user->name,
                'jabatan' => $role === 'kasir' ? 'Kasir' : 'Staff Gudang',
                'no_telepon' => $faker->phoneNumber,
                'user_id' => $user->id,
            ]);
        }

        // 5. Supplier
        $suppliers = [];
        for ($i = 0; $i < 5; $i++) {
            $suppliers[] = \App\Models\Supplier::create([
                'nama' => $faker->company . " Supplier",
                'no_telepon' => $faker->phoneNumber,
                'alamat' => $faker->address,
            ]);
        }

        // 6. Pelanggan
        $pelanggans = [];
        for ($i = 0; $i < 15; $i++) {
            $pelanggans[] = \App\Models\Pelanggan::create([
                'nama' => $faker->name,
                'no_telepon' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'membership_id' => $faker->optional(0.7)->randomElement($memberships)->id ?? null,
            ]);
        }

        // 7. Produk
        $produks = [];
        $namaProduk = [
            'Beras 5kg',
            'Minyak Goreng 1L',
            'Gula Pasir 1kg',
            'Kopi Kapal Api',
            'Teh Kotak',
            'Sabun Lifebuoy',
            'Pepsodent 190g',
            'Rinso 800g',
            'Aqua 600ml',
            'Indomie Goreng',
            'Rokok Sampoerna',
            'Pulpen Snowman',
            'Buku Tulis Sidu',
            'Laptop Charger',
            'Mouse Wireless'
        ];

        foreach ($namaProduk as $nama) {
            $hargaBeli = $faker->numberBetween(5000, 100000);
            $markup = $faker->randomFloat(2, 0.2, 0.6);
            $hargaJual = $hargaBeli * (1 + $markup);

            $produk = \App\Models\Produk::create([
                'nama_produk' => $nama,
                'barcode' => $faker->unique()->ean13,
                'harga_beli' => $hargaBeli,
                'harga_jual' => round($hargaJual, -2),
                'satuan' => $faker->randomElement(['pcs', 'kg', 'liter', 'pack']),
                'kategori_id' => $faker->randomElement($kategoris)->id,
            ]);

            $produks[] = $produk;

            // Stok di gudang
            foreach ($gudangs as $gudang) {
                \App\Models\StokGudang::create([
                    'produk_id' => $produk->id,
                    'gudang_id' => $gudang->id,
                    'jumlah_stok' => $faker->numberBetween(10, 200),
                ]);
            }
        }

        // 8. Transaksi Pembelian (PO)
        for ($i = 0; $i < 8; $i++) {
            $transaksi = \App\Models\TransaksiPembelian::create([
                'no_po' => 'PO' . $faker->unique()->numberBetween(1000, 9999),
                'tanggal' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                'supplier_id' => $faker->randomElement($suppliers)->id,
                'karyawan_id' => $faker->randomElement($karyawans)->id,
                'total_biaya' => 0,
            ]);

            $total = 0;
            $items = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $items; $j++) {
                $produk = $faker->randomElement($produks);
                $jumlah = $faker->numberBetween(5, 50);
                $hargaBeli = $produk->harga_beli;
                $subtotal = $hargaBeli * $jumlah;

                \App\Models\DetailPembelian::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;

                // Update stok gudang utama (gudang pertama)
                $stok = \App\Models\StokGudang::where('produk_id', $produk->id)
                    ->where('gudang_id', $gudangs[0]->id)->first();
                if ($stok) {
                    $stok->jumlah_stok += $jumlah;
                    $stok->save();
                }
            }

            $transaksi->total_biaya = $total;
            $transaksi->save();
        }

        // 9. Transaksi Penjualan
        $metodeBayar = ['tunai', 'transfer', 'debit', 'qr'];
        for ($i = 0; $i < 20; $i++) {
            $subtotal = 0;
            $diskon = 0;

            $pelanggan = $faker->randomElement($pelanggans);

            $transaksi = \App\Models\TransaksiPenjualan::create([
                'no_invoice' => 'INV' . $faker->unique()->numberBetween(10000, 99999),
                'tanggal' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'karyawan_id' => $faker->randomElement(array_filter($karyawans, fn($k) => in_array($k->jabatan, ['Kasir', 'admin'])))->id,
                'pelanggan_id' => $pelanggan->id, // ← UBAH: pakai $pelanggan (bukan $pelanggans)
                'membership_id' => $pelanggan->membership_id, // ← UBAH: pakai $pelanggan
                'subtotal' => 0,
                'diskon' => 0,
                'total_bayar' => 0,
                'metode_bayar' => $faker->randomElement($metodeBayar),
            ]);

            $items = $faker->numberBetween(1, 6);
            for ($j = 0; $j < $items; $j++) {
                $produk = $faker->randomElement($produks);
                $stok = \App\Models\StokGudang::where('produk_id', $produk->id)
                    ->where('gudang_id', $gudangs[0]->id)->first();

                if (!$stok || $stok->jumlah_stok < 1) continue;

                $jumlah = $faker->numberBetween(1, min(5, $stok->jumlah_stok));
                $hargaJual = $produk->harga_jual;
                $subtotalItem = $hargaJual * $jumlah;

                \App\Models\DetailPenjualan::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaJual,
                    'subtotal' => $subtotalItem,
                ]);

                $subtotal += $subtotalItem;
                $stok->jumlah_stok -= $jumlah;
                $stok->save();
            }

            // Hitung diskon dari membership
            if ($pelanggan->membership) {
                $diskon = $subtotal * ($pelanggan->membership->diskon_persen / 100);
            }

            $totalBayar = $subtotal - $diskon;

            $transaksi->update([
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total_bayar' => $totalBayar,
            ]);
        }

        // 10. Retur Pembelian & Penjualan (masing-masing 3)
        $transaksiPembelians = \App\Models\TransaksiPembelian::all();
        foreach ($transaksiPembelians->random(3) as $transaksi) {
            $detail = $transaksi->detailPembelians()->first();
            if ($detail) {
                \App\Models\ReturPembelian::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $detail->produk_id,
                    'tanggal_retur' => $faker->dateTimeBetween($transaksi->tanggal, 'now')->format('Y-m-d'),
                    'jumlah_retur' => $faker->numberBetween(1, $detail->jumlah),
                    'nilai_retur' => $detail->harga_beli * $faker->numberBetween(1, $detail->jumlah),
                    'alasan' => $faker->randomElement(['Rusak', 'Kadaluarsa', 'Salah Kirim']),
                ]);
            }
        }

        $transaksiPenjualans = \App\Models\TransaksiPenjualan::all();
        foreach ($transaksiPenjualans->random(3) as $transaksi) {
            $detail = $transaksi->detailPenjualans()->first();
            if ($detail) {
                \App\Models\ReturPenjualan::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $detail->produk_id,
                    'tanggal_retur' => $faker->dateTimeBetween($transaksi->tanggal, 'now')->format('Y-m-d'),
                    'jumlah_retur' => $faker->numberBetween(1, $detail->jumlah),
                    'nilai_retur' => $detail->harga_satuan * $faker->numberBetween(1, $detail->jumlah),
                    'alasan' => $faker->randomElement(['Rusak', 'Tidak Sesuai', 'Kadaluwarsa']),
                ]);
            }
        }

        $this->command->info('Semua data dummy berhasil dimasukkan!');
    }
}
