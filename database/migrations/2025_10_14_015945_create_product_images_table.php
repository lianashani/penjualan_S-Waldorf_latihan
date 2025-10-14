<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('id_image');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_variant')->nullable(); // Optional: specific variant image
            $table->string('gambar', 255);
            $table->string('alt_text', 255)->nullable();
            $table->integer('urutan')->default(0); // For image ordering
            $table->boolean('is_primary')->default(false); // Main product image
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            $table->foreign('id_variant')->references('id_variant')->on('product_variants')->onDelete('cascade');

            // Indexes
            $table->index(['id_produk', 'is_active']);
            $table->index(['id_variant', 'is_active']);
            $table->index(['id_produk', 'is_primary']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_images');
    }
};
