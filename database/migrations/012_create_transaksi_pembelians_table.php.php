<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('no_po')->unique();
            $table->date('tanggal');
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('karyawan_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_biaya', 14, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembelians');
    }
};