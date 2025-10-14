<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id('id_variant');
            $table->unsignedBigInteger('id_produk');
            $table->string('ukuran', 50);
            $table->string('warna', 50);
            $table->string('kode_warna', 7)->nullable(); // Hex color code
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2)->nullable(); // Optional variant price
            $table->string('sku', 100)->nullable(); // Stock Keeping Unit
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');

            // Unique constraint for product + size + color combination
            $table->unique(['id_produk', 'ukuran', 'warna'], 'unique_product_variant');

            // Indexes for performance
            $table->index(['id_produk', 'is_active']);
            $table->index('sku');
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_variants');
    }
};
