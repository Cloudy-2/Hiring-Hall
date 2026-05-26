<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\DropdownOptionsSeeder::class);
    }

    public function test_jobs_page_loads(): void
    {
        $response = $this->get('/jobs');
        $response->assertStatus(200);
    }

    public function test_jobs_page_shows_open_jobs(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Test Job Position',
            'slug' => 'test-job-position',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs');
        $response->assertStatus(200);
        $response->assertSee('Test Job Position');
    }

    public function test_jobs_page_hides_closed_jobs(): void
    {
        $company = Company::create([
            'name' => 'Test Company',
            'slug' => 'test-company-2',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Open Job',
            'slug' => 'open-job',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Closed Job',
            'slug' => 'closed-job',
            'status' => 'closed',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs');
        $response->assertStatus(200);
        $response->assertSee('Open Job');
        $response->assertDontSee('Closed Job');
    }

    public function test_jobs_filter_by_industry_type(): void
    {
        $company = Company::create([
            'name' => 'Tech Company',
            'slug' => 'tech-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Tech Job',
            'slug' => 'tech-job',
            'status' => 'open',
            'industry_type' => 'research_development',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Accounting Job',
            'slug' => 'accounting-job',
            'status' => 'open',
            'industry_type' => 'accounting',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs?industry_type[]=research_development');
        $response->assertStatus(200);
        $response->assertSee('Tech Job');
        $response->assertDontSee('Accounting Job');
    }

    public function test_jobs_filter_by_multiple_industry_types_uses_or_logic(): void
    {
        $company = Company::create([
            'name' => 'Multi Company',
            'slug' => 'multi-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Research Job',
            'slug' => 'research-job',
            'status' => 'open',
            'industry_type' => 'research_development',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Accounting Job',
            'slug' => 'accounting-job-2',
            'status' => 'open',
            'industry_type' => 'accounting',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Marketing Job',
            'slug' => 'marketing-job',
            'status' => 'open',
            'industry_type' => 'marketing',
            'posted_at' => now(),
        ]);

        // Filter by research OR accounting - should show both, not marketing
        $response = $this->get('/jobs?industry_type[]=research_development&industry_type[]=accounting');
        $response->assertStatus(200);
        $response->assertSee('Research Job');
        $response->assertSee('Accounting Job');
        $response->assertDontSee('Marketing Job');
    }

    public function test_jobs_filter_by_employment_type(): void
    {
        $company = Company::create([
            'name' => 'Employment Company',
            'slug' => 'employment-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Full Time Position',
            'slug' => 'full-time-position',
            'status' => 'open',
            'employment_type' => 'full_time',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Part Time Position',
            'slug' => 'part-time-position',
            'status' => 'open',
            'employment_type' => 'part_time',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs?employment_type[]=full_time');
        $response->assertStatus(200);
        $response->assertSee('Full Time Position');
        $response->assertDontSee('Part Time Position');
    }

    public function test_jobs_filter_by_recruiter_type(): void
    {
        $company = Company::create([
            'name' => 'Recruiter Company',
            'slug' => 'recruiter-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Direct Hire Job',
            'slug' => 'direct-hire-job',
            'status' => 'open',
            'recruiter_type' => 'direct',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Agency Job',
            'slug' => 'agency-job',
            'status' => 'open',
            'recruiter_type' => 'agency',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs?recruiter_type[]=direct');
        $response->assertStatus(200);
        $response->assertSee('Direct Hire Job');
        $response->assertDontSee('Agency Job');
    }

    public function test_jobs_filter_by_vacancies(): void
    {
        $company = Company::create([
            'name' => 'Vacancy Company',
            'slug' => 'vacancy-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Small Team Job',
            'slug' => 'small-team-job',
            'status' => 'open',
            'vacancies' => 5,
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Large Team Job',
            'slug' => 'large-team-job',
            'status' => 'open',
            'vacancies' => 25,
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs?vacancies[]=0-10');
        $response->assertStatus(200);
        $response->assertSee('Small Team Job');
        $response->assertDontSee('Large Team Job');
    }

    public function test_jobs_search_by_keyword(): void
    {
        $company = Company::create([
            'name' => 'Search Company',
            'slug' => 'search-company',
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Virtual Assistant Role',
            'slug' => 'virtual-assistant-role',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Data Entry Clerk',
            'slug' => 'data-entry-clerk',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs?keyword=Virtual');
        $response->assertStatus(200);
        $response->assertSee('Virtual Assistant Role');
        $response->assertDontSee('Data Entry Clerk');
    }

    public function test_employer_dashboard_requires_auth(): void
    {
        $response = $this->get('/employer/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_employer_dashboard_loads_for_employer(): void
    {
        $user = User::factory()->create(['role' => 'employer']);

        $response = $this->actingAs($user)->get('/employer/dashboard');
        $response->assertStatus(200);
    }

    public function test_job_details_page_loads(): void
    {
        $company = Company::create([
            'name' => 'Details Company',
            'slug' => 'details-company',
        ]);

        $job = JobPosting::create([
            'company_id' => $company->id,
            'title' => 'Detailed Job',
            'slug' => 'detailed-job',
            'status' => 'open',
            'posted_at' => now(),
        ]);

        $response = $this->get('/jobs/'.$job->slug);
        $response->assertStatus(200);
        $response->assertSee('Detailed Job');
    }
}
