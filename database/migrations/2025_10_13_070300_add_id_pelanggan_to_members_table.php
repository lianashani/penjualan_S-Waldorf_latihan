<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('members', 'id_pelanggan')) {
            Schema::table('members', function (Blueprint $table) {
                $table->unsignedBigInteger('id_pelanggan')->nullable()->after('id_member');
                $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('members', 'id_pelanggan')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropForeign(['id_pelanggan']);
                $table->dropColumn('id_pelanggan');
            });
        }
    }
};
