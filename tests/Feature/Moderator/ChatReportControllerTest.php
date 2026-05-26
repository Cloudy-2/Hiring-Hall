<?php

namespace Tests\Feature\Moderator;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_access_chat_reports_index(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator', 'email_verified_at' => now()]);

        $response = $this->actingAs($moderator)->get(route('moderator.chat-reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Reported Chat Messages');
    }

    public function test_guest_cannot_access_chat_reports_index(): void
    {
        $response = $this->get(route('moderator.chat-reports.index'));

        $response->assertRedirect();
    }

    public function test_applicant_cannot_access_chat_reports_index(): void
    {
        $applicant = User::factory()->create(['role' => 'applicant', 'email_verified_at' => now()]);

        $response = $this->actingAs($applicant)->get(route('moderator.chat-reports.index'));

        $response->assertStatus(403);
    }
}
