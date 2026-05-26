<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicantInterviewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DropdownOptionsSeeder::class);
    }

    public function test_interview_model_scopes_work(): void
    {
        $employer = User::factory()->create(['role' => 'employer', 'email_verified_at' => now()]);
        $applicant = User::factory()->create(['role' => 'applicant', 'email_verified_at' => now()]);
        $profile = ApplicantProfile::create([
            'user_id' => $applicant->id,
            'display_name' => $applicant->name,
            'onboarding_completed' => true,
        ]);

        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
            'user_id' => $employer->id,
            'verified' => true,
        ]);

        $job = JobPosting::create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'title' => 'Test Job',
            'slug' => 'test-job',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $applicant->id,
            'applicant_profile_id' => $profile->id,
            'status' => 'accepted',
            'applied_at' => now(),
        ]);

        $upcomingInterview = Interview::create([
            'job_application_id' => $application->id,
            'employer_id' => $employer->id,
            'applicant_id' => $applicant->id,
            'title' => 'Upcoming Interview',
            'interview_type' => 'video',
            'scheduled_at' => now()->addDays(3),
            'duration_minutes' => 60,
            'status' => 'scheduled',
        ]);

        $pastInterview = Interview::create([
            'job_application_id' => $application->id,
            'employer_id' => $employer->id,
            'applicant_id' => $applicant->id,
            'title' => 'Past Interview',
            'interview_type' => 'phone',
            'scheduled_at' => now()->subDays(3),
            'duration_minutes' => 30,
            'status' => 'completed',
        ]);

        $this->assertEquals(1, Interview::where('applicant_id', $applicant->id)->upcoming()->count());
        $this->assertTrue($upcomingInterview->isUpcoming());
        $this->assertFalse($pastInterview->isUpcoming());
        $this->assertTrue($pastInterview->isPast());
    }

    public function test_interview_reminder_notification_can_be_created(): void
    {
        $employer = User::factory()->create(['role' => 'employer', 'email_verified_at' => now()]);
        $applicant = User::factory()->create(['role' => 'applicant', 'email_verified_at' => now()]);
        $profile = ApplicantProfile::create([
            'user_id' => $applicant->id,
            'display_name' => $applicant->name,
            'onboarding_completed' => true,
        ]);

        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
            'user_id' => $employer->id,
            'verified' => true,
        ]);

        $job = JobPosting::create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'title' => 'Test Job',
            'slug' => 'test-job',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $applicant->id,
            'applicant_profile_id' => $profile->id,
            'status' => 'accepted',
            'applied_at' => now(),
        ]);

        $interview = Interview::create([
            'job_application_id' => $application->id,
            'employer_id' => $employer->id,
            'applicant_id' => $applicant->id,
            'title' => 'Technical Interview',
            'interview_type' => 'video',
            'scheduled_at' => now()->addDays(3),
            'duration_minutes' => 60,
            'status' => 'scheduled',
        ]);

        $notification = new \App\Notifications\InterviewReminderNotification($interview);
        $notificationArray = $notification->toArray($applicant);

        $this->assertEquals('Interview Reminder', $notificationArray['title']);
        $this->assertEquals('interview_reminder', $notificationArray['type']);
        $this->assertEquals($interview->id, $notificationArray['interview_id']);
    }

    public function test_application_status_changed_notification_works(): void
    {
        $employer = User::factory()->create(['role' => 'employer', 'email_verified_at' => now()]);
        $applicant = User::factory()->create(['role' => 'applicant', 'email_verified_at' => now()]);
        $profile = ApplicantProfile::create([
            'user_id' => $applicant->id,
            'display_name' => $applicant->name,
            'onboarding_completed' => true,
        ]);

        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
            'user_id' => $employer->id,
            'verified' => true,
        ]);

        $job = JobPosting::create([
            'company_id' => $company->id,
            'user_id' => $employer->id,
            'title' => 'Test Job',
            'slug' => 'test-job',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $applicant->id,
            'applicant_profile_id' => $profile->id,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        $notification = new \App\Notifications\ApplicationStatusChangedNotification($application, 'applied', 'accepted');
        $notificationArray = $notification->toArray($applicant);

        $this->assertEquals('Application Status Updated', $notificationArray['title']);
        $this->assertEquals('application_status_changed', $notificationArray['type']);
        $this->assertEquals($application->id, $notificationArray['application_id']);
        $this->assertStringContainsString('Accepted', $notificationArray['message']);
    }
}
