<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stok_gudangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gudang_id')->constrained()->cascadeOnDelete();
            $table->integer('jumlah_stok')->default(0);
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
            $table->unique(['produk_id', 'gudang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_gudangs');
    }
};