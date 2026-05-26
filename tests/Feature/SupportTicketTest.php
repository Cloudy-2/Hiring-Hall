<?php

namespace Tests\Feature;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_tickets_list(): void
    {
        $response = $this->get(route('tickets.list'));
        $response->assertRedirect();
    }

    public function test_authenticated_user_sees_tickets_list(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('tickets.list'));
        $response->assertStatus(200);
        $response->assertSee('Support Tickets');
        $response->assertSee('New Ticket');
    }

    public function test_authenticated_user_sees_create_form(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('tickets.create'));
        $response->assertStatus(200);
        $response->assertSee('New Support Ticket');
        $response->assertSee('Subject');
        $response->assertSee('Message');
    }

    public function test_authenticated_user_can_submit_ticket(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'subject' => 'Login issue',
            'message' => 'I cannot log in to my account.',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'subject' => 'Login issue',
            'message' => 'I cannot log in to my account.',
            'status' => 'pending',
        ]);
    }

    public function test_validation_fails_without_subject_and_message(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'subject' => '',
            'message' => '',
        ]);
        $response->assertSessionHasErrors(['subject', 'message']);
    }

    public function test_user_can_view_own_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('tickets.show', $ticket));
        $response->assertStatus(200);
        $response->assertSee($ticket->subject);
        $response->assertSee($ticket->message);
    }

    public function test_user_cannot_view_other_users_ticket(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $ticket = SupportTicket::factory()->create(['user_id' => $owner->id]);
        $response = $this->actingAs($other)->get(route('tickets.show', $ticket));
        $response->assertStatus(403);
    }

    public function test_user_can_add_reply_to_own_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = SupportTicket::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        $response = $this->actingAs($user)->post(route('tickets.reply', $ticket), [
            'message' => 'Here is more information.',
        ]);
        $response->assertRedirect(route('tickets.show', $ticket));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('support_ticket_replies', [
            'support_ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Here is more information.',
        ]);
    }
}
