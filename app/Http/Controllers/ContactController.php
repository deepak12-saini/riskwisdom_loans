<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReplyMail;
use App\Mail\ContactEnquiryMail;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('_gotcha')) {
            return redirect()->route('thank-you');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'loan_type' => ['required', 'string', 'in:'.implode(',', array_keys(config('riskwisdom.loan_types')))],
            'timeline' => ['required', 'string', 'in:'.implode(',', array_keys(config('riskwisdom.timelines')))],
            'state' => ['required', 'string', 'in:'.implode(',', array_keys(config('riskwisdom.states')))],
            'enquiry' => ['required', 'string', 'max:2000'],
            'source' => ['nullable', 'string', 'max:120'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
        ], [
            'loan_type.required' => 'Please select a loan type.',
            'timeline.required' => 'Please select when you are looking to proceed.',
            'state.required' => 'Please select your state.',
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'enquiry.required' => 'Please tell us about your finance goals.',
        ]);

        if ($validator->fails()) {
            return redirect()->to(route('home').'#contact')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        $enquiry = Enquiry::create([
            ...$validated,
            'ip_address' => $request->ip(),
        ]);

        $mailDetails = $enquiry->toMailDetails();
        $contactTo = (string) env('CONTACT_TO_ADDRESS', (string) config('mail.from.address'));

        try {
            Mail::to($contactTo)->send(new ContactEnquiryMail($mailDetails));
            $enquiry->update(['email_sent_at' => now()]);
        } catch (\Throwable $exception) {
            report($exception);

            // Lead is already saved — do not block the user with a misleading form error.
            return redirect()->route('thank-you')
                ->with('mail_warning', true);
        }

        try {
            Mail::to($enquiry->email)->send(new ContactAutoReplyMail($mailDetails));
            $enquiry->update(['auto_reply_sent_at' => now()]);
        } catch (\Throwable $exception) {
            report($exception);
        }

        return redirect()->route('thank-you');
    }
}
