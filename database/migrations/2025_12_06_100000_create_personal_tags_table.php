<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->string('color')->default('#6366f1');
            $table->string('icon')->nullable();
            $table->boolean('is_private')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'position']);
        });

        Schema::create('personal_tag_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_tag_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('body')->nullable();
            $table->foreignId('forwarded_from_message_id')->nullable();
            $table->json('forwarded_metadata')->nullable();
            $table->timestamps();

            $table->index(['personal_tag_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_tag_messages');
        Schema::dropIfExists('personal_tags');
    }
};
