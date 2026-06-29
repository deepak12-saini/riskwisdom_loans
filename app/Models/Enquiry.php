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
        'email_sent_at',
        'auto_reply_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'email_sent_at' => 'datetime',
            'auto_reply_sent_at' => 'datetime',
        ];
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
        ];
    }
}
