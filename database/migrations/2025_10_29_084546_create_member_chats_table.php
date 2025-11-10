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
        Schema::create('member_chats', function (Blueprint $table) {
            $table->id('id_chat');
            $table->unsignedBigInteger('id_member');
            $table->unsignedBigInteger('id_user')->nullable(); // Kasir/Admin yang balas
            $table->text('message');
            $table->enum('sender_type', ['member', 'staff'])->default('member');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->index(['id_member', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_chats');
    }
};
