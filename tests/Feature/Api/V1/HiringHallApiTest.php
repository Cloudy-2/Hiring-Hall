<?php

namespace Tests\Feature\Api\V1;

use App\Models\ApplicantProfile;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\PipelineStage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HiringHallApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_registration_returns_documented_json_contract(): void
    {
        Notification::fake();

        $response = $this->postJson('/api/v1/auth/register/applicant', [
            'name' => 'Alex Applicant',
            'email' => 'alex@example.com',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'alex@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'alex@example.com',
            'role' => 'applicant',
        ]);
    }

    public function test_login_and_logout_return_documented_json_contracts(): void
    {
        $user = User::factory()->create([
            'role' => 'applicant',
            'email' => 'alex@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
            'onboarding_step' => 1,
        ]);

        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'alex@example.com',
            'password' => 'password',
            'device_name' => 'phpunit',
        ]);

        $login->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonPath('data.abilities.0', 'applicant')
            ->assertJsonStructure(['data' => ['token', 'user']]);

        $token = $login->json('data.token');

        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Logged out successfully.');
    }

    public function test_role_abilities_block_cross_role_routes(): void
    {
        $applicant = User::factory()->create(['role' => 'applicant']);
        $employer = User::factory()->create(['role' => 'employer']);

        Sanctum::actingAs($applicant, ['applicant']);
        $this->getJson('/api/v1/employer/jobs')
            ->assertForbidden()
            ->assertJsonPath('success', false);

        Sanctum::actingAs($employer, ['employer']);
        $this->getJson('/api/v1/applicant/jobs')
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_list_request_rejects_per_page_over_safe_max(): void
    {
        Sanctum::actingAs(User::factory()->create(['role' => 'applicant']), ['applicant']);

        $this->getJson('/api/v1/applicant/jobs?per_page=101')
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonValidationErrors('per_page');
    }

    public function test_employer_cannot_schedule_interview_for_another_company_application(): void
    {
        [$employer, $company] = $this->employerWithCompany('Owner Company');
        [$otherEmployer, $otherCompany] = $this->employerWithCompany('Other Company');
        [$applicant, $profile] = $this->applicantWithProfile();

        $application = $this->applicationFor($otherCompany, $applicant, $profile);

        Sanctum::actingAs($employer, ['employer']);

        $this->postJson('/api/v1/employer/interviews', [
            'job_application_id' => $application->id,
            'applicant_id' => $applicant->id,
            'title' => 'Technical Interview',
            'interview_type' => 'video',
            'scheduled_at' => now()->addDay()->toIso8601String(),
            'duration_minutes' => 30,
            'meeting_link' => 'https://example.com/meet',
        ])
            ->assertForbidden()
            ->assertJsonPath('success', false);

        $this->assertDatabaseMissing('interviews', [
            'job_application_id' => $application->id,
            'employer_id' => $employer->id,
        ]);

        $this->assertNotEquals($company->id, $otherCompany->id);
        $this->assertNotEquals($employer->id, $otherEmployer->id);
    }

    public function test_employer_can_list_company_applications_across_jobs_with_filters(): void
    {
        [$employer, $company] = $this->employerWithCompany('Owner Company');
        [, $otherCompany] = $this->employerWithCompany('Other Company');
        [$applicant, $profile] = $this->applicantWithProfile();
        [$otherApplicant, $otherProfile] = $this->applicantWithProfile();

        $stage = PipelineStage::create([
            'company_id' => $company->id,
            'name' => 'Screening',
            'color' => '#8b5cf6',
            'sort_order' => 2,
            'is_default' => false,
            'is_system' => true,
        ]);

        $ownedApplication = $this->applicationFor($company, $applicant, $profile);
        $ownedApplication->update([
            'status' => 'reviewing',
            'pipeline_stage_id' => $stage->id,
            'cover_letter' => 'I am interested in this role.',
        ]);
        $this->applicationFor($otherCompany, $otherApplicant, $otherProfile);

        Sanctum::actingAs($employer, ['employer']);

        $this->getJson("/api/v1/employer/applications?status=reviewing&stage_id={$stage->id}")
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.id', $ownedApplication->id)
            ->assertJsonPath('data.0.pipeline_stage.id', $stage->id)
            ->assertJsonPath('data.0.applicant_profile.user.email', $applicant->email)
            ->assertJsonPath('data.0.job_posting.company.id', $company->id);
    }

    public function test_employer_cannot_save_applicant_without_company_application(): void
    {
        [$employer] = $this->employerWithCompany('Owner Company');
        [$applicant, $profile] = $this->applicantWithProfile();

        Sanctum::actingAs($employer, ['employer']);

        $this->postJson("/api/v1/employer/saved-applicants/{$profile->id}")
            ->assertForbidden()
            ->assertJsonPath('success', false);

        $this->assertDatabaseMissing('saved_applicants', [
            'employer_id' => $employer->id,
            'applicant_profile_id' => $profile->id,
        ]);

        $this->assertSame('applicant', $applicant->role);
    }

    public function test_employer_onboarding_creates_missing_company_profile(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'name' => 'Taylor Employer',
        ]);

        Sanctum::actingAs($employer, ['employer']);

        $this->postJson('/api/v1/employer/onboarding', ['step' => 1])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.onboarding_step', 1)
            ->assertJsonPath('data.onboarding_completed', false);

        $this->assertDatabaseHas('companies', [
            'user_id' => $employer->id,
            'name' => "Taylor Employer's Company",
            'slug' => 'taylor-employers-company',
            'verification_status' => Company::STATUS_PENDING,
        ]);
    }

    private function applicantWithProfile(): array
    {
        $user = User::factory()->create(['role' => 'applicant']);

        $profile = ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
            'onboarding_step' => 1,
        ]);

        return [$user, $profile];
    }

    private function employerWithCompany(string $name): array
    {
        $user = User::factory()->create(['role' => 'employer']);

        $company = Company::create([
            'user_id' => $user->id,
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'verified' => true,
            'verification_status' => Company::STATUS_APPROVED,
            'onboarding_step' => 4,
            'onboarding_completed_at' => now(),
        ]);

        return [$user, $company];
    }

    private function applicationFor(Company $company, User $applicant, ApplicantProfile $profile): JobApplication
    {
        $job = JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Support Specialist',
            'slug' => 'support-specialist-'.$company->id,
            'status' => 'active',
            'moderation_status' => JobPosting::MODERATION_APPROVED,
            'posted_at' => now(),
        ]);

        return JobApplication::create([
            'job_posting_id' => $job->id,
            'user_id' => $applicant->id,
            'applicant_profile_id' => $profile->id,
            'status' => 'pending',
            'applied_at' => now(),
            'terms_agreed_at' => now(),
        ]);
    }
}
