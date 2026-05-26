<?php

namespace Tests\Feature\Api\Applicant;

use App\Models\ApplicantProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'applicant']);
        $this->token = $this->user->createToken('test', ['applicant'])->plainTextToken;
    }

    public function test_get_onboarding_status_returns_current_step(): void
    {
        $profile = ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 3,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('GET', '/api/v1/applicant/onboarding');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 3,
                    'onboarding_completed' => false,
                    'onboarding_completed_at' => null,
                ],
            ]);
    }

    public function test_advance_onboarding_step_updates_profile(): void
    {
        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', ['step' => 2]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 2,
                    'onboarding_completed' => false,
                ],
            ]);

        $this->user->refresh();
        $this->assertEquals(2, $this->user->applicantProfile->onboarding_step);
    }

    public function test_advancing_to_step_5_marks_onboarding_complete(): void
    {
        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 4,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', ['step' => 5]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 5,
                    'onboarding_completed' => true,
                ],
            ]);

        $this->user->refresh();
        $this->assertNotNull($this->user->applicantProfile->onboarding_completed_at);
    }

    public function test_requires_valid_step_integer(): void
    {
        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', ['step' => 'invalid']);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_step_must_be_between_1_and_10(): void
    {
        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 1,
        ]);

        // Step too low
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', ['step' => 0]);
        $response->assertStatus(422);

        // Step too high
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', ['step' => 11]);
        $response->assertStatus(422);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->json('GET', '/api/v1/applicant/onboarding');
        $response->assertStatus(401);

        $response = $this->json('POST', '/api/v1/applicant/onboarding', ['step' => 2]);
        $response->assertStatus(401);
    }

    public function test_employer_cannot_advance_applicant_onboarding(): void
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $employerToken = $employer->createToken('test', ['employer'])->plainTextToken;

        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 1,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$employerToken}")
            ->json('POST', '/api/v1/applicant/onboarding', ['step' => 2]);

        $response->assertStatus(403); // Forbidden (ability:applicant middleware)
    }

    public function test_terms_agreed_records_consent_on_user(): void
    {
        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 4,
        ]);

        $this->assertNull($this->user->terms_shared_data_at);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', [
                'step' => 5,
                'terms_agreed' => true,
            ]);

        $response->assertStatus(200);

        $this->user->refresh();
        $this->assertNotNull($this->user->terms_shared_data_at);
    }

    public function test_cannot_overwrite_existing_consent_timestamp(): void
    {
        $originalTime = now()->subHours(2);
        $this->user->update(['terms_shared_data_at' => $originalTime]);

        ApplicantProfile::create([
            'user_id' => $this->user->id,
            'display_name' => $this->user->name,
            'onboarding_step' => 4,
        ]);

        $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/applicant/onboarding', [
                'step' => 5,
                'terms_agreed' => true,
            ]);

        $this->user->refresh();
        $this->assertEquals($originalTime->format('Y-m-d H:i'), $this->user->terms_shared_data_at->format('Y-m-d H:i'));
    }
}
