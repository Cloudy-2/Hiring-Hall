<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only alter chat_messages if it exists
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                if (! Schema::hasColumn('chat_messages', 'forwarded_from_message_id')) {
                    $table->foreignId('forwarded_from_message_id')->nullable()->after('reply_to_message_id')
                        ->constrained('chat_messages')->nullOnDelete();
                }
                if (! Schema::hasColumn('chat_messages', 'forwarded_metadata')) {
                    $table->json('forwarded_metadata')->nullable()->after('forwarded_from_message_id');
                }
            });
        }

        if (! Schema::hasTable('chat_friendships')) {
            Schema::create('chat_friendships', function (Blueprint $table) {
                $table->id();
                $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('addressee_id')->constrained('users')->cascadeOnDelete();
                $table->enum('status', ['pending', 'accepted', 'declined', 'blocked'])->default('pending');
                $table->timestamp('responded_at')->nullable();
                $table->foreignId('blocked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->unique(['requester_id', 'addressee_id']);
                $table->index('status');
            });
        }

        if (! Schema::hasTable('chat_presence_statuses')) {
            Schema::create('chat_presence_statuses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('status')->default('offline');
                $table->string('platform')->nullable();
                $table->string('custom_status')->nullable();
                $table->timestamp('last_active_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->json('device_context')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('chat_discussion_topics')) {
            Schema::create('chat_discussion_topics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('slug');
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedInteger('position')->default(0);
                $table->string('visibility')->default('public');
                $table->unsignedInteger('slow_mode_seconds')->default(0);
                $table->boolean('is_archived')->default(false);
                $table->timestamps();
                $table->softDeletes();
                $table->unique(['conversation_id', 'slug']);
                $table->index(['conversation_id', 'visibility']);
            });
        }

        if (! Schema::hasTable('chat_topic_subscriptions')) {
            Schema::create('chat_topic_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('topic_id')->constrained('chat_discussion_topics')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('notification_level')->default('all');
                $table->timestamp('muted_until')->nullable();
                $table->timestamps();
                $table->unique(['topic_id', 'user_id']);
            });
        }

        if (! Schema::hasTable('chat_roles')) {
            Schema::create('chat_roles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->nullable()->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name');
                $table->string('color')->nullable();
                $table->unsignedInteger('priority')->default(0);
                $table->boolean('is_system')->default(false);
                $table->boolean('applies_to_bots')->default(false);
                $table->json('permissions')->nullable();
                $table->timestamps();
                $table->unique(['conversation_id', 'name']);
            });
        }

        if (! Schema::hasTable('chat_role_assignments')) {
            Schema::create('chat_role_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained('chat_roles')->cascadeOnDelete();
                $table->unsignedBigInteger('assignable_id');
                $table->string('assignable_type');
                $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamps();
                $table->unique(['role_id', 'assignable_id', 'assignable_type'], 'chat_role_assignments_unique');
                $table->index(['assignable_id', 'assignable_type']);
            });
        }

        if (! Schema::hasTable('chat_bot_profiles')) {
            Schema::create('chat_bot_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->nullable()->constrained('chat_conversation')->cascadeOnDelete();
                $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('avatar_url')->nullable();
                $table->string('trigger_prefix')->default('/');
                $table->string('default_role')->nullable();
                $table->boolean('is_global')->default(false);
                $table->boolean('is_active')->default(true);
                $table->json('settings')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('chat_bot_commands')) {
            Schema::create('chat_bot_commands', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bot_id')->constrained('chat_bot_profiles')->cascadeOnDelete();
                $table->foreignId('required_role_id')->nullable()->constrained('chat_roles')->nullOnDelete();
                $table->string('command');
                $table->string('description')->nullable();
                $table->string('sample_input')->nullable();
                $table->string('execution_handler')->nullable();
                $table->unsignedInteger('cooldown_seconds')->default(0);
                $table->string('visibility')->default('public');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->unique(['bot_id', 'command']);
            });
        }

        // Only create tables that reference chat_messages if it exists
        if (Schema::hasTable('chat_messages')) {
            if (! Schema::hasTable('chat_discussion_topic_messages')) {
                Schema::create('chat_discussion_topic_messages', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('topic_id')->constrained('chat_discussion_topics')->cascadeOnDelete();
                    $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
                    $table->timestamps();
                    $table->unique(['topic_id', 'message_id']);
                });
            }

            if (! Schema::hasTable('chat_message_reports')) {
                Schema::create('chat_message_reports', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
                    $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
                    $table->foreignId('conversation_id')->nullable()->constrained('chat_conversation')->nullOnDelete();
                    $table->text('reason')->nullable();
                    $table->json('context')->nullable();
                    $table->string('status')->default('pending');
                    $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
                    $table->timestamp('resolved_at')->nullable();
                    $table->timestamps();
                    $table->index(['status', 'conversation_id']);
                });
            }

            if (! Schema::hasTable('chat_message_user_states')) {
                Schema::create('chat_message_user_states', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('message_id')->constrained('chat_messages')->cascadeOnDelete();
                    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                    $table->string('state')->default('manual_unread');
                    $table->timestamp('set_at')->nullable();
                    $table->json('metadata')->nullable();
                    $table->timestamps();
                    $table->unique(['message_id', 'user_id', 'state'], 'chat_message_user_state_unique');
                });
            }

            if (! Schema::hasTable('chat_moderation_rules')) {
                Schema::create('chat_moderation_rules', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('conversation_id')->nullable()->constrained('chat_conversation')->cascadeOnDelete();
                    $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                    $table->foreignId('bot_id')->nullable()->constrained('chat_bot_profiles')->nullOnDelete();
                    $table->string('name');
                    $table->string('match_type')->default('phrase');
                    $table->text('pattern');
                    $table->string('action')->default('block');
                    $table->unsignedInteger('action_duration_seconds')->nullable();
                    $table->unsignedInteger('escalation_threshold')->nullable();
                    $table->boolean('auto_resolve')->default(true);
                    $table->json('metadata')->nullable();
                    $table->timestamps();
                });
            }

            if (! Schema::hasTable('chat_moderation_events')) {
                Schema::create('chat_moderation_events', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('rule_id')->nullable()->constrained('chat_moderation_rules')->nullOnDelete();
                    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                    $table->foreignId('message_id')->nullable()->constrained('chat_messages')->nullOnDelete();
                    $table->foreignId('bot_id')->nullable()->constrained('chat_bot_profiles')->nullOnDelete();
                    $table->string('action_taken');
                    $table->timestamp('occurred_at')->useCurrent();
                    $table->json('payload')->nullable();
                    $table->timestamps();
                    $table->index('occurred_at');
                });
            }
        }

        if (! Schema::hasTable('chat_user_favorite_reactions')) {
            Schema::create('chat_user_favorite_reactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedTinyInteger('slot')->default(1);
                $table->string('emoji', 32);
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'slot']);
                $table->unique(['user_id', 'emoji']);
            });
        }

    }

    public function down(): void
    {
        Schema::dropIfExists('chat_user_favorite_reactions');
        Schema::dropIfExists('chat_message_user_states');
        Schema::dropIfExists('chat_message_reports');
        Schema::dropIfExists('chat_moderation_events');
        Schema::dropIfExists('chat_moderation_rules');
        Schema::dropIfExists('chat_discussion_topic_messages');
        Schema::dropIfExists('chat_bot_commands');
        Schema::dropIfExists('chat_bot_profiles');
        Schema::dropIfExists('chat_role_assignments');
        Schema::dropIfExists('chat_roles');
        Schema::dropIfExists('chat_topic_subscriptions');
        Schema::dropIfExists('chat_discussion_topics');
        Schema::dropIfExists('chat_presence_statuses');
        Schema::dropIfExists('chat_friendships');

        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                if (Schema::hasColumn('chat_messages', 'forwarded_from_message_id')) {
                    $table->dropForeign(['forwarded_from_message_id']);
                    $table->dropColumn('forwarded_from_message_id');
                }
                if (Schema::hasColumn('chat_messages', 'forwarded_metadata')) {
                    $table->dropColumn('forwarded_metadata');
                }
            });
        }
    }
};
