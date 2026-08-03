<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Services\EnquiryNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RateReviewController extends Controller
{
    public function __construct(
        private readonly EnquiryNotificationService $notifications,
    ) {}

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('_gotcha')) {
            return redirect()->route('rate-review');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => lead_name_rules(),
            'last_name' => lead_name_rules(),
            'phone_country_code' => lead_phone_country_code_rules(),
            'phone' => lead_phone_rules(),
            'email' => lead_email_rules(),
            'current_rate' => ['required', 'numeric', 'min:0', 'max:20'],
            'loan_balance' => ['nullable', 'numeric', 'min:0', 'max:50000000'],
            'lender' => ['nullable', 'string', 'max:120'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
        ], [
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'phone.required' => 'Please enter your phone number.',
            'phone_country_code.required' => 'Please select a country code.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'current_rate.required' => 'Please enter your current interest rate.',
        ]);

        apply_lead_identity_checks($validator);

        if ($validator->fails()) {
            return redirect()
                ->route('rate-review')
                ->withErrors($validator)
                ->withInput()
                ->withFragment('rate-review-form');
        }

        $validated = normalize_validated_lead_phone($validator->validated());

        $metadata = [
            'current_rate' => (float) $validated['current_rate'],
            'loan_balance' => isset($validated['loan_balance']) ? (float) $validated['loan_balance'] : null,
            'lender' => $validated['lender'] ?? null,
        ];

        $enquiryLines = [
            'Rate review request — fast callback requested.',
            'Current rate: '.$validated['current_rate'].'% p.a.',
        ];

        if (! empty($validated['loan_balance'])) {
            $enquiryLines[] = 'Approx. loan balance: $'.number_format((float) $validated['loan_balance']);
        }

        if (! empty($validated['lender'])) {
            $enquiryLines[] = 'Current lender: '.$validated['lender'];
        }

        $enquiry = Enquiry::create([
            'lead_type' => 'rate_review',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'loan_type' => 'refinance',
            'timeline' => 'ready_now',
            'enquiry' => implode("\n", $enquiryLines),
            'metadata' => $metadata,
            'source' => 'rate_review_form',
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'ip_address' => $request->ip(),
            'marketing_consent' => $request->boolean('marketing_consent'),
        ]);

        $this->notifications->sendAfterResponse($enquiry);

        return redirect()
            ->route('thank-you')
            ->with('lead_type', 'rate_review')
            ->with('enquiry_id', $enquiry->id)
            ->with('utm_source', $validated['utm_source'] ?? null)
            ->with('utm_medium', $validated['utm_medium'] ?? null)
            ->with('utm_campaign', $validated['utm_campaign'] ?? null);
    }
}
