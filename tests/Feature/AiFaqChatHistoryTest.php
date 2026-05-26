<?php

namespace Tests\Feature;

use App\Models\AiFaqConversation;
use App\Models\AiFaqMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AiFaqChatHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_delete_own_ai_chat_history(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $conversation = AiFaqConversation::create([
            'user_id' => $user->id,
            'uuid' => (string) Str::uuid(),
            'title' => 'Benefits question',
        ]);

        AiFaqMessage::create([
            'ai_faq_conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => 'What benefits are available?',
            'metadata' => null,
        ]);

        AiFaqMessage::create([
            'ai_faq_conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => 'Here is the benefits overview.',
            'metadata' => null,
        ]);

        $response = $this->actingAs($user)->delete(route('ai.faq-chat.conversation.destroy', $conversation->uuid));

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'message' => 'Conversation deleted.',
        ]);

        $this->assertDatabaseMissing('ai_faq_conversations', [
            'id' => $conversation->id,
        ]);

        $this->assertDatabaseMissing('ai_faq_messages', [
            'ai_faq_conversation_id' => $conversation->id,
        ]);
    }

    public function test_authenticated_user_cannot_delete_another_users_ai_chat_history(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $conversation = AiFaqConversation::create([
            'user_id' => $owner->id,
            'uuid' => (string) Str::uuid(),
            'title' => 'Private conversation',
        ]);

        $response = $this->actingAs($other)->delete(route('ai.faq-chat.conversation.destroy', $conversation->uuid));

        $response->assertNotFound();
        $this->assertDatabaseHas('ai_faq_conversations', [
            'id' => $conversation->id,
        ]);
    }
}
