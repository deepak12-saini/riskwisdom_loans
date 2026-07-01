<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Services\EnquiryNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversionLandingController extends Controller
{
    public function __construct(
        private readonly EnquiryNotificationService $notifications,
    ) {}

    public function show(?string $campaign = null): View
    {
        $landing = $this->resolveLanding($campaign);

        return view('pages.conversion-landing', [
            'landing' => $landing,
            'campaign' => $landing['slug'],
        ]);
    }

    public function store(Request $request, ?string $campaign = null): RedirectResponse
    {
        $landing = $this->resolveLanding($campaign);
        $slug = $landing['slug'];

        if ($request->filled('_gotcha')) {
            return redirect()->route('enquire.show', ['campaign' => $slug === 'default' ? null : $slug]);
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
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
        ], [
            'loan_type.required' => 'Please tell us what you are looking for.',
            'timeline.required' => 'Please select when you are looking to proceed.',
            'state.required' => 'Please select your state.',
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'phone.required' => 'Please enter your phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'enquiry.required' => 'Please tell us what you need help with.',
        ]);

        $redirectRoute = $slug === 'default'
            ? route('enquire.show')
            : route('enquire.show', ['campaign' => $slug]);

        if ($validator->fails()) {
            return redirect()
                ->to($redirectRoute)
                ->withErrors($validator)
                ->withInput()
                ->withFragment('enquiry-form');
        }

        $validated = $validator->validated();

        $enquiry = Enquiry::create([
            'lead_type' => 'conversion',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'loan_type' => $validated['loan_type'],
            'timeline' => $validated['timeline'],
            'state' => $validated['state'],
            'enquiry' => $validated['enquiry'],
            'metadata' => [
                'campaign' => $slug,
                'campaign_label' => $landing['eyebrow'],
            ],
            'source' => 'conversion_'.$slug,
            'utm_source' => $validated['utm_source'] ?? null,
            'utm_medium' => $validated['utm_medium'] ?? null,
            'utm_campaign' => $validated['utm_campaign'] ?? null,
            'ip_address' => $request->ip(),
            'marketing_consent' => $request->boolean('marketing_consent'),
        ]);

        $this->notifications->sendAfterResponse($enquiry);

        return redirect()
            ->route('thank-you')
            ->with('lead_type', 'conversion')
            ->with('enquiry_id', $enquiry->id)
            ->with('utm_source', $validated['utm_source'] ?? null)
            ->with('utm_medium', $validated['utm_medium'] ?? null)
            ->with('utm_campaign', $validated['utm_campaign'] ?? null);
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveLanding(?string $campaign): array
    {
        $slug = $campaign ?: 'default';
        $landings = config('riskwisdom.conversion_landings', []);
        $landing = $landings[$slug] ?? null;

        if (! is_array($landing)) {
            throw new NotFoundHttpException();
        }

        $landing['slug'] = $slug;

        return $landing;
    }
}
