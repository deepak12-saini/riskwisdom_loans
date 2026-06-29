<?php

namespace App\Http\Controllers;

use App\Services\DocuSignService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DocuSignWebhookController extends Controller
{
    public function __invoke(Request $request, DocuSignService $docuSign): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('X-DocuSign-Signature-1');

        if (! $docuSign->verifyWebhookSignature($payload, $signature)) {
            Log::warning('DocuSign webhook rejected: invalid signature');

            return response('Invalid signature', 401);
        }

        $data = $request->json()->all();

        if ($data === []) {
            $data = json_decode($payload, true) ?? [];
        }

        try {
            $docuSign->handleWebhookPayload($data);
        } catch (\Throwable $exception) {
            Log::error('DocuSign webhook error', [
                'message' => $exception->getMessage(),
            ]);

            return response('Webhook processing failed', 500);
        }

        return response('OK', 200);
    }
}
