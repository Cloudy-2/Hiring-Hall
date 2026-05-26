<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_faq_conversations')) {
            Schema::create('ai_faq_conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->uuid('uuid')->unique();
                $table->string('title', 255)->nullable();
                $table->timestamps();

                $table->index(['user_id', 'updated_at']);
            });
        }

        if (! Schema::hasTable('ai_faq_messages')) {
            Schema::create('ai_faq_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ai_faq_conversation_id')->constrained('ai_faq_conversations')->cascadeOnDelete();
                $table->string('role', 32);
                $table->longText('content');
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['ai_faq_conversation_id', 'id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_faq_messages');
        Schema::dropIfExists('ai_faq_conversations');
    }
};
