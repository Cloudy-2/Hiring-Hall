<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerOnboardingStep4Test extends TestCase
{
    use RefreshDatabase;

    public function test_employer_onboarding_step_4_requires_terms_agreed(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        Company::factory()->forUser($user)->create([
            'onboarding_step' => 4,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('employer.onboarding.store', ['step' => 4]));

        $response->assertSessionHasErrors(['terms_agreed']);
    }

    public function test_employer_onboarding_step_4_completes_with_terms_agreed(): void
    {
        $user = User::factory()->create(['role' => 'employer']);
        Company::factory()->forUser($user)->create([
            'onboarding_step' => 4,
            'onboarding_completed_at' => null,
            'terms_agreed_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('employer.onboarding.store', ['step' => 4]), [
            'terms_agreed' => '1',
        ]);

        $response->assertRedirect(route('employer.dashboard'));

        $user->company->refresh();
        $user->refresh();
        $this->assertNotNull($user->company->onboarding_completed_at);
        $this->assertNotNull($user->terms_shared_data_at);
    }
}
