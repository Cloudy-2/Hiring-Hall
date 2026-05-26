<?php

namespace Tests\Feature\Api\Employer;

use App\Models\Company;
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
        $this->user = User::factory()->create(['role' => 'employer']);
        $this->token = $this->user->createToken('test', ['employer'])->plainTextToken;
    }

    public function test_get_onboarding_status_returns_current_step(): void
    {
        $company = Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_step' => 2,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('GET', '/api/v1/employer/onboarding');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 2,
                    'onboarding_completed' => false,
                    'onboarding_completed_at' => null,
                    'verification_status' => 'pending',
                ],
            ]);
    }

    public function test_advance_onboarding_step_updates_company(): void
    {
        Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_step' => 1,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 2]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 2,
                    'onboarding_completed' => false,
                ],
            ]);

        $this->user->refresh();
        $this->assertEquals(2, $this->user->company->onboarding_step);
    }

    public function test_advancing_to_step_4_marks_onboarding_complete(): void
    {
        Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_step' => 3,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 4]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 4,
                    'onboarding_completed' => true,
                ],
            ]);

        $this->user->refresh();
        $this->assertNotNull($this->user->company->onboarding_completed_at);
    }

    public function test_auto_creates_company_if_missing(): void
    {
        $this->assertNull($this->user->company);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 1]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'onboarding_step' => 1,
                    'onboarding_completed' => false,
                ],
            ]);

        $this->user->refresh();
        $this->assertNotNull($this->user->company);
        $this->assertNotNull($this->user->company->slug);
    }

    public function test_auto_created_company_name_based_on_user_name(): void
    {
        $this->user->update(['name' => 'John Doe']);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 1]);

        $response->assertStatus(200);

        $this->user->refresh();
        $this->assertEquals("John Doe's Company", $this->user->company->name);
    }

    public function test_auto_created_company_generates_unique_slug(): void
    {
        // Create a company with the slug that would normally be generated
        Company::factory()->create([
            'slug' => $this->user->name."'s Company",
        ]);

        $this->user->update(['name' => $this->user->name]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 1]);

        $response->assertStatus(200);

        $this->user->refresh();
        $this->assertNotNull($this->user->company->slug);
        // Slug should be unique
        $this->assertEquals(1, Company::where('slug', $this->user->company->slug)->count());
    }

    public function test_requires_valid_step_integer(): void
    {
        Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_step' => 1,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 'invalid']);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_step_must_be_between_1_and_10(): void
    {
        Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_step' => 1,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 0]);
        $response->assertStatus(422);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 11]);
        $response->assertStatus(422);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->json('GET', '/api/v1/employer/onboarding');
        $response->assertStatus(401);

        $response = $this->json('POST', '/api/v1/employer/onboarding', ['step' => 2]);
        $response->assertStatus(401);
    }

    public function test_applicant_cannot_advance_employer_onboarding(): void
    {
        $applicant = User::factory()->create(['role' => 'applicant']);
        $applicantToken = $applicant->createToken('test', ['applicant'])->plainTextToken;
        Company::factory()->create([
            'user_id' => $this->user->id,
            'onboarding_step' => 1,
            'onboarding_completed_at' => null,
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$applicantToken}")
            ->json('POST', '/api/v1/employer/onboarding', ['step' => 2]);

        $response->assertStatus(403); // Forbidden (ability:employer middleware)
    }

    public function test_show_returns_not_found_for_employer_without_company(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->json('GET', '/api/v1/employer/onboarding');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Company profile not found.',
            ]);
    }
}
