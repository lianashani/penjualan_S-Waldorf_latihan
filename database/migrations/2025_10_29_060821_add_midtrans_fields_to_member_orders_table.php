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
        Schema::table('member_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('member_orders', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('member_orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('member_orders', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('member_orders', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending')->after('payment_type');
            }
            if (!Schema::hasColumn('member_orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_orders', function (Blueprint $table) {
            if (Schema::hasColumn('member_orders', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
            if (Schema::hasColumn('member_orders', 'transaction_id')) {
                $table->dropColumn('transaction_id');
            }
            if (Schema::hasColumn('member_orders', 'payment_type')) {
                $table->dropColumn('payment_type');
            }
            if (Schema::hasColumn('member_orders', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('member_orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });
    }
};
