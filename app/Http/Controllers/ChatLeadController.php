<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Services\EnquiryNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatLeadController extends Controller
{
    public function __construct(
        private readonly EnquiryNotificationService $notifications,
    ) {}

    public function __invoke(Request $request): RedirectResponse
    {
        if ($request->filled('_gotcha')) {
            return redirect()->back()->withFragment('after-hours-chat');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'enquiry' => ['required', 'string', 'max:1200'],
            'loan_type' => ['nullable', 'string', 'in:'.implode(',', array_keys(config('riskwisdom.loan_types')))],
        ], [
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'enquiry.required' => 'Please tell us what you need help with.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'chat')
                ->withInput()
                ->with('chat_open', true)
                ->withFragment('after-hours-chat');
        }

        $validated = $validator->validated();

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'chat_widget',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'loan_type' => $validated['loan_type'] ?? null,
            'timeline' => 'researching',
            'enquiry' => $validated['enquiry'],
            'source' => 'after_hours_chat',
            'marketing_consent' => $request->boolean('marketing_consent'),
            'ip_address' => $request->ip(),
        ]);

        $this->notifications->sendAfterResponse($enquiry);

        return redirect()
            ->route('thank-you')
            ->with('lead_type', 'chat_widget')
            ->with('enquiry_id', $enquiry->id);
    }
}
