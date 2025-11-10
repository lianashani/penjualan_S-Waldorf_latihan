<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update status enum to include 'pending' for Midtrans payment flow
        DB::statement("ALTER TABLE `member_orders` MODIFY `status` ENUM('pending', 'awaiting_preparation', 'ready_for_pickup', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum values
        DB::statement("ALTER TABLE `member_orders` MODIFY `status` ENUM('awaiting_preparation', 'ready_for_pickup', 'completed', 'cancelled') NOT NULL DEFAULT 'awaiting_preparation'");
    }
};
