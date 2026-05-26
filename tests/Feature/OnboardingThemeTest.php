<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_onboarding_uses_landing_theme_header(): void
    {
        $user = User::factory()->create([
            'role' => 'applicant',
        ]);

        $this->actingAs($user)
            ->get(route('applicant.onboarding.show', ['step' => 1]))
            ->assertOk()
            ->assertSee('Hiring Hall', escape: false)
            ->assertSee('assets/logo.png', escape: false)
            ->assertSee('background: #ffffff;', escape: false);
    }

    public function test_employer_onboarding_uses_landing_theme_header(): void
    {
        $user = User::factory()->create([
            'role' => 'employer',
        ]);

        $this->actingAs($user)
            ->get(route('employer.onboarding.show', ['step' => 1]))
            ->assertOk()
            ->assertSee('Hiring Hall', escape: false)
            ->assertSee('assets/logo.png', escape: false)
            ->assertSee('background: #ffffff;', escape: false);
    }
}
