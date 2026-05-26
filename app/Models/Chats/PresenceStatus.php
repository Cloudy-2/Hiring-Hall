<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PresenceStatus extends Model
{
    protected $table = 'chat_presence_statuses';

    protected $fillable = [
        'user_id',
        'status',
        'platform',
        'custom_status',
        'last_active_at',
        'expires_at',
        'device_context',
        'current_conversation_id',
        'current_topic_slug',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
        'expires_at' => 'datetime',
        'device_context' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentConversation()
    {
        return $this->belongsTo(Conversation::class, 'current_conversation_id');
    }

    /**
     * Update user's current location (conversation/topic they're viewing)
     */
    public static function updateLocation(int $userId, ?int $conversationId, ?string $topicSlug = null): void
    {
        static::where('user_id', $userId)->update([
            'current_conversation_id' => $conversationId,
            'current_topic_slug' => $topicSlug,
        ]);
    }

    /**
     * Update or create presence for a user
     * Preserves 'invisible' status if already set
     */
    public static function setOnline(int $userId, string $platform = 'web'): self
    {
        $existing = static::where('user_id', $userId)->first();

        // If user has invisible status and it hasn't expired, just extend the expiry without changing status
        if ($existing && $existing->status === 'invisible') {
            // Check if not expired
            if (! $existing->expires_at || ! $existing->expires_at->isPast()) {
                $existing->platform = $platform;
                $existing->last_active_at = now();
                $existing->expires_at = now()->addHours(24);
                $existing->save();

                return $existing->fresh();
            }
        }

        return static::updateOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'online',
                'platform' => $platform,
                'last_active_at' => now(),
                'expires_at' => now()->addMinutes(5),
            ]
        );
    }

    public static function setOffline(int $userId): self
    {
        // Check if user has invisible status - preserve it (they want to stay hidden)
        $existing = static::where('user_id', $userId)->first();
        if ($existing && $existing->status === 'invisible') {
            if (! $existing->expires_at || ! $existing->expires_at->isPast()) {
                // Don't change to offline, keep invisible
                return $existing;
            }
        }

        return static::updateOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'offline',
                'last_active_at' => now(),
            ]
        );
    }

    public static function setIdle(int $userId): self
    {
        // Check if user has invisible status - preserve it
        $existing = static::where('user_id', $userId)->first();
        if ($existing && $existing->status === 'invisible') {
            if (! $existing->expires_at || ! $existing->expires_at->isPast()) {
                $existing->last_active_at = now();
                $existing->save();

                return $existing->fresh();
            }
        }

        return static::updateOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'idle',
                'last_active_at' => now(),
            ]
        );
    }

    /**
     * Get user's current status (checks expiry)
     */
    public static function getStatus(int $userId): string
    {
        $presence = static::where('user_id', $userId)->first();

        if (! $presence) {
            return 'offline';
        }

        // If expired, consider offline
        if ($presence->expires_at && $presence->expires_at->isPast()) {
            return 'offline';
        }

        return $presence->status;
    }

    /**
     * Check if user is online
     */
    public static function isOnline(int $userId): bool
    {
        return in_array(static::getStatus($userId), ['online', 'idle', 'dnd']);
    }
}
