<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('member_orders')) {
            // Align ENUM sets to include values used by the app
            DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('cash','debt') NOT NULL DEFAULT 'cash'");
            DB::statement("ALTER TABLE `member_orders` MODIFY `status` ENUM('pending','paid','debt','shipped','completed','cancelled') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        // No-op: leaving enums as-is to avoid accidental data loss.
    }
};
