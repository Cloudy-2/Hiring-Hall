<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileManager extends Model
{
    use HasFactory;

    protected $table = 't_file_manager';

    /** 500 MB in bytes — applies to employer & applicant roles */
    public const STORAGE_LIMIT_BYTES = 524288000;

    /** Roles that are subject to the storage quota */
    public const RESTRICTED_ROLES = ['employer', 'applicant'];

    protected $fillable = ['link', 'name', 'path', 'size', 'format', 'mime_type', 'user_id', 'parent_id', 'is_folder', 'isDeleted', 'google_drive_folder_id', 'google_drive_id', 'uploader_id'];

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FileManager::class, 'parent_id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FileManager::class, 'parent_id');
    }

    /**
     * Get the total bytes used by non-deleted files for a given user.
     */
    public static function usedStorageBytes(int $userId): int
    {
        return (int) static::query()
            ->where('user_id', $userId)
            ->where('is_folder', false)
            ->where('isDeleted', 0)
            ->sum('size');
    }

    /**
     * Return whether the given user role is subject to a storage quota.
     */
    public static function isStorageRestricted(string $role): bool
    {
        return in_array($role, self::RESTRICTED_ROLES, true);
    }
}
