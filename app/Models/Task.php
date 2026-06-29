<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'description',
        'owner',
        'status',
        'priority',
        'due_date',
        'notes',
        'assigned_user_id',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress'], true);
    }

    public function isOverdue(): bool
    {
        return $this->isOpen()
            && $this->due_date !== null
            && $this->due_date->isPast();
    }

    /**
     * @param  Builder<Task>  $query
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * @param  Builder<Task>  $query
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->open()
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today());
    }
}
