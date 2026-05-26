<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public array $jobs,
        public string $alertName = 'Your job alert',
        public bool $emailEnabled = true,
        public ?int $jobAlertPreferenceId = null
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if ($this->emailEnabled) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $count = count($this->jobs);
        $mail = (new MailMessage)
            ->subject($count.' new job(s) match your alert: '.$this->alertName)
            ->line('We found '.$count.' new job(s) that match your job alert criteria.');

        foreach (array_slice($this->jobs, 0, 5) as $job) {
            $mail->line('• **'.($job['title'] ?? 'Job').'** at '.($job['company'] ?? 'Company'));
        }

        if ($count > 5) {
            $mail->line('... and '.($count - 5).' more.');
        }

        return $mail->action('View Jobs', route('jobs'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $count = count($this->jobs);
        $message = $count === 1
            ? '1 new job matches your alert: '.$this->alertName
            : $count.' new jobs match your alert: '.$this->alertName;

        $jobSlugs = array_values(array_filter(array_map(
            fn ($j) => $j['slug'] ?? null,
            $this->jobs
        )));

        $jobsUrl = $this->jobAlertPreferenceId
            ? url(route('applicant.job-alerts.show', ['jobAlert' => $this->jobAlertPreferenceId, 'slugs' => implode(',', $jobSlugs)]))
            : url(route('jobs'));

        return [
            'type' => 'job_alert',
            'title' => 'Job Alert: '.$this->alertName,
            'subject' => 'Job Alert: '.$this->alertName,
            'message' => $message,
            'action_url' => $jobsUrl,
            'url' => $jobsUrl,
            'job_count' => $count,
            'job_alert_preference_id' => $this->jobAlertPreferenceId,
            'job_slugs' => $jobSlugs,
        ];
    }
}
