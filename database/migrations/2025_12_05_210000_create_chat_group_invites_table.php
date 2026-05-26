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

        if (! Schema::hasTable('chat_group_invites')) {
            Schema::create('chat_group_invites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
                $table->string('code')->unique();
                $table->unsignedInteger('max_uses')->nullable();
                $table->unsignedInteger('uses_count')->default(0);
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_revoked')->default(false);
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['conversation_id', 'is_revoked']);
                $table->index('expires_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_group_invites');
    }
};
