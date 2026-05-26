<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    const STATUS_PENDING = 'pending';

    const STATUS_APPROVED = 'approved';

    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'onboarding_step',
        'onboarding_completed_at',
        'name',
        'slug',
        'logo_url',
        'location',
        'industry',
        'description',
        'website',
        'email',
        'phone',
        'contact_name',
        'contact_position',
        'contact_availability_time',
        'contact_person_email',
        'contact_person_phone',
        'established_year',
        'employees_count',
        // Verification fields
        'registration_type',
        'registration_number',
        'registration_document_url',
        // Business address
        'business_address',
        'city',
        'province',
        'postal_code',
        'country',
        'terms_agreed_at',
        // Status fields
        'verified',
        'verification_status',
        'rejection_reason',
        'verified_at',
        'verified_by',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'employees_count' => 'integer',
        'established_year' => 'integer',
        'verified_at' => 'datetime',
        'terms_agreed_at' => 'datetime',
        'onboarding_completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class);
    }

    public function isOnboarded(): bool
    {
        return ! is_null($this->onboarding_completed_at);
    }

    public function isApproved(): bool
    {
        return $this->verification_status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', self::STATUS_PENDING);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }
}
