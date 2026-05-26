<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewScheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Interview $interview,
        public bool $isRescheduled = false
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $interview = $this->interview;
        $interview->load(['jobApplication.jobPosting.company', 'employer']);

        $jobTitle = $interview->jobApplication?->jobPosting?->title ?? 'a position';
        $companyName = $interview->jobApplication?->jobPosting?->company?->name ?? 'the company';

        $subject = $this->isRescheduled
            ? "Interview Rescheduled: {$interview->title}"
            : "Interview Invitation: {$interview->title}";

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello '.($notifiable->name ?? 'Applicant').'!');

        if ($this->isRescheduled) {
            $mail->line("Your interview for **{$jobTitle}** at **{$companyName}** has been rescheduled.");
        } else {
            $mail->line("You have been invited to an interview for **{$jobTitle}** at **{$companyName}**.");
        }

        $mail->line('')
            ->line('**Interview Details:**')
            ->line('- **Date & Time:** '.$interview->scheduled_at->format('F j, Y \a\t g:i A'))
            ->line("- **Duration:** {$interview->duration_minutes} minutes")
            ->line('- **Type:** '.$interview->getTypeLabel());

        if ($interview->location) {
            $mail->line("- **Location:** {$interview->location}");
        }

        if ($interview->meeting_link) {
            $mail->line("- **Meeting Link:** {$interview->meeting_link}");
        }

        if ($interview->description) {
            $mail->line('')
                ->line('**Additional Information:**')
                ->line($interview->description);
        }

        $mail->line('')
            ->line('Please make sure to be available at the scheduled time.')
            ->line('Good luck with your interview!')
            ->salutation('Best regards,<br>'.config('app.name'));

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        $interview = $this->interview;
        $interview->load(['jobApplication.jobPosting.company']);

        $jobTitle = $interview->jobApplication?->jobPosting?->title ?? 'a position';
        $companyName = $interview->jobApplication?->jobPosting?->company?->name ?? 'the company';

        $title = $this->isRescheduled
            ? 'Interview Rescheduled'
            : 'Interview Invitation';

        $message = $this->isRescheduled
            ? "Your interview for {$jobTitle} at {$companyName} has been rescheduled to ".$interview->scheduled_at->format('M j, Y g:i A')
            : "You've been invited for an interview for {$jobTitle} at {$companyName} on ".$interview->scheduled_at->format('M j, Y g:i A');

        return [
            'title' => $title,
            'message' => $message,
            'type' => 'interview_scheduled',
            'interview_id' => $interview->id,
            'scheduled_at' => $interview->scheduled_at->toIso8601String(),
            'is_rescheduled' => $this->isRescheduled,
            'url' => route('applicant.interviews.index'),
        ];
    }
}
