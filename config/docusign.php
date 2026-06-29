<?php

return [
    /*
    |--------------------------------------------------------------------------
    | DocuSign integration (JWT grant)
    |--------------------------------------------------------------------------
    |
    | Create an app at https://developers.docusign.com/ and add the integration
    | key, user ID, account ID, and RSA private key to .env. Use demo for sandbox.
    |
    */

    'enabled' => env('DOCUSIGN_ENABLED', false),

    'environment' => env('DOCUSIGN_ENV', 'demo'), // demo | production

    'integration_key' => env('DOCUSIGN_INTEGRATION_KEY'),
    'user_id' => env('DOCUSIGN_USER_ID'),
    'account_id' => env('DOCUSIGN_ACCOUNT_ID'),
    'private_key' => env('DOCUSIGN_PRIVATE_KEY'),
    'private_key_path' => env('DOCUSIGN_PRIVATE_KEY_PATH'),

    'webhook_secret' => env('DOCUSIGN_WEBHOOK_SECRET'),

    'oauth_base_url' => env('DOCUSIGN_ENV', 'demo') === 'production'
        ? 'https://account.docusign.com'
        : 'https://account-d.docusign.com',

    'api_base_url' => env('DOCUSIGN_ENV', 'demo') === 'production'
        ? 'https://na4.docusign.net/restapi'
        : 'https://demo.docusign.net/restapi',

    'document_types' => [
        'privacy_consent' => 'Privacy consent',
        'credit_guide' => 'Credit guide acknowledgment',
        'authority_to_act' => 'Authority to act',
        'other' => 'Other document',
    ],

    'statuses' => [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'delivered' => 'Delivered',
        'signed' => 'Signed',
        'declined' => 'Declined',
        'voided' => 'Voided',
        'error' => 'Error',
    ],
];
