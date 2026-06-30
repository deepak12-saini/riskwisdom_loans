<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Services\BorrowingPowerCalculator;
use App\Services\EnquiryNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowingPowerController extends Controller
{
    public function __construct(
        private readonly BorrowingPowerCalculator $calculator,
        private readonly EnquiryNotificationService $notifications,
    ) {}

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('_gotcha')) {
            return redirect()->route('tools.borrowing-power');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'income' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'expenses' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'deposit' => ['required', 'numeric', 'min:0', 'max:10000000'],
            'rate' => ['required', 'numeric', 'min:0', 'max:15'],
            'term' => ['required', 'integer', 'min:5', 'max:30'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
        ], [
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('tools.borrowing-power')
                ->withErrors($validator)
                ->withInput()
                ->with('borrowing_power_unlock', true)
                ->withFragment('bp-lead-gate');
        }

        $validated = $validator->validated();

        $result = $this->calculator->estimate(
            (float) $validated['income'],
            (float) $validated['expenses'],
            (float) $validated['deposit'],
            (float) $validated['rate'],
            (int) $validated['term'],
        );

        $enquiryText = implode("\n", [
            'Borrowing power calculator lead.',
            'Estimated range: '.$result['range_label'],
            'Annual income: $'.number_format($result['income']),
            'Monthly expenses: $'.number_format($result['expenses']),
            'Deposit: $'.number_format($result['deposit']),
            'Rate: '.$result['rate'].'%',
            'Term: '.$result['term_years'].' years',
        ]);

        $enquiry = Enquiry::create([
            'lead_type' => 'borrowing_power',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'enquiry' => $enquiryText,
            'metadata' => $result,
            'source' => 'borrowing_power_calculator',
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'ip_address' => $request->ip(),
            'marketing_consent' => $request->boolean('marketing_consent'),
        ]);

        $this->notifications->sendAfterResponse($enquiry);

        return redirect()
            ->route('tools.borrowing-power')
            ->with('borrowing_power_result', $result)
            ->with('borrowing_power_submitted_first_name', $validated['first_name'])
            ->withFragment('bp-result');
    }
}
