<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSignup;
use App\Services\MailchimpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsletterSignupController extends Controller
{
    public function __invoke(Request $request, MailchimpService $mailchimp): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
        ], [
            'first_name.required' => 'Please enter your first name for newsletter updates.',
            'email.required' => 'Please enter your email address for newsletter updates.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'newsletter')
                ->withInput()
                ->withFragment('newsletter-signup');
        }

        $validated = $validator->validated();

        $signup = NewsletterSignup::query()->updateOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'first_name' => $validated['first_name'],
                'source' => 'newsletter_footer',
                'mailchimp_sync_error' => null,
            ]
        );

        if ($mailchimp->isConfigured()) {
            try {
                $mailchimp->subscribeContact(
                    $signup->email,
                    [
                        'FNAME' => $signup->first_name,
                    ],
                    [config('riskwisdom.newsletter.tag', 'newsletter')]
                );

                $signup->update([
                    'mailchimp_synced_at' => now(),
                    'mailchimp_sync_error' => null,
                ]);
            } catch (\Throwable $exception) {
                report($exception);

                $signup->update([
                    'mailchimp_sync_error' => Str::limit($exception->getMessage(), 1000),
                ]);
            }
        }

        return redirect()->back()
            ->with('newsletter_status', 'Thanks — you are subscribed for rate updates and home loan tips.')
            ->withFragment('newsletter-signup');
    }
}
