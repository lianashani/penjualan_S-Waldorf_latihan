<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penjualans', function (Blueprint $table) {
                $table->id('id_penjualan');
                $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
                $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggans')->onDelete('set null');
                $table->foreignId('id_promo')->nullable()->constrained('promos')->onDelete('set null');
                $table->decimal('total_bayar', 10, 2)->default(0);
                $table->decimal('kembalian', 10, 2)->default(0);
                $table->enum('status_transaksi', ['pending','selesai','batal'])->default('pending');
                $table->datetime('tanggal_transaksi')->useCurrent();
                $table->timestamps();
                });
    }

    public function down(): void {
        Schema::dropIfExists('penjualans');
    }
};
