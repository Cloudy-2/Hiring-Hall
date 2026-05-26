<?php

namespace Tests\Feature;

use App\Models\ReleaseNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReleaseNotesTest extends TestCase
{
    use RefreshDatabase;

    public function test_moderator_can_access_release_notes_index(): void
    {
        $moderator = User::factory()->create(['role' => 'moderator', 'email_verified_at' => now()]);

        $response = $this->actingAs($moderator)->get(route('moderator.release-notes.index'));

        $response->assertStatus(200);
        $response->assertSee('Release Notes');
    }

    public function test_guest_redirected_from_public_release_notes(): void
    {
        $response = $this->get(route('release-notes.index'));

        $response->assertRedirect();
    }

    public function test_authenticated_user_sees_published_release_notes(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        ReleaseNote::create([
            'version' => '1.0.0',
            'title' => 'Initial release',
            'body' => 'Welcome to the platform.',
            'released_at' => now(),
            'is_published' => true,
            'created_by' => null,
        ]);

        $response = $this->actingAs($user)->get(route('release-notes.index'));

        $response->assertStatus(200);
        $response->assertSee('1.0.0');
        $response->assertSee('Initial release');
        $response->assertSee('Welcome to the platform.');
    }

    public function test_public_page_does_not_show_unpublished_release_notes(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        ReleaseNote::create([
            'version' => '2.0.0',
            'title' => 'Draft note',
            'body' => 'Not visible yet.',
            'released_at' => now(),
            'is_published' => false,
            'created_by' => null,
        ]);

        $response = $this->actingAs($user)->get(route('release-notes.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Draft note');
    }
}
