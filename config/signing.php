<?php

return [
    /*
    |--------------------------------------------------------------------------
    | E-sign provider
    |--------------------------------------------------------------------------
    |
    | Which service sends documents from admin: annature (AU) or docusign.
    | Set SIGNING_PROVIDER=annature in .env when Annature keys are ready.
    |
    */

    'provider' => env('SIGNING_PROVIDER', 'annature'),

    'providers' => [
        'annature' => 'Annature',
        'docusign' => 'DocuSign',
    ],

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
