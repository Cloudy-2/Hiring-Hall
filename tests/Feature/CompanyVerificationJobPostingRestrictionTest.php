<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyVerificationJobPostingRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DropdownOptionsSeeder::class);
    }

    public function test_employer_without_verified_company_sees_restriction_on_post_job_page(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);

        Company::create([
            'user_id' => $employer->id,
            'name' => 'Pending Co',
            'slug' => 'pending-co',
            'verified' => false,
            'verification_status' => Company::STATUS_PENDING,
            'onboarding_step' => 3,
            'onboarding_completed_at' => now(),
        ]);

        $this->actingAs($employer)
            ->get(route('jobs.create'))
            ->assertOk()
            ->assertSee('Company verification required')
            ->assertSee(route('employer.companies.index'));
    }

    public function test_employer_without_verified_company_cannot_store_job_posting(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);

        $company = Company::create([
            'user_id' => $employer->id,
            'name' => 'Pending Co',
            'slug' => 'pending-co-2',
            'verified' => false,
            'verification_status' => Company::STATUS_PENDING,
            'onboarding_step' => 3,
            'onboarding_completed_at' => now(),
        ]);

        $this->actingAs($employer)
            ->post(route('jobs.store'), [
                'company_id' => $company->id,
                'title' => 'Test Job',
            ])
            ->assertRedirect(route('employer.companies.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('job_postings', [
            'title' => 'Test Job',
        ]);
    }

    public function test_employer_jobs_page_shows_indicator_when_company_not_verified(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);

        Company::create([
            'user_id' => $employer->id,
            'name' => 'Pending Co',
            'slug' => 'pending-co-3',
            'verified' => false,
            'verification_status' => Company::STATUS_PENDING,
            'onboarding_step' => 3,
            'onboarding_completed_at' => now(),
        ]);

        $this->actingAs($employer)
            ->get(route('employer.jobs.index'))
            ->assertOk()
            ->assertSee('Company verification required')
            ->assertSee('You can’t post a job yet.');
    }

    public function test_employer_with_verified_company_can_store_job_posting(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);

        $company = Company::create([
            'user_id' => $employer->id,
            'name' => 'Approved Co',
            'slug' => 'approved-co',
            'verified' => true,
            'verification_status' => Company::STATUS_APPROVED,
            'onboarding_step' => 3,
            'onboarding_completed_at' => now(),
        ]);

        $this->actingAs($employer)
            ->post(route('jobs.store'), [
                'company_id' => $company->id,
                'title' => 'Approved Job',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('job_postings', [
            'company_id' => $company->id,
            'title' => 'Approved Job',
        ]);
    }
}
