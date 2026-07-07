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
    | coordinates — fixed box placement for general admin uploads (default)
    | anchor      — place on {{signature}} text for controlled templates
    |
    | Per document type can override via document_type_placement below.
    |
    */

    'signature_placement' => env('ANNATURE_SIGNATURE_PLACEMENT', 'coordinates'),

    'anchor' => env('ANNATURE_SIGNATURE_ANCHOR', '{{signature}}'),

  /*
    | Default field size/margins for coordinate placement. Position is computed from
    | the uploaded PDF's page size (signature goes on the last page, bottom-left).
    | Do not set x_coordinate / y_coordinate here — they are derived per PDF.
    */
    'signature_field' => [
        'margin_x' => 72,
        'margin_y' => 72,
        'width' => 200,
        'height' => 50,
    ],

    'document_type_placement' => [
        // 'privacy_consent' => 'anchor',
        // 'credit_guide' => 'anchor',
        // 'authority_to_act' => 'anchor',
    ],
];
