<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    /*
     * Use a model your key supports (e.g. gpt-4o-mini, gpt-4o).
     * Invalid model names cause API errors that surface as generic server errors in the UI.
     */
    'chat_model' => env('OPENAI_CHAT_MODEL', 'gpt-4o-mini'),

    /*
     * TLS to api.openai.com (Guzzle/cURL). On Windows, error 60 “unable to get local issuer certificate”
     * usually means PHP has no CA bundle: set OPENAI_CA_BUNDLE to curl’s cacert.pem, or for local-only
     * dev set OPENAI_VERIFY_SSL=false (never disable on production).
     */
    'verify_ssl' => match (strtolower((string) env('OPENAI_VERIFY_SSL', 'true'))) {
        '0', 'false', 'no', 'off' => false,
        default => true,
    },

    'ca_bundle' => trim((string) env('OPENAI_CA_BUNDLE', '')),
];
