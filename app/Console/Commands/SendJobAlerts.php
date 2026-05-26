<?php

namespace App\Console\Commands;

use App\Models\JobAlertPreference;
use App\Models\JobPosting;
use Illuminate\Console\Command;

class SendJobAlerts extends Command
{
    protected $signature = 'job-alerts:send';

    protected $description = 'Send job alert notifications to candidates with matching new jobs';

    public function handle(): int
    {
        $preferences = JobAlertPreference::with('user')
            ->whereHas('user')
            ->where('is_active', true)
            ->get();

        $sent = 0;

        foreach ($preferences as $pref) {
            if (! $pref->shouldSendToday()) {
                continue;
            }

            $jobs = $this->getMatchingJobs($pref);

            if ($jobs->isEmpty()) {
                continue;
            }

            $jobData = $jobs->map(fn ($j) => [
                'title' => $j->title,
                'company' => $j->company?->name,
                'slug' => $j->slug,
            ])->all();

            $pref->user->notify(new \App\Notifications\JobAlertNotification(
                $jobData,
                $pref->name ?: 'Job Alert',
                $pref->email_enabled,
                $pref->id
            ));

            $pref->update(['last_sent_at' => now()]);
            $sent++;
        }

        $this->info("Sent job alerts to {$sent} user(s).");

        return Command::SUCCESS;
    }

    protected function getMatchingJobs(JobAlertPreference $pref)
    {
        $since = $pref->last_sent_at ?? now()->subDays($pref->frequency === 'weekly' ? 7 : 1);

        $query = JobPosting::with('company')
            ->approvedModeration()
            ->where('status', 'open')
            ->where('posted_at', '>=', $since)
            ->whereNotNull('posted_at');

        if ($pref->keywords) {
            $keywords = array_values(array_filter(array_map('trim', explode(',', $pref->keywords))));
            if (! empty($keywords)) {
                $query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('title', 'like', '%'.$kw.'%')
                            ->orWhere('summary', 'like', '%'.$kw.'%')
                            ->orWhere('description', 'like', '%'.$kw.'%')
                            ->orWhere('requirements', 'like', '%'.$kw.'%');
                    }
                });
            }
        }

        if ($pref->location) {
            $query->where('location', 'like', '%'.$pref->location.'%');
        }

        if ($pref->category) {
            $query->where('category', $pref->category);
        }

        if ($pref->remote_type) {
            $query->where('remote_type', $pref->remote_type);
        }

        if ($pref->employment_type) {
            $query->where('employment_type', $pref->employment_type);
        }

        return $query->latest('posted_at')->limit(10)->get();
    }
}
