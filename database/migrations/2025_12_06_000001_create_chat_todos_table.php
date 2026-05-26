<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chat_todos')) {
            Schema::create('chat_todos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                // Make conversation_id nullable without foreign key if chat_conversation doesn't exist
                if (Schema::hasTable('chat_conversation')) {
                    $table->foreignId('conversation_id')->nullable()->constrained('chat_conversation')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('conversation_id')->nullable();
                }
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('priority')->default('medium');
                $table->string('status')->default('pending');
                $table->timestamp('due_date')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index(['conversation_id', 'status']);
                $table->index('due_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_todos');
    }
};
