<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('member_orders')) {
            Schema::create('member_orders', function (Blueprint $table) {
                $table->id('id_order');
                $table->unsignedBigInteger('id_member');
                $table->enum('payment_method', ['cash', 'debt'])->default('cash');
                $table->enum('status', ['pending', 'paid', 'debt', 'shipped', 'completed', 'cancelled'])->default('pending');
                $table->decimal('total', 10, 2)->default(0);
                $table->timestamp('debt_due_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('member_orders');
    }
};
