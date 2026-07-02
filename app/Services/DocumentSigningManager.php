<?php

namespace App\Services;

use App\Contracts\DocumentSigningService as DocumentSigningServiceContract;
use App\Models\ClientDocument;

class DocumentSigningManager
{
    public function __construct(
        private AnnatureService $annature,
        private DocuSignService $docuSign,
    ) {}

    public function forDocument(?ClientDocument $document = null): DocumentSigningServiceContract
    {
        $provider = $document?->signing_provider ?? config('signing.provider', 'annature');

        return $this->forProvider($provider);
    }

    public function active(): DocumentSigningServiceContract
    {
        return $this->forProvider((string) config('signing.provider', 'annature'));
    }

    public function forProvider(string $provider): DocumentSigningServiceContract
    {
        return match ($provider) {
            'docusign' => $this->docuSign,
            default => $this->annature,
        };
    }
}
