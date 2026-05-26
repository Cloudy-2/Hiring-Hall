<?php

namespace App\Services\AI;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * FAQ RAG chat backed by `hiring_hall_faq_rag.md` plus optional `hiring_hall_routes_reference.md` for path hints.
 * Returns structured payloads for logging, training candidates, and safer fallbacks.
 */
class AIChatService
{
    protected string $faqKbPath;

    protected string $routesRefPath;

    protected string $legacyKbPath;

    /** @var array<string, string> */
    protected array $agentPaths = [];

    /** Token overlap needed to treat retrieval as a “strong” match. */
    protected int $strictMinScore = 1;

    /** Max FAQ excerpts sent to the model (bytes roughly bounded by chunk count). */
    protected int $maxChunksToModel = 6;

    /** Paragraphs pulled into agent RAG context. */
    protected int $agentMaxParagraphs = 8;

    public function __construct()
    {
        $this->faqKbPath = resource_path('markdown/hiring_hall_faq_rag.md');
        $this->routesRefPath = resource_path('markdown/hiring_hall_routes_reference.md');
        $this->legacyKbPath = resource_path('markdown/hillbcs_kb.md');
        $this->agentPaths = [
            'douglas' => resource_path('markdown/agents/douglas.md'),
            'kent' => resource_path('markdown/agents/kent.md'),
            'sofia' => resource_path('markdown/agents/sofia.md'),
            'saffi' => resource_path('markdown/agents/saffi.md'),
            'kimberly' => resource_path('markdown/agents/kimberly.md'),
            'arnel' => resource_path('markdown/agents/arnel.md'),
        ];
    }

    protected function openAiClient(): PendingRequest
    {
        $req = Http::timeout(60)->connectTimeout(15);

        $ca = (string) config('openai.ca_bundle', '');
        if ($ca !== '' && is_readable($ca)) {
            return $req->withOptions(['verify' => $ca]);
        }

        if (! (bool) config('openai.verify_ssl', true)) {
            return $req->withOptions(['verify' => false]);
        }

        return $req;
    }

    protected function sanitizeAiDisplayName(?string $name): ?string
    {
        if (! is_string($name)) {
            return null;
        }
        $name = trim($name);
        if ($name === '') {
            return null;
        }
        $name = preg_replace('/[\x00-\x1F\x7F<>]+/u', '', $name) ?? '';
        $name = trim($name);
        if ($name === '') {
            return null;
        }

        return Str::limit($name, 40, '');
    }

    /**
     * Legacy multi-agent RAG: hillbcs_kb.md + agent profile markdown.
     */
    public function chatWithRag(string $message, string $agent = 'kent'): string
    {
        $message = trim($message);
        if ($message === '') {
            return 'Please enter a message.';
        }

        $agentKey = strtolower($agent);
        if (! isset($this->agentPaths[$agentKey])) {
            $agentKey = 'kent';
        }

        $kb = $this->readFileSafe($this->legacyKbPath);
        $routesRef = $this->readFileSafe($this->routesRefPath);
        $profile = $this->readFileSafe($this->agentPaths[$agentKey]);
        $corpus = trim($kb."\n\n".$routesRef."\n\n".$profile);
        $context = $this->retrieveRelevantContextLegacy($corpus, $message, $this->agentMaxParagraphs);

        $apiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');
        if (! $apiKey) {
            $hint = $context !== '' ? "\n\nContext excerpt:\n".$context : '';

            return 'OpenAI is not configured (missing OPENAI_API_KEY).'.$hint;
        }

        $system = $this->buildAgentSystemPrompt($agentKey, $profile);
        $userPayload = $context !== ''
            ? "Context excerpts:\n{$context}\n\nUser:\n{$message}"
            : "No local context was retrieved. Answer generally about Hiring Hall and suggest /FAQ or support.\n\nUser:\n{$message}";

        $model = config('openai.chat_model') ?: 'gpt-4o-mini';

        try {
            $res = $this->openAiClient()
                ->withHeaders([
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $userPayload],
                    ],
                    'max_tokens' => 280,
                    'temperature' => 0.2,
                ]);

            if ($res->successful()) {
                $json = $res->json();
                $content = $json['choices'][0]['message']['content'] ?? null;
                if (is_string($content) && trim($content) !== '') {
                    return trim($content);
                }

                return 'The AI returned an empty reply. Please try again.';
            }

            Log::warning('OpenAI agent chat HTTP error', [
                'status' => $res->status(),
                'body' => $res->body(),
                'model' => $model,
            ]);

            return match ($res->status()) {
                401 => 'OpenAI rejected the API key (401). Check OPENAI_API_KEY and model access.',
                429 => 'The AI service is rate-limited. Try again soon.',
                default => 'The AI service returned an error. Please try again or use /FAQ.',
            };
        } catch (\Throwable $e) {
            Log::error('AIChatService agent chat exception', [
                'message' => $e->getMessage(),
                'class' => $e::class,
            ]);

            $hint = config('app.debug') ? ' ('.$e->getMessage().')' : '';

            return 'Could not reach the AI service'.$hint.'.';
        }
    }

    protected function readFileSafe(string $path): string
    {
        if (! is_readable($path)) {
            return '';
        }

        return trim((string) file_get_contents($path));
    }

    protected function buildAgentSystemPrompt(string $agentKey, string $profile): string
    {
        $name = ucfirst($agentKey);
        $base = "You are {$name}, a helpful assistant for HillBCS Hiring Hall. Use the context excerpts when they apply. If they are insufficient, say so and point users to /FAQ or support. Do not claim access to private account data. No markdown code fences.";

        if ($profile !== '') {
            $base .= "\n\nAgent profile (style and role):\n".$profile;
        }

        return $base;
    }

    protected function retrieveRelevantContextLegacy(string $corpus, string $query, int $maxParagraphs): string
    {
        $corpus = trim($corpus);
        if ($corpus === '') {
            return '';
        }

        $paragraphs = preg_split('/\n\s*\n/', $corpus) ?: [];
        $paragraphs = array_values(array_filter(array_map('trim', $paragraphs), fn (string $p): bool => $p !== ''));

        if ($paragraphs === []) {
            return '';
        }

        $qTokens = $this->tokenize($query);
        $scored = [];
        foreach ($paragraphs as $p) {
            $scored[] = ['text' => $p, 'score' => $this->overlapScore($qTokens, $this->tokenize($p))];
        }

        usort($scored, fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        $withHits = array_values(array_filter($scored, fn (array $row): bool => $row['score'] > 0));
        $selected = $withHits !== []
            ? array_slice($withHits, 0, $maxParagraphs)
            : array_slice($scored, 0, min(3, count($scored)));

        return implode(
            "\n\n---\n\n",
            array_map(fn (array $row): string => $row['text'], $selected)
        );
    }

    /**
     * @return array{
     *     reply: string,
     *     kb_best_score: int,
     *     kb_strict_match: bool,
     *     chunks_used: int,
     *     openai_called: bool,
     *     dataset_candidate: bool,
     *     error: ?string
     * }
     */
    public function processFaqChat(string $userMessage, ?string $userFirstName = null): array
    {
        $userMessage = trim($userMessage);
        $userFirstName = $this->sanitizeAiDisplayName($userFirstName);
        $baseMeta = [
            'reply' => '',
            'kb_best_score' => 0,
            'kb_strict_match' => false,
            'chunks_used' => 0,
            'openai_called' => false,
            'dataset_candidate' => false,
            'error' => null,
        ];

        if ($userMessage === '') {
            return array_merge($baseMeta, [
                'reply' => 'Please type a question about Hiring Hall or the FAQ.',
            ]);
        }

        if (! is_readable($this->faqKbPath)) {
            return array_merge($baseMeta, [
                'reply' => 'The FAQ knowledge file is missing. Ask an administrator to add resources/markdown/hiring_hall_faq_rag.md.',
                'dataset_candidate' => true,
            ]);
        }

        $kb = trim((string) file_get_contents($this->faqKbPath));
        if ($kb === '') {
            return array_merge($baseMeta, [
                'reply' => 'The FAQ knowledge file is empty. Please contact support.',
                'dataset_candidate' => true,
            ]);
        }

        $routesRef = $this->readFileSafe($this->routesRefPath);
        if ($routesRef !== '') {
            $kb .= "\n\n---\n\n".$routesRef;
        }

        $chunks = $this->extractQaChunks($kb);
        $retrieval = $this->scoreChunks($chunks, $userMessage);
        $bestScore = $retrieval[0]['score'] ?? 0;

        $strict = array_values(array_filter($retrieval, fn (array $row): bool => $row['score'] >= $this->strictMinScore));
        $selected = $strict !== []
            ? array_slice($strict, 0, $this->maxChunksToModel)
            : array_slice($retrieval, 0, $this->maxChunksToModel);

        $kbStrictMatch = $strict !== [];
        $datasetCandidate = ! $kbStrictMatch;

        $context = implode(
            "\n\n---\n\n",
            array_map(fn (array $row): string => $row['text'], $selected)
        );

        $apiKey = config('openai.api_key') ?: env('OPENAI_API_KEY');
        if (! $apiKey) {
            $fallback = 'OpenAI is not configured (missing OPENAI_API_KEY). You can still read answers on /FAQ or contact Help Desk.';
            if ($userFirstName !== null) {
                $fallback = "Hi, {$userFirstName}—".$fallback;
            }
            if ($context !== '') {
                $fallback .= "\n\nHere is the closest FAQ text we have on file:\n\n".$context;
            }

            return array_merge($baseMeta, [
                'reply' => $fallback,
                'kb_best_score' => $bestScore,
                'kb_strict_match' => $kbStrictMatch,
                'chunks_used' => count($selected),
                'openai_called' => false,
                'dataset_candidate' => $datasetCandidate,
            ]);
        }

        $lowConfidenceNote = $kbStrictMatch
            ? ''
            : <<<'NOTE'


Retrieval note: overlap with the stored help text was weak or the question is situational. Use the excerpts for any specific facts they contain. If they do not spell out an answer (e.g. exact wait times after applying), you may still reply in a **short**, natural way **only about Hiring Hall**—acknowledge their scenario, set expectations in general terms (for example, review timing depends on the employer and role; they can track status from their applications area), and avoid invented deadlines or guarantees. Keep it to a few sentences, conversational. Point to /FAQ or Help Desk when they need something account-specific or definitive.
NOTE;

        $system = <<<'PROMPT'
You are "Ask Hill AI" for Hiring Hall (HillBCS). You help users with this hiring platform.

When the excerpts in the user message clearly cover the question: answer mainly from those excerpts. Be clear and concise.

When the excerpts do not fully answer but the topic is still about Hiring Hall (applying to jobs, employers, applications, verification, accounts, notifications, etc.): you may give a **minimal** conversational reply—warm and practical, **staying strictly on Hiring Hall**. Do not invent exact wait times, SLAs, or policies unless the excerpts state them; say timing can vary and suggest they check in-app status or the help center. If the question is off-topic for this product, say briefly that you only help with Hiring Hall and suggest /FAQ.

Rules:
1) Do not claim you can see the user's private account, applications, messages, or data.
2) Do not contradict the excerpts when they do state a fact.
3) You may mention a path when it helps (e.g. /FAQ, /jobs)—woven into a sentence—not as a dry directory listing.
4) **Human voice:** When excerpts include **route lists** (GET/POST, backticks, “route `name`”), **never** paste that inventory to the user unless they explicitly ask for every technical endpoint. Turn it into short, friendly guidance: what they’re trying to do, the usual next step, and who to contact if it’s org-specific. Sound like a helpful colleague, not API documentation.
5) Prefer a few short sentences or light bullets only for real step-by-step tasks—not for enumerating URLs.
6) If a **Personalization** line appears after the user question with their first name, use that name occasionally for warmth (e.g. “Yes, {Name}—…” or “Happy to help, {Name}.”). Use only that first name—never last names. Do not force the name into every sentence. If there is no Personalization line, do not invent a name.
7) No markdown code fences.
PROMPT;

        $userPayload = "Knowledge excerpts (FAQ, routes, etc.):\n{$context}{$lowConfidenceNote}\n\nUser question:\n{$userMessage}";
        if ($userFirstName !== null) {
            $userPayload .= "\n\nPersonalization: The user’s first name is «{$userFirstName}» (first word of their account name). Address them by this name sometimes—not every sentence—to feel personal and friendly.";
        }

        $model = config('openai.chat_model') ?: 'gpt-4o-mini';
        $temperature = $kbStrictMatch ? 0.15 : 0.22;

        try {
            $res = $this->openAiClient()
                ->withHeaders([
                    'Authorization' => 'Bearer '.$apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $userPayload],
                    ],
                    'max_tokens' => (int) config('faq_chat.max_openai_reply_tokens', 400),
                    'temperature' => $temperature,
                ]);

            if ($res->successful()) {
                $json = $res->json();
                $content = $json['choices'][0]['message']['content'] ?? null;
                if (is_string($content) && trim($content) !== '') {
                    return [
                        'reply' => trim($content),
                        'kb_best_score' => $bestScore,
                        'kb_strict_match' => $kbStrictMatch,
                        'chunks_used' => count($selected),
                        'openai_called' => true,
                        'dataset_candidate' => $datasetCandidate,
                        'error' => null,
                    ];
                }

                return array_merge($baseMeta, [
                    'reply' => 'The AI returned an empty reply. Please try again or open /FAQ.',
                    'kb_best_score' => $bestScore,
                    'kb_strict_match' => $kbStrictMatch,
                    'chunks_used' => count($selected),
                    'openai_called' => true,
                    'dataset_candidate' => $datasetCandidate,
                    'error' => 'empty_choice',
                ]);
            }

            Log::warning('OpenAI FAQ chat HTTP error', [
                'status' => $res->status(),
                'body' => $res->body(),
                'model' => $model,
            ]);

            $userReply = match ($res->status()) {
                401 => 'OpenAI rejected the API key (401). Check OPENAI_API_KEY and model access.',
                429 => 'The AI service is rate-limited. Try again soon or use /FAQ.',
                default => 'The AI service returned an error. Please use /FAQ or Help Desk.',
            };

            return array_merge($baseMeta, [
                'reply' => $userReply,
                'kb_best_score' => $bestScore,
                'kb_strict_match' => $kbStrictMatch,
                'chunks_used' => count($selected),
                'openai_called' => true,
                'dataset_candidate' => true,
                'error' => 'http_'.$res->status(),
            ]);
        } catch (\Throwable $e) {
            Log::error('AIChatService FAQ exception', [
                'message' => $e->getMessage(),
                'class' => $e::class,
            ]);

            $hint = config('app.debug') ? ' ('.$e->getMessage().')' : '';

            return array_merge($baseMeta, [
                'reply' => 'Could not reach the AI service'.$hint.'. Please use /FAQ or open a support ticket.',
                'kb_best_score' => $bestScore,
                'kb_strict_match' => $kbStrictMatch,
                'chunks_used' => count($selected),
                'openai_called' => true,
                'dataset_candidate' => true,
                'error' => 'exception',
            ]);
        }
    }

    /**
     * @return list<string>
     */
    protected function extractQaChunks(string $kb): array
    {
        $pos = strpos($kb, '**Q:**');
        $body = $pos !== false ? substr($kb, $pos) : $kb;
        $parts = preg_split('/(?=\*\*Q:\*\*)/', $body, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $chunks = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || ! str_starts_with($part, '**Q:**')) {
                continue;
            }
            if (! str_contains($part, '**A:**')) {
                continue;
            }
            $chunks[] = $part;
        }

        if ($chunks !== []) {
            return $chunks;
        }

        $fallback = preg_split('/\n-{3,}\n/', $kb) ?: [];
        $out = [];
        foreach ($fallback as $c) {
            $c = trim((string) $c);
            if ($c !== '') {
                $out[] = $c;
            }
        }

        return $out !== [] ? $out : [$kb];
    }

    /**
     * @param  list<string>  $chunks
     * @return list<array{text: string, score: int}>
     */
    protected function scoreChunks(array $chunks, string $query): array
    {
        $qTokens = $this->tokenize($query);
        $scored = [];

        foreach ($chunks as $text) {
            $score = $this->overlapScore($qTokens, $this->tokenize($text));
            $scored[] = ['text' => $text, 'score' => $score];
        }

        usort($scored, fn (array $a, array $b): int => $b['score'] <=> $a['score']);

        return $scored;
    }

    /**
     * @return list<string>
     */
    protected function tokenize(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text) ?? $text;

        return preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }

    /**
     * @param  list<string>  $a
     * @param  list<string>  $b
     */
    protected function overlapScore(array $a, array $b): int
    {
        if ($a === [] || $b === []) {
            return 0;
        }

        $setA = array_count_values($a);
        $setB = array_count_values($b);

        $score = 0;
        foreach ($setA as $tok => $cnt) {
            if (isset($setB[$tok])) {
                $score += min($cnt, $setB[$tok]);
            }
        }

        return $score;
    }
}
