<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected JobApplication $application,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $candidateName = $this->application->user->name ?? 'A candidate';
        $jobTitle = $this->application->jobPosting->title ?? 'your job posting';

        return [
            'title' => 'New Application Received',
            'message' => "{$candidateName} applied for \"{$jobTitle}\".",
            'action_url' => route('employer.applications.index'),
            'application_id' => $this->application->id,
            'job_posting_id' => $this->application->job_posting_id,
        ];
    }
}
