<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnsureEmployerCompanyApprovedTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_without_company_is_redirected_to_create_company(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($employer)->get('/applicants');

        $response->assertRedirect(route('employer.companies.create'));
        $response->assertSessionHas('error');
    }

    public function test_employer_with_pending_company_cannot_view_applicants(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'two_factor_confirmed_at' => now(),
        ]);

        $company = Company::factory()->pending()->forUser($employer)->create();

        $response = $this->actingAs($employer)->get('/applicants');

        $response->assertRedirect(route('employer.companies.edit', $company));
        $response->assertSessionHas('error');
    }

    public function test_employer_with_rejected_company_cannot_view_applicants(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'two_factor_confirmed_at' => now(),
        ]);

        $company = Company::factory()->rejected()->forUser($employer)->create();

        $response = $this->actingAs($employer)->get('/applicants');

        $response->assertRedirect(route('employer.companies.edit', $company));
        $response->assertSessionHas('error');
    }

    public function test_employer_with_approved_company_can_view_applicants(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'two_factor_confirmed_at' => now(),
        ]);

        Company::factory()->approved()->forUser($employer)->create();

        $response = $this->actingAs($employer)->get('/applicants');

        $response->assertStatus(200);
    }

    public function test_non_employer_cannot_access_applicants(): void
    {
        $applicant = User::factory()->create([
            'role' => 'applicant',
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($applicant)->get('/applicants');

        $response->assertRedirect(route('applicant.dashboard'));
    }

    public function test_employer_with_pending_company_cannot_access_saved_applicants(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'two_factor_confirmed_at' => now(),
        ]);

        $company = Company::factory()->pending()->forUser($employer)->create();

        $response = $this->actingAs($employer)->get('/employer/saved-applicants');

        $response->assertRedirect(route('employer.companies.edit', $company));
    }

    public function test_employer_with_approved_company_can_access_saved_applicants(): void
    {
        $employer = User::factory()->create([
            'role' => 'employer',
            'two_factor_confirmed_at' => now(),
        ]);

        Company::factory()->approved()->forUser($employer)->create();

        $response = $this->actingAs($employer)->get('/employer/saved-applicants');

        $response->assertStatus(200);
    }
}
