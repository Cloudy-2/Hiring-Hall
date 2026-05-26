<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_applicant_creates_user_and_profile(): void
    {
        $response = $this->json('POST', '/api/v1/auth/register/applicant', [
            'name' => 'John Applicant',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'email' => 'john@example.com',
                ],
            ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('applicant', $user->role);
        $this->assertNotNull($user->applicantProfile);
        $this->assertEquals(1, $user->applicantProfile->onboarding_step);
    }

    public function test_register_applicant_sends_verification_email(): void
    {
        $response = $this->json('POST', '/api/v1/auth/register/applicant', [
            'name' => 'John Applicant',
            'email' => 'john@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at); // Not verified yet
    }

    public function test_register_applicant_requires_valid_password(): void
    {
        $response = $this->json('POST', '/api/v1/auth/register/applicant', [
            'name' => 'John Applicant',
            'email' => 'john@example.com',
            'password' => 'short', // Too short
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_register_employer_creates_user_and_company(): void
    {
        $response = $this->json('POST', '/api/v1/auth/register/employer', [
            'name' => 'Jane Employer',
            'email' => 'jane@example.com',
            'company_name' => 'Acme Corp',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'email' => 'jane@example.com',
                ],
            ]);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('employer', $user->role);
        $this->assertNotNull($user->company);
        $this->assertEquals('Acme Corp', $user->company->name);
    }

    public function test_register_employer_without_company_name(): void
    {
        $response = $this->json('POST', '/api/v1/auth/register/employer', [
            'name' => 'Jane Employer',
            'email' => 'jane@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->company); // No company created
    }

    public function test_login_requires_verified_email(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'applicant',
            'email_verified_at' => null, // Not verified
        ]);

        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'Password123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Your email address is not verified. Please check your inbox.',
            ]);
    }

    public function test_login_with_verified_email_issues_token(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'applicant',
        ]);
        $user->markEmailAsVerified();

        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'Password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'token_type' => 'Bearer',
                    'abilities' => ['applicant'],
                ],
            ])
            ->assertJsonStructure([
                'data' => ['token', 'token_type', 'abilities', 'user'],
            ]);

        $this->assertNotNull($response->json('data.token'));
    }

    public function test_login_employer_receives_employer_ability(): void
    {
        $user = User::factory()->create([
            'email' => 'employer@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'employer',
        ]);
        $user->markEmailAsVerified();

        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'employer@example.com',
            'password' => 'Password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'abilities' => ['employer'],
                ],
            ]);
    }

    public function test_login_admin_receives_both_abilities(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'admin',
        ]);
        $user->markEmailAsVerified();

        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'Password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'abilities' => ['applicant', 'employer'],
                ],
            ]);
    }

    public function test_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'applicant',
        ]);

        $response = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_login_revokes_existing_token_for_same_device(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'applicant',
        ]);
        $user->markEmailAsVerified();

        // First login
        $response1 = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'Password123',
            'device_name' => 'mobile',
        ]);

        $token1 = $response1->json('data.token');
        $this->assertEquals(1, $user->tokens()->count());

        // Second login with same device name
        $response2 = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'Password123',
            'device_name' => 'mobile',
        ]);

        $token2 = $response2->json('data.token');
        $this->assertNotEquals($token1, $token2);
        $this->assertEquals(1, $user->tokens()->count()); // Still just 1 token
    }

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Password123'),
            'role' => 'applicant',
        ]);
        $user->markEmailAsVerified();

        $loginResponse = $this->json('POST', '/api/v1/auth/login', [
            'email' => 'john@example.com',
            'password' => 'Password123',
        ]);

        $token = $loginResponse->json('data.token');
        $this->assertEquals(1, $user->tokens()->count());

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->json('POST', '/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertEquals(0, $user->tokens()->count());
    }

    public function test_forgot_password_sends_reset_email(): void
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->json('POST', '/api/v1/auth/forgot-password', [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_reset_password_revokes_all_tokens(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('OldPassword123'),
        ]);
        $user->markEmailAsVerified();

        // Create a token
        $user->createToken('device', ['applicant']);
        $this->assertEquals(1, $user->tokens()->count());

        // Generate password reset token
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        $response = $this->json('POST', '/api/v1/auth/reset-password', [
            'email' => 'john@example.com',
            'token' => $token,
            'password' => 'NewPassword123',
            'password_confirmation' => 'NewPassword123',
        ]);

        $response->assertStatus(200);

        // Verify token was revoked
        $this->assertEquals(0, $user->tokens()->count());

        // Verify password was changed
        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123', $user->password));
    }
}
