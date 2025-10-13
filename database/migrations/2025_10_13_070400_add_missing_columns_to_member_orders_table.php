<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('member_orders')) {
            Schema::table('member_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('member_orders', 'payment_method')) {
                    $table->enum('payment_method', ['cash', 'debt'])->default('cash')->after('id_member');
                }
                if (!Schema::hasColumn('member_orders', 'status')) {
                    $table->enum('status', ['pending', 'paid', 'debt', 'shipped', 'completed', 'cancelled'])->default('pending')->after('payment_method');
                }
                if (!Schema::hasColumn('member_orders', 'total')) {
                    $table->decimal('total', 10, 2)->default(0)->after('status');
                }
                if (!Schema::hasColumn('member_orders', 'debt_due_at')) {
                    $table->timestamp('debt_due_at')->nullable()->after('total');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member_orders')) {
            Schema::table('member_orders', function (Blueprint $table) {
                if (Schema::hasColumn('member_orders', 'debt_due_at')) {
                    $table->dropColumn('debt_due_at');
                }
                if (Schema::hasColumn('member_orders', 'total')) {
                    $table->dropColumn('total');
                }
                if (Schema::hasColumn('member_orders', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('member_orders', 'payment_method')) {
                    $table->dropColumn('payment_method');
                }
            });
        }
    }
};
