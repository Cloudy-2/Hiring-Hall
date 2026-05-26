<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_escalations')) {
            Schema::create('ai_escalations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('agent', 64)->nullable();
                $table->string('name', 191)->nullable();
                $table->string('email', 191);
                $table->text('description');
                $table->json('conversation_json')->nullable();
                $table->string('status', 32)->default('open');
                $table->timestamps();

                $table->index(['status', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_escalations');
    }
};
