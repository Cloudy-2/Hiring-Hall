<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\JobAlertPreference;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobAlertNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_alert_notification_links_to_alert_results_page(): void
    {
        $user = User::factory()->create([
            'role' => 'applicant',
            'email_verified_at' => now(),
        ]);

        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
            'onboarding_step' => 4,
            'onboarding_completed_at' => now(),
        ]);

        $company = Company::create([
            'name' => 'Acme Inc',
            'slug' => 'acme-inc',
        ]);

        $job = JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Operations Virtual Assistant',
            'slug' => 'operations-virtual-assistant',
            'summary' => 'We need an operations VA',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $alert = JobAlertPreference::create([
            'user_id' => $user->id,
            'name' => 'Ops Alert',
            'keywords' => 'operations',
            'frequency' => 'daily',
            'email_enabled' => false,
            'is_active' => true,
        ]);

        $this->artisan('job-alerts:send')->assertExitCode(0);

        $notification = $user->notifications()->latest()->first();
        $this->assertNotNull($notification);
        $this->assertSame('job_alert', $notification->data['type'] ?? null);

        $url = $notification->data['action_url'] ?? null;
        $this->assertNotEmpty($url);
        $this->assertStringContainsString('/applicant/job-alerts/'.$alert->id, $url);
        $this->assertStringContainsString('slugs='.$job->slug, $url);

        $path = parse_url($url, PHP_URL_PATH) ?? $url;
        $query = parse_url($url, PHP_URL_QUERY);
        $requestUrl = $path.($query ? ('?'.$query) : '');

        $this->actingAs($user)
            ->get($requestUrl)
            ->assertOk()
            ->assertSee($alert->name)
            ->assertSee($job->title);
    }
}
