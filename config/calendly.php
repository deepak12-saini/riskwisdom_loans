<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Calendly booking webhook
    |--------------------------------------------------------------------------
    |
    | Create a webhook subscription via the Calendly API (paid plans) pointing to
    | https://your-domain/webhooks/calendly for invitee.created / invitee.canceled.
    | Store the signing key returned when the subscription is created.
    |
    | @see https://developer.calendly.com/api-docs/c1ddc06ce1f1a-create-webhook-subscription
    |
    */

    'webhook_signing_key' => env('CALENDLY_WEBHOOK_SIGNING_KEY'),

    /*
    | Reject webhook signatures older than this many seconds (replay protection).
    */
    'webhook_tolerance_seconds' => (int) env('CALENDLY_WEBHOOK_TOLERANCE', 180),
];
