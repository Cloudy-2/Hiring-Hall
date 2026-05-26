<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create chat_conversation table if it doesn't exist
        if (! Schema::hasTable('chat_conversation')) {
            Schema::create('chat_conversation', function (Blueprint $table) {
                $table->id();
                $table->string('type')->default('private'); // private, group, channel
                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->string('avatar')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->boolean('is_archived')->default(false);
                $table->json('settings')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Create chat_messages table if it doesn't exist
        if (! Schema::hasTable('chat_messages')) {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('body')->nullable();
                $table->string('type')->default('text'); // text, image, file, system, etc.
                $table->foreignId('reply_to_message_id')->nullable()->constrained('chat_messages')->nullOnDelete();
                $table->json('metadata')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamp('edited_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['conversation_id', 'created_at']);
                $table->index('user_id');
            });
        }

        // Create chat_participants table if it doesn't exist
        if (! Schema::hasTable('chat_participants')) {
            Schema::create('chat_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('role')->default('member'); // admin, moderator, member
                $table->timestamp('joined_at')->nullable();
                $table->timestamp('last_read_at')->nullable();
                $table->boolean('is_muted')->default(false);
                $table->timestamp('muted_until')->nullable();
                $table->timestamps();

                $table->unique(['conversation_id', 'user_id']);
            });
        }

        // Create chat_conversation_participants table (alias used by some migrations)
        if (! Schema::hasTable('chat_conversation_participants')) {
            Schema::create('chat_conversation_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('role')->default('member');
                $table->timestamp('joined_at')->nullable();
                $table->timestamp('last_read_at')->nullable();
                $table->unsignedBigInteger('last_read_message_id')->nullable();
                $table->unsignedInteger('unread_count')->default(0);
                $table->timestamp('left_at')->nullable();
                $table->boolean('is_muted')->default(false);
                $table->timestamps();

                $table->unique(['conversation_id', 'user_id']);
            });
        } else {
            // Add left_at column if table exists but column doesn't
            if (! Schema::hasColumn('chat_conversation_participants', 'left_at')) {
                Schema::table('chat_conversation_participants', function (Blueprint $table) {
                    $table->timestamp('left_at')->nullable()->after('role');
                });
            }
            // Add last_read_message_id column if missing
            if (! Schema::hasColumn('chat_conversation_participants', 'last_read_message_id')) {
                Schema::table('chat_conversation_participants', function (Blueprint $table) {
                    $table->unsignedBigInteger('last_read_message_id')->nullable()->after('last_read_at');
                });
            }
            // Add unread_count column if missing
            if (! Schema::hasColumn('chat_conversation_participants', 'unread_count')) {
                Schema::table('chat_conversation_participants', function (Blueprint $table) {
                    $table->unsignedInteger('unread_count')->default(0);
                });
            }
        }

        // Create chat_attachments table if it doesn't exist
        if (! Schema::hasTable('chat_attachments')) {
            Schema::create('chat_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
                $table->string('file_path');
                $table->string('file_name');
                $table->string('original_name')->nullable();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->timestamps();
            });
        }

        // Create chat_message_pins table if it doesn't exist
        if (! Schema::hasTable('chat_message_pins')) {
            Schema::create('chat_message_pins', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
                $table->foreignId('pinned_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->unique(['conversation_id', 'message_id']);
            });
        }

        // Create chat_message_reactions table if it doesn't exist
        if (! Schema::hasTable('chat_message_reactions')) {
            Schema::create('chat_message_reactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('emoji', 50);
                $table->timestamps();
                $table->unique(['message_id', 'user_id', 'emoji']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_attachments');
        Schema::dropIfExists('chat_conversation_participants');
        Schema::dropIfExists('chat_participants');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversation');
    }
};
