<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_faq_feedbacks')) {
            Schema::create('ai_faq_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->uuid('conversation_uuid')->nullable();
                $table->unsignedTinyInteger('rating_stars')->nullable();
                $table->string('sentiment', 16)->nullable();
                $table->string('context', 32)->default('idle_timeout');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->index('conversation_uuid');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_faq_feedbacks');
    }
};
