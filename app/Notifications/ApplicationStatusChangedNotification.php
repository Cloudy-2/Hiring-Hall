<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected JobApplication $application,
        protected string $oldStatus,
        protected string $newStatus,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $this->application->load(['jobPosting.company']);
        $jobTitle = $this->application->jobPosting->title ?? 'a job';

        $statusInfo = $this->getStatusInfo();

        return [
            'title' => 'Application Status Updated',
            'message' => "Your application for \"{$jobTitle}\" is now: {$statusInfo['label']}.",
            'type' => 'application_status_changed',
            'url' => route('applicant.applications.index'),
            'application_id' => $this->application->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }

    protected function getStatusInfo(): array
    {
        return match ($this->newStatus) {
            'applied', 'submitted' => [
                'label' => 'Submitted',
                'message' => 'Your application has been successfully submitted.',
            ],
            'under_review' => [
                'label' => 'Under Review',
                'message' => 'Great news! Your application is now being reviewed by the hiring team.',
            ],
            'application_viewed', 'viewed' => [
                'label' => 'Application Viewed',
                'message' => 'The employer has viewed your application.',
            ],
            'accepted' => [
                'label' => 'Accepted',
                'message' => 'Congratulations! Your application has been accepted. The employer may contact you soon for the next steps.',
            ],
            'declined', 'not_selected', 'rejected' => [
                'label' => 'Not Selected',
                'message' => 'Unfortunately, the employer has decided to move forward with other candidates. Don\'t give up - keep applying!',
            ],
            'interview_scheduled' => [
                'label' => 'Interview Scheduled',
                'message' => 'An interview has been scheduled. Please check your interviews page for details.',
            ],
            'interviewed' => [
                'label' => 'Interviewed',
                'message' => 'Thank you for attending the interview. The employer will review and get back to you.',
            ],
            'offered' => [
                'label' => 'Job Offered',
                'message' => 'Congratulations! You have received a job offer.',
            ],
            'hired' => [
                'label' => 'Hired',
                'message' => 'Congratulations! You have been hired for this position.',
            ],
            'withdrawn' => [
                'label' => 'Withdrawn',
                'message' => 'Your application has been withdrawn.',
            ],
            default => [
                'label' => str_replace('_', ' ', ucfirst($this->newStatus)),
                'message' => null,
            ],
        };
    }
}
