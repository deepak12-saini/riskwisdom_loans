<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Services\EnquiryNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuideDownloadController extends Controller
{
    public function __construct(
        private readonly EnquiryNotificationService $notifications,
    ) {}

    public function show(string $slug): View
    {
        $guide = $this->guideOrFail($slug);

        return view('pages.download-guide', [
            'guide' => $guide,
            'slug' => $slug,
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        if ($request->filled('_gotcha')) {
            return redirect()->route('guides.download.show', $slug);
        }

        $guide = $this->guideOrFail($slug);

        $validator = Validator::make($request->all(), [
            'first_name' => lead_name_rules(),
            'last_name' => lead_name_rules(),
            'email' => lead_email_rules(),
            'phone_country_code' => lead_phone_country_code_rules(),
            'phone' => lead_phone_rules(),
            'state' => ['nullable', 'string', 'in:'.implode(',', array_keys(config('riskwisdom.states')))],
        ], [
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'phone_country_code.required' => 'Please select a country code.',
        ]);

        apply_lead_identity_checks($validator);

        if ($validator->fails()) {
            return redirect()
                ->route('guides.download.show', $slug)
                ->withErrors($validator)
                ->withInput()
                ->withFragment('guide-download-form');
        }

        $validated = normalize_validated_lead_phone($validator->validated());

        $enquiry = Enquiry::query()->create([
            'lead_type' => 'guide_download',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'loan_type' => $guide['loan_type'] ?? null,
            'timeline' => $guide['timeline'] ?? 'researching',
            'state' => $validated['state'] ?? null,
            'enquiry' => 'Guide download request: '.$guide['title'],
            'metadata' => [
                'guide_slug' => $slug,
                'guide_title' => $guide['title'],
                'guide_file' => $guide['file'],
                'guide_tag' => $guide['tag'],
                'guide_download_url' => asset($guide['file']),
            ],
            'source' => 'guide_download',
            'marketing_consent' => true,
            'ip_address' => $request->ip(),
        ]);

        $this->notifications->sendAfterResponse($enquiry);

        return redirect()
            ->route('thank-you')
            ->with('lead_type', 'guide_download')
            ->with('enquiry_id', $enquiry->id)
            ->with('guide_slug', $slug);
    }

    /**
     * @return array<string, mixed>
     */
    private function guideOrFail(string $slug): array
    {
        $guide = config('riskwisdom.download_guides.'.$slug);

        if (! is_array($guide)) {
            throw new NotFoundHttpException();
        }

        return $guide;
    }
}
