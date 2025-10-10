<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->unsignedBigInteger('id_promo')->nullable();
            $table->decimal('total_bayar', 10, 2)->default(0);
            $table->decimal('kembalian', 10, 2)->default(0);
            $table->enum('status_transaksi', ['pending','selesai','batal'])->default('pending');
            $table->datetime('tanggal_transaksi')->useCurrent();
            $table->timestamps();

            // Foreign keys with correct column references
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('set null');
            $table->foreign('id_promo')->references('id_promo')->on('promos')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('penjualans');
    }
};
