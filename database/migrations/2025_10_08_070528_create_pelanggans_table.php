<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->string('nama_pelanggan', 100);
            $table->string('email')->unique();
            $table->string('password')->nullable(); // hash disimpan di sini
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('tanggal_daftar')->useCurrent();

    // relasi ke membership
    $table->unsignedBigInteger('id_membership')->nullable();
    $table->foreign('id_membership')->references('id_membership')->on('memberships')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pelanggans');
    }
};
