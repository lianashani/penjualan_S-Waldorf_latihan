<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id('id_membership');
            $table->string('nama_membership', 100);
            $table->decimal('minimal_transaksi', 10, 2)->default(0);
            $table->decimal('diskon_member', 5, 2)->default(0);
            $table->text('benefit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('memberships');
    }
};
