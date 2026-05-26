<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FAQ / Ask Hill AI — URL mode (local http vs production https)
    |--------------------------------------------------------------------------
    |
    | - relative_urls (default true): browser calls /ai/faq-chat on the SAME
    |   origin as the page (works for http://127.0.0.1:8000 and https://hire...).
    |
    | - api_origin: set to override the origin only for JS fetch URLs, e.g.
    |   http://127.0.0.1:8000 when the app is opened from another host, or a
    |   staging API URL. No trailing slash. Empty = not used.
    |
    */
    'relative_urls' => match (strtolower((string) env('FAQ_CHAT_RELATIVE_URLS', 'true'))) {
        '0', 'false', 'no', 'off' => false,
        default => true,
    },

    'api_origin' => rtrim((string) env('FAQ_CHAT_API_ORIGIN', ''), '/'),

    /*
    | GET /ai/faq-chat returns JSON usage hints (avoids 405 when opened in a browser).
    | Set FAQ_CHAT_ALLOW_GET_DISCOVERY=false for strict POST-only (405 + Allow header).
    */
    'allow_get_discovery' => match (strtolower((string) env('FAQ_CHAT_ALLOW_GET_DISCOVERY', 'true'))) {
        '0', 'false', 'no', 'off' => false,
        default => true,
    },

    /*
    |--------------------------------------------------------------------------
    | Input limits & idle feedback (Ask Hill AI drawer)
    |--------------------------------------------------------------------------
    */
    'max_message_chars' => max(1, min(4000, (int) env('FAQ_CHAT_MAX_MESSAGE_CHARS', 500))),

    'max_feedback_comment_chars' => max(1, min(2000, (int) env('FAQ_CHAT_MAX_FEEDBACK_COMMENT_CHARS', 500))),

    /** Seconds without user activity in the chat before showing the feedback survey and ending the session. */
    'idle_timeout_seconds' => max(60, min(86400, (int) env('FAQ_CHAT_IDLE_TIMEOUT_SECONDS', 900))),

    /** Cap OpenAI completion length for FAQ chat (cost control). */
    'max_openai_reply_tokens' => max(120, min(2000, (int) env('FAQ_CHAT_MAX_REPLY_TOKENS', 400))),

];
