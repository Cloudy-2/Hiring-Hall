<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_settings_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertSee('Application Settings');
    }

    public function test_admin_can_update_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        Setting::query()->create(['key' => 'test_key', 'value' => 'old']);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'settings' => [
                'test_key' => 'new_value',
            ],
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');
        $this->assertSame('new_value', Setting::get('test_key'));
    }

    public function test_moderator_cannot_access_settings_index(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator', 'email_verified_at' => now()]);

        $response = $this->actingAs($moderator)->get(route('admin.settings.index'));

        $response->assertStatus(403);
    }
}
