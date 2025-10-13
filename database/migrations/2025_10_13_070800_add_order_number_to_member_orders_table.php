<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('member_orders') && !Schema::hasColumn('member_orders', 'order_number')) {
            Schema::table('member_orders', function (Blueprint $table) {
                $table->string('order_number', 40)->unique()->nullable()->after('id_member');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member_orders') && Schema::hasColumn('member_orders', 'order_number')) {
            Schema::table('member_orders', function (Blueprint $table) {
                $table->dropUnique(['order_number']);
                $table->dropColumn('order_number');
            });
        }
    }
};
