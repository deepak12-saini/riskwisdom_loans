<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDocument;
use App\Services\DocuSignService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminClientDocumentController extends Controller
{
    public function store(Request $request, Client $client, DocuSignService $docuSign): RedirectResponse
    {
        $documentTypes = array_keys(config('docusign.document_types', []));

        $validated = $request->validate([
            'document_type' => ['required', 'string', 'in:'.implode(',', $documentTypes)],
            'title' => ['required', 'string', 'max:255'],
            'signer_name' => ['required', 'string', 'max:255'],
            'signer_email' => ['required', 'email', 'max:255'],
            'task_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if (! empty($validated['task_id'])) {
            abort_unless(
                $client->tasks()->whereKey($validated['task_id'])->exists(),
                422,
                'Task does not belong to this client.'
            );
        }

        $disk = config('filesystems.default', 'local');
        $path = $request->file('pdf')->store(
            'clients/'.$client->id.'/documents/original',
            $disk
        );

        $document = $client->documents()->create([
            'document_type' => $validated['document_type'],
            'title' => $validated['title'],
            'signer_name' => $validated['signer_name'],
            'signer_email' => $validated['signer_email'],
            'task_id' => $validated['task_id'] ?? null,
            'original_disk' => $disk,
            'original_path' => $path,
            'status' => 'draft',
        ]);

        if (! $docuSign->isConfigured()) {
            return redirect()
                ->route('admin.clients.show', $client)
                ->with('error', 'Document saved as draft. DocuSign API keys are not configured yet — add them to .env to send for signature.');
        }

        try {
            $result = $docuSign->sendDocument($document);

            $document->update([
                'envelope_id' => $result['envelope_id'],
                'status' => $result['status'] === 'completed' ? 'signed' : $result['status'],
                'sent_at' => now(),
                'error_message' => null,
            ]);
        } catch (\Throwable $exception) {
            $document->update([
                'status' => 'error',
                'error_message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('admin.clients.show', $client)
                ->with('error', 'Document saved but DocuSign send failed: '.$exception->getMessage());
        }

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Document sent to '.$document->signer_email.' via DocuSign.');
    }

    public function sync(Client $client, ClientDocument $document, DocuSignService $docuSign): RedirectResponse
    {
        abort_unless($document->client_id === $client->id, 404);

        if (! $docuSign->isConfigured()) {
            return redirect()
                ->route('admin.clients.show', $client)
                ->with('error', 'DocuSign is not configured.');
        }

        try {
            $docuSign->syncEnvelopeStatus($document);
        } catch (\Throwable $exception) {
            return redirect()
                ->route('admin.clients.show', $client)
                ->with('error', 'Could not sync DocuSign status: '.$exception->getMessage());
        }

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'DocuSign status updated.');
    }

    public function download(Client $client, ClientDocument $document): StreamedResponse
    {
        abort_unless($document->client_id === $client->id, 404);
        abort_unless($document->isSigned(), 404);

        $filename = str($document->title)->slug().'-signed.pdf';

        return Storage::disk($document->signed_disk)->download(
            $document->signed_path,
            $filename
        );
    }

    public function destroy(Client $client, ClientDocument $document): RedirectResponse
    {
        abort_unless($document->client_id === $client->id, 404);

        $document->deleteStoredFiles();
        $document->delete();

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Document removed.');
    }
}
