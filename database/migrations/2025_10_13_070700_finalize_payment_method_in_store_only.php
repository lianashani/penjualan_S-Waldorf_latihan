<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('member_orders')) {
            DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('in_store') NOT NULL DEFAULT 'in_store'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member_orders')) {
            // Revert to previous broader set to avoid breaking older code
            DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('cash','debt','in_store') NOT NULL DEFAULT 'in_store'");
        }
    }
};
