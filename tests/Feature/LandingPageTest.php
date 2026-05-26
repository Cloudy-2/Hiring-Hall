<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Hiring Hall', escape: false)
            ->assertSee('support@hillbcs.com', escape: false);
    }

    public function test_newsletter_subscription_redirects_back_with_flash_message(): void
    {
        $this->from('/')
            ->post(route('newsletter.subscribe'), [
                'email' => 'test@example.com',
            ])
            ->assertRedirect('/')
            ->assertSessionHas('newsletter');
    }
}
