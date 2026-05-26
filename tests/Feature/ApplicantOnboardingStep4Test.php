<?php

namespace Tests\Feature;

use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicantOnboardingStep4Test extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_onboarding_step_4_requires_terms_agreed(): void
    {
        $user = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
            'onboarding_step' => 3,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('applicant.onboarding.store', ['step' => 4]));

        $response->assertSessionHasErrors(['terms_agreed']);
    }

    public function test_applicant_onboarding_step_4_completes_with_terms_agreed(): void
    {
        $user = User::factory()->create(['role' => 'applicant']);
        ApplicantProfile::create([
            'user_id' => $user->id,
            'display_name' => $user->name,
            'onboarding_step' => 3,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->actingAs($user)->post(route('applicant.onboarding.store', ['step' => 4]), [
            'terms_agreed' => '1',
        ]);

        $response->assertRedirect(route('applicant.dashboard'));

        $user->applicantProfile->refresh();
        $user->refresh();
        $this->assertNotNull($user->applicantProfile->onboarding_completed_at);
        $this->assertNotNull($user->terms_shared_data_at);
    }
}
