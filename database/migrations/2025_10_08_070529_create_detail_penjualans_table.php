<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_penjualan')->constrained('penjualans')->cascadeOnDelete();
            $table->foreignId('id_produk')->constrained('produks')->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('detail_penjualans');
    }
};
