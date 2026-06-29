@extends('layouts.site')

@section('title', 'Thank You | Riskwisdom Loans')
@section('meta_description', 'Thank you for your enquiry. A Riskwisdom Loans broker will be in touch shortly.')
@section('canonical', route('thank-you'))
@section('meta_robots', 'noindex, follow')

@section('body_class', 'rw-page-thank-you')

@section('content')
    @php
        $enquiry = session('enquiry_id')
            ? \App\Models\Enquiry::query()->find(session('enquiry_id'))
            : null;
        $mailFailed = $enquiry !== null && $enquiry->email_sent_at === null;
        $leadType = session('lead_type', 'contact');
    @endphp
    <main class="rw-page">
        <section class="rw-section rw-section--page">
            <div class="container rw-page-card">
                <span class="rw-section-label">Enquiry received</span>
                <h1>Thank you — we have your details.</h1>
                @if (session('mail_warning') || $mailFailed)
                    <p class="rw-form-alert rw-form-alert-error" style="margin-top: 1rem;">
                        Your enquiry was saved, but our email notification could not be sent from the server.
                        We will still follow up using the details you provided — or call us on
                        @include('partials.phone-link', ['variant' => 'text', 'cta' => 'thank-you-call-inline']).
                    </p>
                @endif
                @if ($leadType === 'rate_review')
                    <p>
                        Your rate review request is in. {{ config('riskwisdom.rate_review.callback_promise') }}
                        If we miss you, we will email a summary of next steps.
                    </p>
                @elseif ($leadType === 'borrowing_power')
                    <p>
                        Your borrowing power enquiry is in. A broker will review your estimate and contact you to discuss next steps.
                    </p>
                @else
                    <p>
                        A broker from Riskwisdom Loans will review your enquiry and contact you within 24 hours.
                        If your matter is urgent, call us directly.
                    </p>
                @endif

                <div class="rw-page-actions">
                    @include('partials.phone-link', [
                        'variant' => 'button-solid',
                        'label' => 'Call ' . config('riskwisdom.phone'),
                        'cta' => 'thank-you-call',
                    ])
                    <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="thank-you-book">Book a call</a>
                    <a class="rw-button rw-button--outline" href="{{ route('home') }}" data-cta="thank-you-home">Back to homepage</a>
                </div>

                <div class="rw-steps">
                    <h2>What happens next</h2>
                    <ol>
                        <li><strong>We review your enquiry</strong> — loan type, timeline, and goals you shared.</li>
                        <li><strong>A broker calls or emails you</strong> — to clarify your position and answer initial questions.</li>
                        <li><strong>You receive clear next steps</strong> — whether that is pre-approval, refinance comparison, or a tailored plan.</li>
                    </ol>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: 'generate_lead',
            lead_type: @json($leadType),
            utm_source: @json(session('utm_source')),
            utm_medium: @json(session('utm_medium')),
            utm_campaign: @json(session('utm_campaign')),
        });
    </script>
@endpush
