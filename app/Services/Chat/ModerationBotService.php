<?php

namespace App\Services\Chat;

use App\Events\MessagesCleared;
use App\Events\UserKicked;
use App\Events\UserMuted;
use App\Events\UserUnmuted;
use App\Models\Chats\Conversation;
use App\Models\Chats\ConversationParticipant;
use App\Models\Chats\DiscussionTopic;
use App\Models\Chats\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ModerationBotService
{
    const BOT_NAME = 'HillBot';

    const RULES_CHANNEL_SLUG = 'rules';

    /**
     * Create the default #rules channel for a group
     */
    public static function createRulesChannel(Conversation $conversation): DiscussionTopic
    {
        $existing = DiscussionTopic::where('conversation_id', $conversation->id)
            ->where('slug', self::RULES_CHANNEL_SLUG)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Get a valid user ID (owner or first admin)
        $creatorId = null;

        // First try to find a valid participant
        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->whereHas('user')
            ->first();

        if ($participant) {
            $creatorId = $participant->user_id;
        }

        return DiscussionTopic::create([
            'conversation_id' => $conversation->id,
            'created_by' => $creatorId,
            'slug' => self::RULES_CHANNEL_SLUG,
            'name' => 'rules',
            'description' => 'Group rules and bot configuration. Only admins can post here.',
            'position' => 0,
            'visibility' => 'public',
            'is_readonly' => true,
            'is_archived' => false,
        ]);
    }

    /**
     * Create moderator-only channels for a group
     */
    public static function createModeratorChannels(Conversation $conversation): void
    {
        $modChannels = [
            ['slug' => 'mod-chat', 'name' => 'chat', 'description' => 'Private chat for moderators'],
            ['slug' => 'mod-bot-history', 'name' => 'bot-history', 'description' => 'Bot command history and logs'],
            ['slug' => 'mod-task', 'name' => 'task', 'description' => 'Moderator tasks and notes'],
        ];

        $creatorId = ConversationParticipant::where('conversation_id', $conversation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->whereHas('user')
            ->value('user_id');

        $maxPosition = DiscussionTopic::where('conversation_id', $conversation->id)->max('position') ?? 0;

        foreach ($modChannels as $i => $channel) {
            $existing = DiscussionTopic::where('conversation_id', $conversation->id)
                ->where('slug', $channel['slug'])
                ->first();

            if (! $existing) {
                DiscussionTopic::create([
                    'conversation_id' => $conversation->id,
                    'created_by' => $creatorId,
                    'slug' => $channel['slug'],
                    'name' => $channel['name'],
                    'description' => $channel['description'],
                    'position' => $maxPosition + $i + 100,
                    'visibility' => 'moderator',
                    'is_readonly' => false,
                    'is_archived' => false,
                ]);
            }
        }
    }

    /**
     * Check if user is admin/owner of conversation or system moderator
     */
    public static function isAdmin(Conversation $conversation, int $userId): bool
    {
        // Check if user is a system-level moderator, admin, or super_admin
        $user = User::find($userId);
        if ($user && in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            return true;
        }

        // Check conversation-level role
        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->first();

        return $participant && in_array($participant->role, ['owner', 'admin', 'moderator']);
    }

    /**
     * Process a bot command from any channel
     * Commands: /help, /clear-all, /clear, /mute, /unmute, /kick, /ban, /addrule, /removerule, /listrules, /setaction, /enable, /disable, /stats
     */
    public static function processCommand(Conversation $conversation, int $userId, string $message, ?int $topicId = null): ?array
    {
        if (! self::isAdmin($conversation, $userId)) {
            return ['error' => 'Only admins can use bot commands.'];
        }

        $message = trim($message);
        if (! str_starts_with($message, '/')) {
            return null;
        }

        $parts = explode(' ', $message, 2);
        $command = strtolower($parts[0]);
        $args = $parts[1] ?? '';

        $settings = $conversation->settings ?? [];
        $customRules = $settings['custom_rules'] ?? [];

        switch ($command) {
            case '/help':
                return self::helpResponse();

            case '/clear-all':
                return self::clearAllMessages($conversation, $topicId);

            case '/clear':
                $count = (int) $args ?: 10;

                return self::clearMessages($conversation, $topicId, min($count, 100));

            case '/mute':
                return self::muteUser($conversation, $args, $userId);

            case '/unmute':
                return self::unmuteUser($conversation, $args);

            case '/kick':
                return self::kickUser($conversation, $args, $userId);

            case '/warn':
                return self::warnUser($conversation, $args, $userId);

            case '/stats':
                return self::getStats($conversation, $topicId);

            case '/slowmode':
                return self::setSlowmode($conversation, $args);

            case '/lock':
                return self::lockChannel($conversation, $topicId);

            case '/unlock':
                return self::unlockChannel($conversation, $topicId);

            case '/addrule':
                if (empty($args)) {
                    return ['error' => 'Usage: /addrule <word or phrase to block>'];
                }
                $customRules[] = [
                    'pattern' => $args,
                    'type' => 'block',
                    'added_by' => $userId,
                    'added_at' => now()->toIso8601String(),
                ];
                $settings['custom_rules'] = $customRules;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => "Added rule: Block messages containing \"$args\""];

            case '/removerule':
                if (empty($args)) {
                    return ['error' => 'Usage: /removerule <word or phrase>'];
                }
                $found = false;
                $customRules = array_filter($customRules, function ($rule) use ($args, &$found) {
                    if (strtolower($rule['pattern']) === strtolower($args)) {
                        $found = true;

                        return false;
                    }

                    return true;
                });
                if (! $found) {
                    return ['error' => "Rule not found: \"$args\""];
                }
                $settings['custom_rules'] = array_values($customRules);
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => "Removed rule: \"$args\""];

            case '/listrules':
                return self::listRulesResponse($settings);

            case '/setaction':
                $validActions = ['warn', 'delete', 'mute-5', 'mute-30', 'mute-60'];
                if (! in_array($args, $validActions)) {
                    return ['error' => 'Usage: /setaction <warn|delete|mute-5|mute-30|mute-60>'];
                }
                $settings['violation_action'] = $args;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => "Violation action set to: $args"];

            case '/enable':
                $settings['bot_enabled'] = true;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => 'Moderation bot enabled.'];

            case '/disable':
                $settings['bot_enabled'] = false;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => 'Moderation bot disabled.'];

            case '/profanity':
                $enabled = strtolower($args) === 'on';
                $rules = $settings['rules'] ?? [];
                $rules['profanity'] = $enabled;
                $settings['rules'] = $rules;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => ($enabled ? '[ON]' : '[OFF]').' Profanity filter '.($enabled ? 'enabled' : 'disabled')];

            case '/spam':
                $enabled = strtolower($args) === 'on';
                $rules = $settings['rules'] ?? [];
                $rules['spam'] = $enabled;
                $settings['rules'] = $rules;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => ($enabled ? '[ON]' : '[OFF]').' Spam detection '.($enabled ? 'enabled' : 'disabled')];

            case '/links':
                $enabled = strtolower($args) === 'on';
                $rules = $settings['rules'] ?? [];
                $rules['links'] = $enabled;
                $settings['rules'] = $rules;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => ($enabled ? '[ON]' : '[OFF]').' Link blocking '.($enabled ? 'enabled' : 'disabled')];

            case '/caps':
                $enabled = strtolower($args) === 'on';
                $rules = $settings['rules'] ?? [];
                $rules['caps'] = $enabled;
                $settings['rules'] = $rules;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => ($enabled ? '[ON]' : '[OFF]').' Caps limit '.($enabled ? 'enabled' : 'disabled')];

            case '/forcecaps':
                $enabled = strtolower($args) === 'on';
                $rules = $settings['rules'] ?? [];
                $rules['forcecaps'] = $enabled;
                $settings['rules'] = $rules;
                $conversation->settings = $settings;
                $conversation->save();

                return ['success' => ($enabled ? '[ON]' : '[OFF]').' Force uppercase '.($enabled ? 'enabled' : 'disabled')];

            case '/status':
                return self::statusResponse($settings);

            default:
                return ['error' => "Unknown command: $command. Type /help for available commands."];
        }
    }

    public static function logCommandToHistory(Conversation $conversation, int $userId, string $command, string $result, ?string $channelName = null): void
    {
        $botHistoryTopic = DiscussionTopic::where('conversation_id', $conversation->id)
            ->where('slug', 'mod-bot-history')
            ->first();

        if (! $botHistoryTopic) {
            return;
        }

        $user = User::find($userId);
        $userName = $user?->name ?? 'Unknown';
        $timestamp = now()->format('M d, H:i');
        $channel = $channelName ?? '#general';

        $logMessage = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
            'body' => "[$timestamp] @{$userName} used `{$command}` in {$channel}\nResult: {$result}",
            'type' => 'system',
        ]);

        DB::table('chat_discussion_topic_messages')->insert([
            'topic_id' => $botHistoryTopic->id,
            'message_id' => $logMessage->id,
            'created_at' => now(),
        ]);
    }

    private static function clearAllMessages(Conversation $conversation, ?int $topicId): array
    {
        $query = Message::where('conversation_id', $conversation->id);

        if ($topicId) {
            $messageIds = DB::table('chat_discussion_topic_messages')
                ->where('topic_id', $topicId)
                ->pluck('message_id');
            $query->whereIn('id', $messageIds);
        } else {
            $topicMessageIds = DB::table('chat_discussion_topic_messages')
                ->whereIn('topic_id', function ($q) use ($conversation) {
                    $q->select('id')->from('chat_discussion_topics')->where('conversation_id', $conversation->id);
                })
                ->pluck('message_id');
            $query->whereNotIn('id', $topicMessageIds);
        }

        $ids = $query->pluck('id')->toArray();
        $count = count($ids);

        if ($count > 0) {
            $query->delete();

            try {
                broadcast(new MessagesCleared($conversation->id, $ids, $topicId));
            } catch (\Exception $e) {
            }
        }

        $channelName = $topicId ? DiscussionTopic::find($topicId)?->name ?? 'this channel' : '#general';

        return ['success' => "Cleared $count messages from $channelName"];
    }

    private static function clearMessages(Conversation $conversation, ?int $topicId, int $count): array
    {
        $query = Message::where('conversation_id', $conversation->id)
            ->orderByDesc('id');

        if ($topicId) {
            $messageIds = DB::table('chat_discussion_topic_messages')
                ->where('topic_id', $topicId)
                ->pluck('message_id');
            $query->whereIn('id', $messageIds);
        } else {
            $topicMessageIds = DB::table('chat_discussion_topic_messages')
                ->whereIn('topic_id', function ($q) use ($conversation) {
                    $q->select('id')->from('chat_discussion_topics')->where('conversation_id', $conversation->id);
                })
                ->pluck('message_id');
            $query->whereNotIn('id', $topicMessageIds);
        }

        $ids = $query->take($count)->pluck('id')->toArray();
        $deleted = count($ids);

        if ($deleted > 0) {
            Message::whereIn('id', $ids)->delete();

            try {
                broadcast(new MessagesCleared($conversation->id, $ids, $topicId));
            } catch (\Exception $e) {
            }
        }

        return ['success' => "Cleared $deleted messages"];
    }

    private static function muteUser(Conversation $conversation, string $args, int $adminId): array
    {
        $parts = preg_split('/\s+/', trim($args), 2);
        $username = $parts[0] ?? '';
        $duration = $parts[1] ?? '5';

        if (empty($username)) {
            return ['error' => 'Usage: /mute <username> [duration in minutes, default: 5]'];
        }

        $username = ltrim($username, '@');
        $user = User::where('name', $username)->first();

        if (! $user) {
            return ['error' => "User not found: $username"];
        }

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $participant) {
            return ['error' => "$username is not a member of this group"];
        }

        if (self::isAdmin($conversation, $user->id)) {
            return ['error' => 'Cannot mute admins/moderators'];
        }

        $minutes = (int) $duration ?: 5;
        $mutedUntil = now()->addMinutes($minutes);

        $participant->update([
            'is_muted' => true,
            'muted_until' => $mutedUntil,
            'can_send_messages' => false,
        ]);

        try {
            broadcast(new UserMuted($conversation->id, $user->id, $user->name, $mutedUntil->toIso8601String()));
        } catch (\Exception $e) {
        }

        return ['success' => "Muted $username for $minutes minutes (until ".$mutedUntil->format('H:i').')'];
    }

    private static function unmuteUser(Conversation $conversation, string $args): array
    {
        $username = trim(ltrim($args, '@'));

        if (empty($username)) {
            return ['error' => 'Usage: /unmute <username>'];
        }

        $user = User::where('name', $username)->first();

        if (! $user) {
            return ['error' => "User not found: $username"];
        }

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $participant) {
            return ['error' => "$username is not a member of this group"];
        }

        $participant->update([
            'is_muted' => false,
            'muted_until' => null,
            'can_send_messages' => true,
        ]);

        try {
            broadcast(new UserUnmuted($conversation->id, $user->id, $user->name));
        } catch (\Exception $e) {
        }

        return ['success' => "Unmuted $username"];
    }

    private static function kickUser(Conversation $conversation, string $args, int $adminId): array
    {
        $username = trim(ltrim($args, '@'));

        if (empty($username)) {
            return ['error' => 'Usage: /kick <username>'];
        }

        $user = User::where('name', $username)->first();

        if (! $user) {
            return ['error' => "User not found: $username"];
        }

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->whereNull('left_at')
            ->first();

        if (! $participant) {
            return ['error' => "$username is not a member of this group"];
        }

        if (self::isAdmin($conversation, $user->id)) {
            return ['error' => 'Cannot kick admins/moderators'];
        }

        $participant->update(['left_at' => now()]);

        try {
            broadcast(new UserKicked($conversation->id, $user->id, $user->name));
        } catch (\Exception $e) {
        }

        return ['success' => "Kicked $username from the group"];
    }

    private static function warnUser(Conversation $conversation, string $args, int $adminId): array
    {
        $parts = preg_split('/\s+/', trim($args), 2);
        $username = ltrim($parts[0] ?? '', '@');
        $reason = $parts[1] ?? 'No reason provided';

        if (empty($username)) {
            return ['error' => 'Usage: /warn <username> [reason]'];
        }

        $user = User::where('name', $username)->first();

        if (! $user) {
            return ['error' => "User not found: $username"];
        }

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $participant) {
            return ['error' => "$username is not a member of this group"];
        }

        $settings = $conversation->settings ?? [];
        $warnings = $settings['user_warnings'] ?? [];
        $userWarnings = $warnings[$user->id] ?? 0;
        $warnings[$user->id] = $userWarnings + 1;
        $settings['user_warnings'] = $warnings;
        $conversation->settings = $settings;
        $conversation->save();

        $newCount = $userWarnings + 1;

        return ['success' => "Warned $username (Warning #$newCount): $reason"];
    }

    private static function getStats(Conversation $conversation, ?int $topicId): array
    {
        $query = Message::where('conversation_id', $conversation->id);

        if ($topicId) {
            $messageIds = DB::table('chat_discussion_topic_messages')
                ->where('topic_id', $topicId)
                ->pluck('message_id');
            $query->whereIn('id', $messageIds);
        }

        $totalMessages = $query->count();
        $todayMessages = (clone $query)->whereDate('created_at', today())->count();
        $memberCount = ConversationParticipant::where('conversation_id', $conversation->id)
            ->whereNull('left_at')
            ->count();
        $mutedCount = ConversationParticipant::where('conversation_id', $conversation->id)
            ->whereNull('left_at')
            ->where('is_muted', true)
            ->count();

        $topPosters = Message::where('conversation_id', $conversation->id)
            ->select('user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(5)
            ->with('user:id,name')
            ->get();

        $topList = $topPosters->map(fn ($p) => "• {$p->user->name}: {$p->count} messages")->implode("\n");

        $channelName = $topicId ? '#'.(DiscussionTopic::find($topicId)?->name ?? 'channel') : '#general';

        return [
            'bot_message' => true,
            'title' => "Stats for $channelName",
            'content' => "**Messages:** $totalMessages total, $todayMessages today\n".
                "**Members:** $memberCount active, $mutedCount muted\n\n".
                "**Top Posters:**\n$topList",
        ];
    }

    private static function setSlowmode(Conversation $conversation, string $args): array
    {
        $seconds = (int) $args;

        if ($seconds < 0 || $seconds > 3600) {
            return ['error' => 'Usage: /slowmode <seconds 0-3600> (0 to disable)'];
        }

        $settings = $conversation->settings ?? [];
        $settings['slowmode'] = $seconds;
        $conversation->settings = $settings;
        $conversation->save();

        if ($seconds === 0) {
            return ['success' => 'Slowmode disabled'];
        }

        return ['success' => "Slowmode set to $seconds seconds"];
    }

    private static function lockChannel(Conversation $conversation, ?int $topicId): array
    {
        if (! $topicId) {
            return ['error' => 'Cannot lock #general channel. Use /slowmode instead.'];
        }

        $topic = DiscussionTopic::find($topicId);
        if (! $topic) {
            return ['error' => 'Channel not found'];
        }

        $topic->update(['is_readonly' => true]);

        return ['success' => "Locked #{$topic->name} - only admins can post"];
    }

    private static function unlockChannel(Conversation $conversation, ?int $topicId): array
    {
        if (! $topicId) {
            return ['error' => '#general is always unlocked'];
        }

        $topic = DiscussionTopic::find($topicId);
        if (! $topic) {
            return ['error' => 'Channel not found'];
        }

        $topic->update(['is_readonly' => false]);

        return ['success' => "Unlocked #{$topic->name} - everyone can post"];
    }

    private static function helpResponse(): array
    {
        return [
            'bot_message' => true,
            'title' => 'HillBot Commands',
            'content' => "**Moderation Commands:**\n".
                "`/mute <user> [minutes]` - Mute a user (default: 5 min)\n".
                "`/unmute <user>` - Unmute a user\n".
                "`/kick <user>` - Remove user from group\n".
                "`/warn <user> [reason]` - Warn a user\n\n".
                "**Message Commands:**\n".
                "`/clear [count]` - Delete last N messages (default: 10)\n".
                "`/clear-all` - Delete all messages in channel\n\n".
                "**Channel Commands:**\n".
                "`/lock` - Make channel read-only\n".
                "`/unlock` - Allow everyone to post\n".
                "`/slowmode <seconds>` - Set slowmode (0 to disable)\n\n".
                "**Info Commands:**\n".
                "`/status` - Quick view of active settings\n".
                "`/stats` - Show channel statistics\n".
                "`/listrules` - Show all rules in detail\n\n".
                "**Auto-Mod Rules:**\n".
                "`/enable` / `/disable` - Toggle bot\n".
                "`/addrule <text>` - Block messages with text\n".
                "`/removerule <text>` - Remove a rule\n".
                "`/setaction <action>` - warn|delete|mute-5|mute-30|mute-60\n".
                "`/profanity on|off` - Toggle profanity filter\n".
                "`/spam on|off` - Toggle spam detection\n".
                "`/links on|off` - Toggle link blocking\n".
                "`/caps on|off` - Block excessive caps\n".
                '`/forcecaps on|off` - Force all messages to UPPERCASE',
        ];
    }

    private static function listRulesResponse(array $settings): array
    {
        $lines = ["**Current Bot Configuration:**\n"];

        $botEnabled = $settings['bot_enabled'] ?? false;
        $lines[] = 'Bot Status: '.($botEnabled ? '[ON] Enabled' : '[OFF] Disabled');
        $lines[] = 'Violation Action: '.($settings['violation_action'] ?? 'warn');
        $lines[] = 'Slowmode: '.(($settings['slowmode'] ?? 0) > 0 ? ($settings['slowmode'].'s') : '[OFF]');
        $lines[] = '';
        $lines[] = '**Built-in Rules:**';

        $rules = $settings['rules'] ?? [];
        $lines[] = '- Profanity Filter: '.(($rules['profanity'] ?? false) ? '[ON]' : '[OFF]');
        $lines[] = '- Spam Detection: '.(($rules['spam'] ?? false) ? '[ON]' : '[OFF]');
        $lines[] = '- Block Links: '.(($rules['links'] ?? false) ? '[ON]' : '[OFF]');
        $lines[] = '- Caps Limit: '.(($rules['caps'] ?? false) ? '[ON]' : '[OFF]');
        $lines[] = '- Force Uppercase: '.(($rules['forcecaps'] ?? false) ? '[ON]' : '[OFF]');

        $customRules = $settings['custom_rules'] ?? [];
        if (! empty($customRules)) {
            $lines[] = '';
            $lines[] = '**Custom Rules:**';
            foreach ($customRules as $rule) {
                $lines[] = "- Block: \"{$rule['pattern']}\"";
            }
        }

        return [
            'bot_message' => true,
            'title' => 'Bot Rules',
            'content' => implode("\n", $lines),
        ];
    }

    private static function statusResponse(array $settings): array
    {
        $botEnabled = $settings['bot_enabled'] ?? false;
        $rules = $settings['rules'] ?? [];
        $slowmode = $settings['slowmode'] ?? 0;
        $action = $settings['violation_action'] ?? 'warn';
        $customRules = $settings['custom_rules'] ?? [];

        $activeRules = [];
        if ($rules['profanity'] ?? false) {
            $activeRules[] = 'Profanity';
        }
        if ($rules['spam'] ?? false) {
            $activeRules[] = 'Spam';
        }
        if ($rules['links'] ?? false) {
            $activeRules[] = 'Links';
        }
        if ($rules['caps'] ?? false) {
            $activeRules[] = 'Caps';
        }
        if ($rules['forcecaps'] ?? false) {
            $activeRules[] = 'Force Uppercase';
        }

        $lines = [];
        $lines[] = '**Bot:** '.($botEnabled ? 'ACTIVE' : 'INACTIVE');
        $lines[] = '**Action:** '.strtoupper($action);
        $lines[] = '**Slowmode:** '.($slowmode > 0 ? "{$slowmode}s" : 'OFF');
        $lines[] = '';
        $lines[] = '**Active Filters:** '.(count($activeRules) > 0 ? implode(', ', $activeRules) : 'None');
        $lines[] = '**Custom Rules:** '.count($customRules);

        return [
            'bot_message' => true,
            'title' => 'Bot Status',
            'content' => implode("\n", $lines),
        ];
    }

    /**
     * Check a message against moderation rules
     * Returns null if OK, or array with violation info
     */
    public static function checkMessage(Conversation $conversation, string $message, int $userId): ?array
    {
        $settings = $conversation->settings ?? [];

        if (! ($settings['bot_enabled'] ?? false)) {
            return null;
        }

        // Admins bypass moderation
        if (self::isAdmin($conversation, $userId)) {
            return null;
        }

        $rules = $settings['rules'] ?? [];
        $customRules = $settings['custom_rules'] ?? [];
        $action = $settings['violation_action'] ?? 'warn';

        // Check profanity
        if ($rules['profanity'] ?? false) {
            if (self::containsProfanity($message)) {
                return ['reason' => 'profanity', 'action' => $action];
            }
        }

        // Check spam (repeated characters/words)
        if ($rules['spam'] ?? false) {
            if (self::isSpam($message)) {
                return ['reason' => 'spam', 'action' => $action];
            }
        }

        // Check links
        if ($rules['links'] ?? false) {
            if (self::containsLinks($message)) {
                return ['reason' => 'links', 'action' => $action];
            }
        }

        // Check excessive caps
        if ($rules['caps'] ?? false) {
            if (self::hasExcessiveCaps($message)) {
                return ['reason' => 'caps', 'action' => $action];
            }
        }

        // Check custom rules
        foreach ($customRules as $rule) {
            if (stripos($message, $rule['pattern']) !== false) {
                return ['reason' => 'custom', 'pattern' => $rule['pattern'], 'action' => $action];
            }
        }

        return null;
    }

    private static function containsProfanity(string $message): bool
    {
        // Basic profanity list - can be expanded
        $profanity = ['fuck', 'shit', 'ass', 'bitch', 'damn', 'crap', 'bastard', 'dick', 'pussy', 'cock'];
        $lower = strtolower($message);
        foreach ($profanity as $word) {
            if (preg_match('/\b'.preg_quote($word, '/').'\b/i', $lower)) {
                return true;
            }
        }

        return false;
    }

    private static function isSpam(string $message): bool
    {
        // Check for repeated characters (e.g., "aaaaaaa")
        if (preg_match('/(.)\1{5,}/', $message)) {
            return true;
        }
        // Check for repeated words
        $words = str_word_count(strtolower($message), 1);
        if (count($words) > 3) {
            $counts = array_count_values($words);
            foreach ($counts as $count) {
                if ($count > 3 && $count / count($words) > 0.5) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function containsLinks(string $message): bool
    {
        return preg_match('/https?:\/\/[^\s]+/i', $message) ||
               preg_match('/www\.[^\s]+/i', $message);
    }

    private static function hasExcessiveCaps(string $message): bool
    {
        $letters = preg_replace('/[^a-zA-Z]/', '', $message);
        if (strlen($letters) < 5) {
            return false;
        }

        $caps = preg_replace('/[^A-Z]/', '', $message);

        return strlen($caps) / strlen($letters) > 0.7;
    }

    /**
     * Get the action message for a violation
     */
    public static function getViolationMessage(array $violation): string
    {
        $reasons = [
            'profanity' => 'Your message was blocked for containing inappropriate language.',
            'spam' => 'Your message was blocked for spam.',
            'links' => 'Links are not allowed in this group.',
            'caps' => 'Please avoid using excessive capital letters.',
            'custom' => 'Your message was blocked by a group rule.',
        ];

        return $reasons[$violation['reason']] ?? 'Your message violated group rules.';
    }
}
