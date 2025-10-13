<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('member_orders')) {
            // Add 'in_store' payment method and new status set for in-store pickup flow
            DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('cash','debt','in_store') NOT NULL DEFAULT 'in_store'");
            DB::statement("ALTER TABLE `member_orders` MODIFY `status` ENUM('awaiting_preparation','ready_for_pickup','completed','cancelled') NOT NULL DEFAULT 'awaiting_preparation'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member_orders')) {
            // Revert to previous broader set (kept compatible with earlier code)
            DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('cash','debt') NOT NULL DEFAULT 'cash'");
            DB::statement("ALTER TABLE `member_orders` MODIFY `status` ENUM('pending','paid','debt','shipped','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }
};
