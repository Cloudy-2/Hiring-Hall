<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if chat_conversation table doesn't exist
        if (! Schema::hasTable('chat_conversation')) {
            return;
        }

        if (! Schema::hasTable('group_join_requests')) {
            Schema::create('group_join_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id');
                $table->unsignedBigInteger('user_id');
                $table->string('status')->default('pending');
                $table->text('message')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();

                $table->foreign('conversation_id')->references('id')->on('chat_conversation')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');

                $table->unique(['conversation_id', 'user_id', 'status']);
                $table->index(['conversation_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('group_join_requests');
    }
};
