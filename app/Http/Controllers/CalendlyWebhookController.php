<?php

namespace App\Http\Controllers;

use App\Services\CalendlyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CalendlyWebhookController extends Controller
{
    public function __invoke(Request $request, CalendlyService $calendly): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Calendly-Webhook-Signature');

        if (! $calendly->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Calendly webhook rejected: invalid signature');

            return response('Invalid signature', 401);
        }

        $data = $request->json()->all();

        if ($data === []) {
            $data = json_decode($payload, true) ?? [];
        }

        try {
            $calendly->handleWebhookPayload($data);
        } catch (\Throwable $exception) {
            Log::error('Calendly webhook error', [
                'message' => $exception->getMessage(),
            ]);

            return response('Webhook processing failed', 500);
        }

        return response('OK', 200);
    }
}
