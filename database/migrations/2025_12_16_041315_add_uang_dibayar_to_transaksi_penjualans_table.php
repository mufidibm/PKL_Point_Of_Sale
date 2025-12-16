<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->decimal('uang_dibayar', 15, 2)->default(0)->after('total_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualans', function (Blueprint $table) {
            $table->dropColumn('uang_dibayar');
        });
    }
};