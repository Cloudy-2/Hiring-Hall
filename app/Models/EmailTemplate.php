<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_REJECTION = 'rejection';

    public const TYPE_INTERVIEW_INVITE = 'interview_invite';

    public const TYPE_OFFER = 'offer';

    public const TYPE_FOLLOW_UP = 'follow_up';

    public const TYPE_CUSTOM = 'custom';

    public const TYPES = [
        self::TYPE_REJECTION => 'Rejection',
        self::TYPE_INTERVIEW_INVITE => 'Interview Invite',
        self::TYPE_OFFER => 'Job Offer',
        self::TYPE_FOLLOW_UP => 'Follow Up',
        self::TYPE_CUSTOM => 'Custom',
    ];

    public const AVAILABLE_PLACEHOLDERS = [
        '{applicant_name}' => 'Applicant\'s full name',
        '{applicant_first_name}' => 'Applicant\'s first name',
        '{applicant_email}' => 'Applicant\'s email address',
        '{job_title}' => 'Job posting title',
        '{company_name}' => 'Company name',
        '{employer_name}' => 'Employer/sender name',
        '{application_status}' => 'Current application status',
        '{interview_date}' => 'Interview date',
        '{interview_time}' => 'Interview time',
        '{interview_location}' => 'Interview location/link',
        '{today_date}' => 'Today\'s date',
    ];

    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'subject',
        'body',
        'type',
        'placeholders',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'placeholders' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeAccessibleBy($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhereIn('company_id', $user->companies()->pluck('id'));
        });
    }

    /**
     * Replace placeholders in the template with actual values.
     *
     * @param  array<string, string>  $data
     */
    public function render(array $data): array
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($data as $key => $value) {
            $placeholder = '{'.$key.'}';
            $subject = str_replace($placeholder, $value, $subject);
            $body = str_replace($placeholder, $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? 'Custom';
    }
}
