<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFaqPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_page_returns_200_when_authenticated(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('faq.index'));

        $response->assertStatus(200);
        $response->assertSee('Frequently Asked Questions');
    }

    public function test_faq_page_redirects_guest_to_login(): void
    {
        $response = $this->get(route('faq.index'));

        $response->assertRedirect();
    }

    public function test_faq_page_shows_empty_state_when_no_faqs(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('faq.index'));

        $response->assertStatus(200);
        $response->assertSee('No FAQs have been added yet');
    }

    public function test_faq_page_shows_active_faqs_from_database(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Faq::create([
            'question' => 'How do I reset my password?',
            'answer' => 'Use the forgot password link on the login page.',
            'category' => 'General',
            'sort_order' => 0,
            'is_active' => true,
            'created_by' => null,
        ]);

        $response = $this->actingAs($user)->get(route('faq.index'));

        $response->assertStatus(200);
        $response->assertSee('How do I reset my password?');
        $response->assertSee('Use the forgot password link on the login page.');
    }

    public function test_faq_page_does_not_show_inactive_faqs(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Faq::create([
            'question' => 'Hidden question',
            'answer' => 'Hidden answer',
            'category' => null,
            'sort_order' => 0,
            'is_active' => false,
            'created_by' => null,
        ]);

        $response = $this->actingAs($user)->get(route('faq.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Hidden question');
    }
}
