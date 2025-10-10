<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Members table
        Schema::create('members', function (Blueprint $table) {
            $table->id('id_member');
            $table->string('nama_member');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('no_hp', 20);
            $table->text('alamat')->nullable();
            $table->integer('points')->default(0);
            $table->decimal('total_spent', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });

        // Member Orders table
        Schema::create('member_orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->foreignId('id_member')->constrained('members', 'id_member')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 15, 2);
            $table->integer('points_used')->default(0);
            $table->decimal('points_discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->integer('points_earned')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Member Order Details table
        Schema::create('member_order_details', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_order')->constrained('member_orders', 'id_order')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produks', 'id_produk');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // Point Transactions table
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->foreignId('id_member')->constrained('members', 'id_member')->onDelete('cascade');
            $table->foreignId('id_order')->nullable()->constrained('member_orders', 'id_order')->onDelete('set null');
            $table->enum('type', ['earned', 'redeemed', 'expired']);
            $table->integer('points');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('member_order_details');
        Schema::dropIfExists('member_orders');
        Schema::dropIfExists('members');
    }
};
