<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enquiry extends Model
{
    protected $fillable = [
        'lead_type',
        'first_name',
        'last_name',
        'phone',
        'email',
        'loan_type',
        'timeline',
        'state',
        'enquiry',
        'metadata',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip_address',
        'status',
        'call_status',
        'call_notes',
        'callback_at',
        'last_called_at',
        'email_sent_at',
        'auto_reply_sent_at',
        'marketing_consent',
        'mailchimp_synced_at',
        'mailchimp_sync_error',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'email_sent_at' => 'datetime',
            'auto_reply_sent_at' => 'datetime',
            'marketing_consent' => 'boolean',
            'mailchimp_synced_at' => 'datetime',
            'callback_at' => 'datetime',
            'last_called_at' => 'datetime',
        ];
    }

    public function callStatusLabel(): string
    {
        return config('riskwisdom.call_statuses')[$this->call_status ?? 'new']
            ?? ucfirst(str_replace('_', ' ', (string) ($this->call_status ?? 'new')));
    }

    public function isCallbackDue(): bool
    {
        if (($this->call_status ?? 'new') !== 'callback') {
            return false;
        }

        if ($this->callback_at === null) {
            return true;
        }

        return $this->callback_at->lte(now()->endOfDay());
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function hasClientFile(): bool
    {
        return $this->client()->exists();
    }

    /**
     * @return array<string, string>
     */
    public function toMailDetails(): array
    {
        $metadata = is_array($this->metadata) ? $this->metadata : [];

        return [
            'lead_type' => $this->lead_type ?? 'contact',
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'loan_type' => $this->loan_type ?? '',
            'timeline' => $this->timeline ?? '',
            'state' => $this->state ?? '',
            'enquiry' => $this->enquiry,
            'source' => $this->source ?? '',
            'utm_source' => $this->utm_source ?? '',
            'utm_medium' => $this->utm_medium ?? '',
            'utm_campaign' => $this->utm_campaign ?? '',
            'guide_slug' => (string) ($metadata['guide_slug'] ?? ''),
            'guide_title' => (string) ($metadata['guide_title'] ?? ''),
            'guide_download_url' => (string) ($metadata['guide_download_url'] ?? ''),
        ];
    }
}
