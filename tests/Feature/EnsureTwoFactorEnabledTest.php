<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class EnsureTwoFactorEnabledTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_without_2fa_are_redirected_to_setup_page(): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two factor authentication is not enabled.');
        }

        $user = User::factory()->create([
            'role' => 'applicant',
            'two_factor_confirmed_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('two-factor.setup'));
        $response->assertSessionHas('warning');
    }

    public function test_users_with_2fa_can_access_protected_routes(): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two factor authentication is not enabled.');
        }

        $user = User::factory()->create([
            'role' => 'applicant',
            'two_factor_secret' => encrypt('test-secret'),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('applicant.dashboard'));
    }

    public function test_2fa_setup_page_is_accessible_without_2fa(): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two factor authentication is not enabled.');
        }

        $user = User::factory()->create([
            'role' => 'applicant',
            'two_factor_confirmed_at' => null,
        ]);

        $response = $this->actingAs($user)->get(route('two-factor.setup'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.two-factor-setup');
    }

    public function test_profile_page_is_accessible_without_2fa(): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two factor authentication is not enabled.');
        }

        $user = User::factory()->create([
            'role' => 'applicant',
            'two_factor_confirmed_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertStatus(200);
    }

    public function test_logout_is_accessible_without_2fa(): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two factor authentication is not enabled.');
        }

        $user = User::factory()->create([
            'role' => 'applicant',
            'two_factor_confirmed_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
    }

    public function test_unauthenticated_users_are_not_affected(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
