<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('promos', function (Blueprint $table) {
            $table->id('id_promo');
            $table->string('kode_promo', 50)->unique();
            $table->decimal('persen', 5, 2)->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('promos');
    }
};
