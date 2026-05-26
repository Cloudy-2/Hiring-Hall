<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_posting_id',
        'candidate_profile_id',
        'applicant_profile_id',
        'user_id',
        'status',
        'pipeline_stage_id',
        'cover_letter',
        'cv_path',
        'applied_at',
        'terms_agreed_at',
        'reviewed_at',
        'notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'terms_agreed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function applicantProfile()
    {
        return $this->belongsTo(ApplicantProfile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pipelineStage()
    {
        return $this->belongsTo(PipelineStage::class);
    }
}
