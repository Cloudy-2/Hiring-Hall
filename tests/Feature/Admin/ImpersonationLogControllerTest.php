<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpersonationLogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_impersonation_logs_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.impersonation-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Impersonation Logs');
    }

    public function test_admin_can_export_impersonation_logs(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->get(route('admin.impersonation-logs.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_moderator_cannot_access_impersonation_logs_index(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator', 'email_verified_at' => now()]);

        $response = $this->actingAs($moderator)->get(route('admin.impersonation-logs.index'));

        $response->assertStatus(403);
    }
}
