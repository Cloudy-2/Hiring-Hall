<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserThemePreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests are redirected to login for the authenticated endpoint.
     */
    public function test_guest_cannot_store_theme_preference(): void
    {
        $response = $this->post(route('user.theme.store'), [
            'theme_mode' => 'dark',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_store_theme_preference(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'dark',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.theme_mode', 'dark');

        $this->assertDatabaseHas('user_theme_preferences', [
            'user_id' => $user->id,
            'theme_mode' => 'dark',
        ]);
    }

    public function test_invalid_theme_mode_is_rejected(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'neon',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['theme_mode']);
    }

    public function test_updating_theme_keeps_single_row_per_user(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'dark',
        ])->assertOk();

        $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'light',
        ])->assertOk();

        $this->assertSame(1, UserThemePreference::query()->where('user_id', $user->id)->count());
        $this->assertSame('light', $user->fresh()->themePreference?->theme_mode);
    }

    public function test_user_cannot_overwrite_another_users_preference(): void
    {
        $owner = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $other = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        UserThemePreference::query()->create([
            'user_id' => $owner->id,
            'theme_mode' => 'dark',
        ]);

        $this->actingAs($other)->postJson(route('user.theme.store'), [
            'theme_mode' => 'light',
        ])->assertOk();

        $this->assertSame('dark', $owner->fresh()->themePreference?->theme_mode);
        $this->assertSame('light', $other->fresh()->themePreference?->theme_mode);
    }

    public function test_authenticated_user_can_store_nav_switcher_theme_styles(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'dark',
            'theme_styles' => [
                'header_style' => 'gradient',
                'menu_style' => 'transparent',
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.theme_styles.header_style', 'gradient');
        $response->assertJsonPath('data.theme_styles.menu_style', 'transparent');

        $this->assertDatabaseHas('user_theme_preferences', [
            'user_id' => $user->id,
            'theme_mode' => 'dark',
        ]);

        $themePreference = $user->fresh()->themePreference;
        $this->assertSame('gradient', data_get($themePreference?->theme_styles, 'header_style'));
        $this->assertSame('transparent', data_get($themePreference?->theme_styles, 'menu_style'));
    }

    public function test_updating_one_theme_style_preserves_existing_other_style(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'dark',
            'theme_styles' => [
                'header_style' => 'color',
                'menu_style' => 'gradient',
            ],
        ])->assertOk();

        $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'light',
            'theme_styles' => [
                'menu_style' => 'transparent',
            ],
        ])->assertOk();

        $themePreference = $user->fresh()->themePreference;
        $this->assertSame('light', $themePreference?->theme_mode);
        $this->assertSame('color', data_get($themePreference?->theme_styles, 'header_style'));
        $this->assertSame('transparent', data_get($themePreference?->theme_styles, 'menu_style'));
    }

    public function test_authenticated_user_can_store_layout_settings(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->postJson(route('user.theme.store'), [
            'theme_mode' => 'dark',
            'layout_settings' => [
                'menu_version' => 'v2',
                'nav_layout' => 'horizontal',
                'vertical_style' => 'closed',
                'width' => 'boxed',
                'header_position' => 'fixed',
                'menu_position' => 'scrollable',
                'loader' => 'disable',
            ],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.layout_settings.menu_version', 'v2');
        $response->assertJsonPath('data.layout_settings.width', 'boxed');

        $themePreference = $user->fresh()->themePreference;
        $this->assertSame('v2', data_get($themePreference?->layout_settings, 'menu_version'));
        $this->assertSame('horizontal', data_get($themePreference?->layout_settings, 'nav_layout'));
        $this->assertSame('closed', data_get($themePreference?->layout_settings, 'vertical_style'));
        $this->assertSame('boxed', data_get($themePreference?->layout_settings, 'width'));
        $this->assertSame('fixed', data_get($themePreference?->layout_settings, 'header_position'));
        $this->assertSame('scrollable', data_get($themePreference?->layout_settings, 'menu_position'));
        $this->assertSame('disable', data_get($themePreference?->layout_settings, 'loader'));
    }

    public function test_reset_sets_light_mode_and_clears_layout_settings(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        UserThemePreference::query()->create([
            'user_id' => $user->id,
            'theme_mode' => 'dark',
            'theme_styles' => [
                'header_style' => 'gradient',
                'menu_style' => 'transparent',
            ],
            'layout_settings' => [
                'menu_version' => 'v2',
                'nav_layout' => 'horizontal',
            ],
        ]);

        $response = $this->actingAs($user)->postJson(route('user.theme.store'), [
            'reset' => true,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.theme_mode', 'light');
        $response->assertJsonPath('data.layout_settings', null);

        $themePreference = $user->fresh()->themePreference;
        $this->assertSame('light', $themePreference?->theme_mode);
        $this->assertNull($themePreference?->layout_settings);
    }
}
