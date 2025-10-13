<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penjualans') && !Schema::hasColumn('penjualans', 'id_member_order')) {
            Schema::table('penjualans', function (Blueprint $table) {
                $table->unsignedBigInteger('id_member_order')->nullable()->after('id_promo');
                $table->index('id_member_order');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penjualans') && Schema::hasColumn('penjualans', 'id_member_order')) {
            Schema::table('penjualans', function (Blueprint $table) {
                $table->dropIndex(['id_member_order']);
                $table->dropColumn('id_member_order');
            });
        }
    }
};
