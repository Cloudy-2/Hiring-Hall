<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\AiEscalation;
use App\Models\AiFaqConversation;
use App\Models\AiFaqFeedback;
use App\Models\AiFaqMessage;
use App\Services\AI\AIChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiFaqChatController extends Controller
{
    protected function userFirstNameForAi(mixed $name): ?string
    {
        if (! is_string($name)) {
            return null;
        }
        $name = trim($name);
        if ($name === '') {
            return null;
        }
        $parts = preg_split('/\s+/u', $name, 2, PREG_SPLIT_NO_EMPTY);

        return isset($parts[0]) && $parts[0] !== '' ? Str::limit($parts[0], 40, '') : null;
    }

    public function info(Request $request): JsonResponse
    {
        if (! config('faq_chat.allow_get_discovery')) {
            return response()->json([
                'message' => 'Method Not Allowed. Use POST with JSON: { "message": "..." }.',
            ], 405)->header('Allow', 'POST');
        }

        $base = rtrim($request->getSchemeAndHttpHost().$request->getBaseUrl(), '/');

        return response()->json([
            'ok' => true,
            'environment' => app()->environment(),
            'urls' => [
                'faq_chat_get' => url('/ai/faq-chat'),
                'faq_chat_post' => url('/ai/faq-chat'),
                'agent_chat_post' => url('/ai/agent-chat'),
                'escalate_post' => url('/ai/faq-chat/escalate'),
                'conversations_get' => url('/ai/faq-chat/conversations'),
            ],
            'faq_chat_config' => [
                'relative_urls' => (bool) config('faq_chat.relative_urls'),
                'api_origin' => config('faq_chat.api_origin') ?: null,
                'page_origin' => $base,
            ],
            'faq_chat_post_body' => [
                'message' => 'string (required)',
                'conversation_uuid' => 'uuid (optional)',
            ],
            'agent_chat_post_body' => [
                'message' => 'string (required)',
                'agent' => 'douglas|kent|sofia|saffi|kimberly|arnel (optional, default kent)',
            ],
            'escalate_post_body' => [
                'description' => 'string (required)',
                'name' => 'string (optional)',
                'email' => 'email (optional)',
                'conversation_uuid' => 'uuid (optional)',
                'conversation_json' => 'array (optional)',
                'agent' => 'string (optional)',
            ],
        ]);
    }

    public function agentChat(Request $request, AIChatService $aiChatService): JsonResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'agent' => ['nullable', 'in:douglas,kent,sofia,saffi,kimberly,arnel'],
        ]);

        $reply = $aiChatService->chatWithRag($data['message'], $data['agent'] ?? 'kent');

        return response()->json(['reply' => $reply]);
    }

    public function escalate(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'description' => ['required', 'string', 'max:5000'],
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255'],
            'agent' => ['nullable', 'string', 'max:64'],
            'conversation_uuid' => ['nullable', 'uuid'],
            'conversation_json' => ['nullable', 'array'],
        ]);

        $name = $data['name'] ?? $user->name;
        $email = $data['email'] ?? $user->email;
        if (! is_string($email) || trim($email) === '') {
            return response()->json(['message' => 'A contact email is required.'], 422);
        }

        $conversationPayload = $data['conversation_json'] ?? null;
        if ($conversationPayload === null && ! empty($data['conversation_uuid'])) {
            $conv = AiFaqConversation::query()
                ->where('user_id', $user->id)
                ->where('uuid', $data['conversation_uuid'])
                ->first();
            if ($conv) {
                $conversationPayload = $conv->messages()->orderBy('id')->get()->map(fn (AiFaqMessage $m) => [
                    'role' => $m->role,
                    'content' => $m->content,
                    'metadata' => $m->metadata,
                ])->all();
            }
        }

        $row = AiEscalation::create([
            'user_id' => $user->id,
            'agent' => $data['agent'] ?? null,
            'name' => is_string($name) ? $name : null,
            'email' => $email,
            'description' => $data['description'],
            'conversation_json' => $conversationPayload,
            'status' => 'open',
        ]);

        return response()->json([
            'ok' => true,
            'id' => $row->id,
            'message' => 'Escalation recorded. Our team will follow up.',
        ]);
    }

    public function feedback(Request $request): JsonResponse
    {
        $maxComment = (int) config('faq_chat.max_feedback_comment_chars', 500);

        $data = $request->validate([
            'conversation_uuid' => ['nullable', 'uuid'],
            'rating_stars' => ['nullable', 'integer', 'min:1', 'max:5'],
            'sentiment' => ['nullable', 'in:great,ok,poor'],
            'comment' => ['nullable', 'string', 'max:'.$maxComment],
            'context' => ['required', 'in:idle_timeout'],
        ]);

        $skipped = $request->boolean('skipped');

        $comment = ! $skipped && isset($data['comment']) ? trim((string) $data['comment']) : null;
        if ($comment === '') {
            $comment = null;
        }

        AiFaqFeedback::create([
            'user_id' => $request->user()->id,
            'conversation_uuid' => $data['conversation_uuid'] ?? null,
            'rating_stars' => $skipped ? null : ($data['rating_stars'] ?? null),
            'sentiment' => $skipped ? null : ($data['sentiment'] ?? null),
            'comment' => $skipped ? null : $comment,
            'context' => $data['context'],
        ]);

        return response()->json(['ok' => true, 'message' => 'Thanks for your feedback.']);
    }

    public function chat(Request $request, AIChatService $aiChatService): JsonResponse
    {
        $maxMsg = (int) config('faq_chat.max_message_chars', 500);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:'.$maxMsg],
            'conversation_uuid' => ['nullable', 'string', 'max:64'],
        ]);

        $user = $request->user();
        $uuid = $validated['conversation_uuid'] ?? null;
        if (! is_string($uuid) || ! Str::isUuid($uuid)) {
            $uuid = (string) Str::uuid();
        }

        $conversation = AiFaqConversation::firstOrCreate(
            [
                'user_id' => $user->id,
                'uuid' => $uuid,
            ],
            []
        );

        if (! $conversation->title) {
            $conversation->title = Str::limit($validated['message'], 120);
            $conversation->save();
        }

        $userMessage = AiFaqMessage::create([
            'ai_faq_conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['message'],
            'metadata' => null,
        ]);

        $userFirstName = $this->userFirstNameForAi($user->name);
        $result = $aiChatService->processFaqChat($validated['message'], $userFirstName);

        $userMessage->update([
            'metadata' => [
                'kb_best_score' => $result['kb_best_score'],
                'kb_strict_match' => $result['kb_strict_match'],
                'dataset_candidate' => $result['dataset_candidate'],
            ],
        ]);

        AiFaqMessage::create([
            'ai_faq_conversation_id' => $conversation->id,
            'role' => 'assistant',
            'content' => $result['reply'],
            'metadata' => [
                'kb_best_score' => $result['kb_best_score'],
                'kb_strict_match' => $result['kb_strict_match'],
                'chunks_used' => $result['chunks_used'],
                'openai_called' => $result['openai_called'],
                'dataset_candidate' => $result['dataset_candidate'],
                'error' => $result['error'],
            ],
        ]);

        $conversation->touch();

        return response()->json([
            'reply' => $result['reply'],
            'conversation_uuid' => $conversation->uuid,
            'dataset_candidate' => $result['dataset_candidate'],
        ]);
    }

    public function conversations(Request $request): JsonResponse
    {
        $items = AiFaqConversation::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get()
            ->map(function (AiFaqConversation $c) {
                $last = $c->messages()->latest('id')->first();

                return [
                    'uuid' => $c->uuid,
                    'title' => $c->title ?: 'Conversation',
                    'updated_at' => $c->updated_at?->toIso8601String(),
                    'preview' => $last ? Str::limit(strip_tags($last->content), 140) : '',
                ];
            });

        return response()->json(['conversations' => $items]);
    }

    public function conversationMessages(Request $request, string $uuid): JsonResponse
    {
        $conversation = AiFaqConversation::query()
            ->where('user_id', $request->user()->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $messages = $conversation->messages()
            ->orderBy('id')
            ->get()
            ->map(fn (AiFaqMessage $m) => [
                'role' => $m->role,
                'content' => $m->content,
                'created_at' => $m->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'uuid' => $conversation->uuid,
            'title' => $conversation->title,
            'messages' => $messages,
        ]);
    }

    public function destroyConversation(Request $request, string $uuid): JsonResponse
    {
        $conversation = AiFaqConversation::query()
            ->where('user_id', $request->user()->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        $conversation->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Conversation deleted.',
        ]);
    }
}
