<?php

namespace App\Http\Controllers\AI\Manus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ManusChatController extends Controller
{
    protected string $base;

    protected string $key;

    public function __construct()
    {
        // Example .env:
        // SERVICES_MANUS_BASE=https://api.manus.ai
        // SERVICES_MANUS_KEY=sk_xxxxx
        $this->base = rtrim(config('services.manus.base'), '/');
        $this->key = (string) config('services.manus.key');
    }

    /** Show chat page */
    public function index()
    {
        return view('modules.ai.manus.chat');
    }

    /** Send a chat message using Manus /v1/tasks (chat simulation) */
    public function send(Request $req)
    {
        $data = $req->validate([
            'message' => 'required|string|max:8000',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|in:user,assistant,system',
            'history.*.content' => 'required_with:history|string|max:8000',
            'profile' => 'nullable|in:speed,quality',
            'topic' => 'nullable|string|max:120',
        ]);

        $profile = $data['profile'] ?? 'speed';
        $history = $data['history'] ?? [];
        $message = $data['message'];

        // build simulated chat prompt
        $system = [
            'role' => 'system',
            'content' => <<<'SYS'
            You are Manus AI, a helpful conversational assistant.
            Respond naturally to the user's message in a chat-like tone.
            Avoid repeating the user's question, and keep answers short and helpful.
            SYS
            ,
        ];

        $messages = array_merge([$system], $history, [['role' => 'user', 'content' => $message]]);

        // combine messages into one prompt string
        $prompt = collect($messages)->map(fn ($m) => strtoupper($m['role']).': '.$m['content'])->implode("\n\n");

        $payload = [
            'prompt' => $prompt,
            'taskMode' => 'agent',
            'agentProfile' => $profile,
            'hideInTaskList' => true,
            'createShareableLink' => false,
        ];

        $url = $this->base.'/v1/tasks';

        $resp = Http::withHeaders(['API_KEY' => $this->key])
            ->timeout(90)
            ->retry(1, 500)
            ->post($url, $payload);

        if ($resp->status() === 403 && str_contains($resp->body(), 'free user')) {
            // Retry with speed mode
            $payload['agentProfile'] = 'speed';
            $resp = Http::withHeaders(['API_KEY' => $this->key])
                ->timeout(90)
                ->retry(1, 500)
                ->post($url, $payload);
        }

        if ($resp->failed()) {
            // \Log::error('Manus /v1/tasks error', [
            //     'status' => $resp->status(),
            //     'body' => $resp->body(),
            //     'url' => $url,
            // ]);

            return response()->json(
                [
                    'ok' => false,
                    'error' => 'upstream_failed',
                    'status' => $resp->status(),
                    'detail' => $resp->json() ?: $resp->body(),
                ],
                502,
            );
        }

        $j = $resp->json();

        // try to extract usable text
        $answer = data_get($j, 'message.content') ?? (data_get($j, 'output.0.content.0.text') ?? (data_get($j, 'result.content') ?? (data_get($j, 'answer') ?? (data_get($j, 'messages.0.content.0.text') ?? (data_get($j, 'messages.0.content') ?? '(no content)')))));

        return response()->json([
            'ok' => true,
            'answer' => trim($answer),
            'raw' => $j,
        ]);
    }
}
