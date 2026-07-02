<?php

namespace App\Http\Controllers;

use App\Services\AnnatureService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AnnatureWebhookController extends Controller
{
    public function __invoke(Request $request, AnnatureService $annature): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Annature-Signature');

        if (! $annature->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Annature webhook rejected: invalid signature');

            return response('Invalid signature', 401);
        }

        $data = $request->json()->all();

        if ($data === []) {
            $data = json_decode($payload, true) ?? [];
        }

        try {
            $annature->handleWebhookPayload($data);
        } catch (\Throwable $exception) {
            Log::error('Annature webhook error', [
                'message' => $exception->getMessage(),
            ]);

            return response('Webhook processing failed', 500);
        }

        return response('OK', 200);
    }
}
