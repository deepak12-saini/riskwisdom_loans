<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Annature integration (Australian e-sign)
    |--------------------------------------------------------------------------
    |
    | API keys from Annature dashboard → Developers → API keys.
    | Webhook signing secret from Developers → Endpoints.
    |
    */

    'enabled' => env('ANNATURE_ENABLED', false),

    'public_key' => env('ANNATURE_PUBLIC_KEY'),
    'private_key' => env('ANNATURE_PRIVATE_KEY'),
    'account_id' => env('ANNATURE_ACCOUNT_ID'),

    'webhook_secret' => env('ANNATURE_WEBHOOK_SECRET'),

    'api_base_url' => env('ANNATURE_API_BASE_URL', 'https://api.annature.com.au/v1'),

    /*
    |--------------------------------------------------------------------------
    | Signature field placement
    |--------------------------------------------------------------------------
    |
    | anchor      — place on {{signature}} text in the PDF (default)
    | coordinates — fixed box placement when a PDF needs explicit positioning
    |
    | Per document type can override via document_type_placement below.
    |
    */

    'signature_placement' => env('ANNATURE_SIGNATURE_PLACEMENT', 'anchor'),

    'anchor' => env('ANNATURE_SIGNATURE_ANCHOR', '{{signature}}'),

    'signature_field' => [],

    'document_type_placement' => [
        // 'privacy_consent' => 'anchor',
        // 'credit_guide' => 'anchor',
        // 'authority_to_act' => 'anchor',
    ],
];
