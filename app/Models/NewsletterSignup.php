<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSignup extends Model
{
    protected $fillable = [
        'first_name',
        'email',
        'source',
        'mailchimp_synced_at',
        'mailchimp_sync_error',
    ];

    protected function casts(): array
    {
        return [
            'mailchimp_synced_at' => 'datetime',
        ];
    }
}
