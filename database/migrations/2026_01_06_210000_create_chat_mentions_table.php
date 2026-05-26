<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chat_mentions')) {
            return;
        }

        Schema::create('chat_mentions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('message_id');
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('mentioned_by');
            $table->boolean('is_everyone')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'conversation_id']);
            $table->index('message_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mentioned_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('conversation_id')->references('id')->on('chat_conversation')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_mentions');
    }
};
