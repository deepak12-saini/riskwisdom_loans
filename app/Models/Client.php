<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'enquiry_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'loan_type',
        'state',
        'status',
        'assigned_user_id',
        'notes',
    ];

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function openTasks(): HasMany
    {
        return $this->hasMany(Task::class)->whereIn('status', ['open', 'in_progress']);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * @return array<string, mixed>
     */
    public static function fromEnquiry(Enquiry $enquiry): array
    {
        return [
            'enquiry_id' => $enquiry->id,
            'first_name' => $enquiry->first_name,
            'last_name' => $enquiry->last_name,
            'phone' => $enquiry->phone,
            'email' => $enquiry->email,
            'loan_type' => $enquiry->loan_type,
            'state' => $enquiry->state,
            'status' => 'active',
        ];
    }
}
