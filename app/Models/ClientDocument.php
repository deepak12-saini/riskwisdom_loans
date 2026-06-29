<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ClientDocument extends Model
{
    protected $fillable = [
        'client_id',
        'task_id',
        'document_type',
        'title',
        'envelope_id',
        'status',
        'signer_name',
        'signer_email',
        'original_disk',
        'original_path',
        'signed_disk',
        'signed_path',
        'sent_at',
        'signed_at',
        'error_message',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'sent_at' => 'datetime',
            'signed_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function isSigned(): bool
    {
        return $this->status === 'signed' && $this->signed_path !== null;
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['sent', 'delivered'], true);
    }

    public function signedDownloadUrl(): ?string
    {
        if (! $this->isSigned() || ! $this->signed_disk || ! $this->signed_path) {
            return null;
        }

        return route('admin.clients.documents.download', [
            'client' => $this->client_id,
            'document' => $this->id,
        ]);
    }

    public function deleteStoredFiles(): void
    {
        if ($this->original_disk && $this->original_path) {
            Storage::disk($this->original_disk)->delete($this->original_path);
        }

        if ($this->signed_disk && $this->signed_path) {
            Storage::disk($this->signed_disk)->delete($this->signed_path);
        }
    }
}
