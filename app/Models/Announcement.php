<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    public const TYPE_INFO = 'info';

    public const TYPE_WARNING = 'warning';

    public const TYPE_SUCCESS = 'success';

    public const TYPE_DANGER = 'danger';

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_roles',
        'is_pinned',
        'is_active',
        'published_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'is_pinned' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeForRole($query, ?string $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_roles')
                ->orWhereJsonContains('target_roles', $role)
                ->orWhereJsonContains('target_roles', 'all');
        });
    }

    public function isPublished(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->published_at && $this->published_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public static function getActiveForUser(?User $user): \Illuminate\Database\Eloquent\Collection
    {
        $role = $user?->role ?? 'guest';

        return self::active()
            ->forRole($role)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->get();
    }

    public static function getUnreadCountForUser(?User $user): int
    {
        $role = $user?->role ?? 'guest';
        $lastReadAt = $user?->announcements_last_read_at;

        $query = self::active()->forRole($role);

        if (! $lastReadAt) {
            return $query->count();
        }

        return $query
            ->where(function ($q) use ($lastReadAt) {
                $q->where('published_at', '>', $lastReadAt)
                    ->orWhere(function ($q2) use ($lastReadAt) {
                        $q2->whereNull('published_at')
                            ->where('created_at', '>', $lastReadAt);
                    });
            })
            ->count();
    }
}
