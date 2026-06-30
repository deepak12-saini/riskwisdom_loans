<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mailchimp Marketing API
    |--------------------------------------------------------------------------
    |
    | Create an API key in Mailchimp → Account → Extras → API keys.
    | Server prefix is the datacenter suffix (e.g. us11 from xxxx-us11).
    | Audience ID is under Audience → Settings → Audience name and defaults.
    |
    */

    'enabled' => env('MAILCHIMP_ENABLED', false),

    'api_key' => env('MAILCHIMP_API_KEY'),

    'server_prefix' => env('MAILCHIMP_SERVER_PREFIX', 'us11'),

    'audience_id' => env('MAILCHIMP_AUDIENCE_ID'),

    'marketing_consent_label' => 'Send me rate updates and home loan tips by email (unsubscribe anytime).',
];
