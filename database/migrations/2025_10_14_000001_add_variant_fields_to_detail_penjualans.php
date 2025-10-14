<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detail_penjualans', function (Blueprint $table) {
            $table->string('ukuran')->nullable()->after('id_produk');
            $table->string('warna')->nullable()->after('ukuran');
        });
    }

    public function down(): void
    {
        Schema::table('detail_penjualans', function (Blueprint $table) {
            $table->dropColumn(['ukuran', 'warna']);
        });
    }
};
