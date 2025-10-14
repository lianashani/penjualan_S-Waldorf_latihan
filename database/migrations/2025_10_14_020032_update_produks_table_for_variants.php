<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('produks', function (Blueprint $table) {
            // Add new columns for variant support
            $table->decimal('harga_min', 10, 2)->nullable()->after('harga'); // Minimum price from variants
            $table->decimal('harga_max', 10, 2)->nullable()->after('harga_min'); // Maximum price from variants
            $table->integer('total_stok')->default(0)->after('stok'); // Total stock from all variants
            $table->decimal('rating_average', 3, 2)->default(0)->after('deskripsi'); // Average rating
            $table->integer('rating_count')->default(0)->after('rating_average'); // Total rating count
            $table->boolean('has_variants')->default(false)->after('rating_count'); // Flag for variant products
            $table->string('slug', 255)->nullable()->after('nama_produk'); // SEO friendly URL
            $table->boolean('is_featured')->default(false)->after('has_variants'); // Featured product
            $table->boolean('is_active')->default(true)->after('is_featured'); // Product status

            // Make old columns nullable since variants will handle them
            $table->string('ukuran', 100)->nullable()->change();
            $table->string('warna', 100)->nullable()->change();
            $table->integer('stok')->nullable()->change();

            // Add indexes
            $table->index(['is_active', 'is_featured']);
            $table->index('slug');
            $table->index('rating_average');
        });
    }

    public function down(): void {
        Schema::table('produks', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn([
                'harga_min', 'harga_max', 'total_stok', 'rating_average',
                'rating_count', 'has_variants', 'slug', 'is_featured', 'is_active'
            ]);

            // Restore old columns to required
            $table->string('ukuran', 100)->nullable(false)->change();
            $table->string('warna', 100)->nullable(false)->change();
            $table->integer('stok')->nullable(false)->change();
        });
    }
};
