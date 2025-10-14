<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->id('id_rating');
            $table->unsignedBigInteger('id_produk');
            $table->unsignedBigInteger('id_user')->nullable(); // Optional: for logged in users
            $table->string('nama_pengguna', 100)->nullable(); // For guest users
            $table->string('email_pengguna', 100)->nullable(); // For guest users
            $table->integer('rating')->unsigned(); // 1-5 stars
            $table->text('komentar')->nullable();
            $table->boolean('is_approved')->default(false); // Admin approval
            $table->boolean('is_verified_purchase')->default(false); // Verified purchase
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');

            // Note: Check constraint will be handled in model validation

            // Indexes
            $table->index(['id_produk', 'is_approved']);
            $table->index(['id_user', 'id_produk']);
            $table->index('rating');
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_ratings');
    }
};
