<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('produks', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk', 100);
            $table->foreignId('id_kategori')
                  ->constrained('kategoris')
                  ->cascadeOnDelete();
            $table->string('ukuran', 100);
            $table->string('warna', 100);
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produks');
    }
};
