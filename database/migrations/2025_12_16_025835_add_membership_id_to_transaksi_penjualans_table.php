<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->foreignId('membership_id')
                  ->nullable()
                  ->after('pelanggan_id')
                  ->constrained('memberships')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->dropForeign(['membership_id']);
            $table->dropColumn('membership_id');
        });
    }
};