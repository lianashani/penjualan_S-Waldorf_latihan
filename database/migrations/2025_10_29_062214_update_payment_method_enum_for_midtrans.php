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
        // Update payment_method enum to include 'midtrans'
        DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('cash', 'debt', 'in_store', 'midtrans') NOT NULL DEFAULT 'in_store'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum values
        DB::statement("ALTER TABLE `member_orders` MODIFY `payment_method` ENUM('cash', 'debt', 'in_store') NOT NULL DEFAULT 'in_store'");
    }
};
