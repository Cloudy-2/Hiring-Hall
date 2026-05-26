<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Interview $interview
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
        $timeUntil = $interview->scheduled_at->diffForHumans();

        $mail = (new MailMessage)
            ->subject("Reminder: Your Interview is {$timeUntil}")
            ->greeting('Hello '.($notifiable->name ?? 'Applicant').'!')
            ->line("This is a friendly reminder about your upcoming interview for **{$jobTitle}** at **{$companyName}**.")
            ->line('')
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
            ->line('Please make sure to be prepared and on time.')
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
        $timeUntil = $interview->scheduled_at->diffForHumans();

        return [
            'title' => 'Interview Reminder',
            'message' => "Reminder: Your interview for {$jobTitle} at {$companyName} is scheduled {$timeUntil} on ".$interview->scheduled_at->format('M j, Y g:i A'),
            'type' => 'interview_reminder',
            'interview_id' => $interview->id,
            'scheduled_at' => $interview->scheduled_at->toIso8601String(),
            'url' => route('applicant.interviews.index'),
        ];
    }
}
