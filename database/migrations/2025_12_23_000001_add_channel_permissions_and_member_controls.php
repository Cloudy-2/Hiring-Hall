<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add channel-level permissions to discussion topics (if table exists)
        if (Schema::hasTable('chat_discussion_topics')) {
            Schema::table('chat_discussion_topics', function (Blueprint $table) {
                if (! Schema::hasColumn('chat_discussion_topics', 'is_readonly')) {
                    $table->boolean('is_readonly')->default(false)->after('is_archived')
                        ->comment('Only moderators/admins can post');
                }
                if (! Schema::hasColumn('chat_discussion_topics', 'allowed_roles')) {
                    $table->json('allowed_roles')->nullable()->after('is_readonly')
                        ->comment('JSON array of roles that can view this channel');
                }
            });
        }

        // Add member mute/permissions to conversation participants (if table exists)
        if (Schema::hasTable('chat_conversation_participants')) {
            Schema::table('chat_conversation_participants', function (Blueprint $table) {
                if (! Schema::hasColumn('chat_conversation_participants', 'muted_until')) {
                    $table->timestamp('muted_until')->nullable()->after('is_muted')
                        ->comment('Timestamp until which member is muted');
                }
                if (! Schema::hasColumn('chat_conversation_participants', 'can_send_messages')) {
                    $table->boolean('can_send_messages')->default(true)->after('muted_until');
                }
                if (! Schema::hasColumn('chat_conversation_participants', 'can_view_channels')) {
                    $table->json('can_view_channels')->nullable()->after('can_send_messages')
                        ->comment('JSON array of channel IDs this member can view, null = all');
                }
            });
        }

        // Create waiting list table for candidates waiting to be assigned (if chat_conversation exists)
        if (Schema::hasTable('chat_conversation') && ! Schema::hasTable('chat_waiting_list')) {
            Schema::create('chat_waiting_list', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('conversation_id')->nullable()->constrained('chat_conversation')->nullOnDelete();
                $table->string('status')->default('waiting');
                $table->text('notes')->nullable();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'status'], 'unique_waiting_user');
                $table->index(['status', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_waiting_list');

        if (Schema::hasTable('chat_conversation_participants')) {
            Schema::table('chat_conversation_participants', function (Blueprint $table) {
                $columns = ['muted_until', 'can_send_messages', 'can_view_channels'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('chat_conversation_participants', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('chat_discussion_topics')) {
            Schema::table('chat_discussion_topics', function (Blueprint $table) {
                $columns = ['is_readonly', 'allowed_roles'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('chat_discussion_topics', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
