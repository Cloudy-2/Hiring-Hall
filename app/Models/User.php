<?php

namespace App\Models;

use App\Models\Chats\Conversation;
use App\Models\Chats\ConversationParticipant;
use App\Models\Chats\DiscussionTopic;
use App\Models\Chats\PresenceStatus;
use App\Traits\LogsActivity;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Wirechat\Wirechat\Contracts\WirechatUser;
use Wirechat\Wirechat\Panel;
use Wirechat\Wirechat\Traits\InteractsWithWirechat;

class User extends Authenticatable implements MustVerifyEmail, WirechatUser
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use InteractsWithWirechat;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * Decide if this user may access the given panel.
     */
    public function canAccessWirechatPanel(Panel $panel): bool
    {
        // if you use email verification:
        return $this->hasVerifiedEmail();
        // or just: return true;
    }

    /**
     * Control whether this user is allowed to create 1-to-1 chats.
     * Candidates cannot create DMs - they must wait to be assigned.
     */
    public function canCreateChats(): bool
    {
        return $this->role !== 'applicant';
    }

    /**
     * Control whether this user can create group conversations.
     * Applicants cannot create groups - they must wait to be assigned.
     */
    public function canCreateGroups(): bool
    {
        return $this->role !== 'applicant';
    }

    /**
     * Check if user is an applicant (restricted chat permissions)
     */
    public function isApplicant(): bool
    {
        return $this->role === 'applicant';
    }

    /**
     * Alias for isApplicant() - backward compatibility
     */
    public function isCandidate(): bool
    {
        return $this->isApplicant();
    }

    /**
     * Get the applicant profile for applicant users.
     */
    public function applicantProfile()
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    /**
     * Check if user can post in a specific channel
     */
    public function canPostInChannel($topicId): bool
    {
        // Moderators can always post
        if ($this->isModerator()) {
            return true;
        }

        $topic = DiscussionTopic::find($topicId);
        if (! $topic) {
            return true; // No topic = general chat
        }

        // Check if channel is readonly (mod-only)
        if ($topic->is_readonly) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can view a specific channel
     */
    public function canViewChannel($topicId, $conversationId): bool
    {
        // Moderators can view all
        if ($this->isModerator()) {
            return true;
        }

        $participant = ConversationParticipant::where('conversation_id', $conversationId)
            ->where('user_id', $this->id)
            ->whereNull('left_at')
            ->first();

        if (! $participant) {
            return false;
        }

        // If can_view_channels is null, user can view all
        if (is_null($participant->can_view_channels)) {
            return true;
        }

        $allowedChannels = is_array($participant->can_view_channels)
            ? $participant->can_view_channels
            : json_decode($participant->can_view_channels, true) ?? [];

        return in_array($topicId, $allowedChannels);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'date_of_birth',
        'gender',
        'phone',
        'marital_status',
        'address',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_github',
        'social_youtube',
        'workspace_user_id',
        'workspace_linked_at',
        'terms_shared_data_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     * Override Jetstream's default to use DiceBear avatars.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/'.$this->profile_photo_path);
        }

        return 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($this->name ?? 'User').'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'workspace_linked_at' => 'datetime',
            'terms_shared_data_at' => 'datetime',
            'announcements_last_read_at' => 'datetime',
        ];
    }

    /**
     * Get user's presence status
     */
    public function presence()
    {
        return $this->hasOne(PresenceStatus::class);
    }

    /**
     * Get user's current online status
     */
    public function getOnlineStatusAttribute(): string
    {
        return PresenceStatus::getStatus($this->id);
    }

    /**
     * Check if user is online
     */
    public function isOnline(): bool
    {
        return PresenceStatus::isOnline($this->id);
    }

    /**
     * Get the company for employer users (one-to-one, legacy).
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get all companies for employer users (one-to-many).
     */
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    /**
     * Get the per-user theme preference.
     */
    public function themePreference(): HasOne
    {
        return $this->hasOne(UserThemePreference::class);
    }

    /**
     * Get conversations this user participates in
     */
    public function conversations()
    {
        return $this->belongsToMany(
            Conversation::class,
            'chat_conversation_participants',
            'user_id',
            'conversation_id'
        )->whereNull('chat_conversation_participants.left_at');
    }

    /**
     * Check if user is a system moderator (silent admin in all groups)
     * Includes admin and super_admin roles as they have all moderator privileges
     */
    public function isModerator(): bool
    {
        return in_array($this->role, ['moderator', 'admin', 'super_admin']);
    }

    /**
     * Check if user is a system admin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user has staff privileges (moderator, admin, or super_admin)
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['moderator', 'admin', 'super_admin']);
    }

    /**
     * Check if user has admin privileges in a specific conversation
     * Moderators are silent admins in ALL groups
     */
    public function hasGroupAdminAccess($conversationId): bool
    {
        // System moderators have admin access to all groups
        if ($this->isModerator()) {
            return true;
        }

        // Check if user is owner or admin in the conversation
        $participant = ConversationParticipant::where('conversation_id', $conversationId)
            ->where('user_id', $this->id)
            ->whereNull('left_at')
            ->first();

        return $participant && in_array($participant->role, ['owner', 'admin']);
    }

    /**
     * Check if user can moderate a conversation (view, manage, etc.)
     */
    public function canModerateConversation($conversationId): bool
    {
        return $this->isModerator() || $this->hasGroupAdminAccess($conversationId);
    }
}
