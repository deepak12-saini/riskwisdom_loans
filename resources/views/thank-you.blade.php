@extends('layouts.site')

@section('title', 'Thank You | Riskwisdom Loans')
@section('meta_description', 'Thank you for your enquiry. A Riskwisdom Loans broker will be in touch shortly.')
@section('canonical', route('thank-you'))
@section('meta_robots', 'noindex, follow')

@section('body_class', 'rw-page-thank-you')
@section('header_class', 'rw-header--static')

@section('content')
    @php
        $enquiry = session('enquiry_id')
            ? \App\Models\Enquiry::query()->find(session('enquiry_id'))
            : null;
        $mailFailed = $enquiry !== null && $enquiry->email_sent_at === null;
        $leadType = session('lead_type', 'contact');
        $guideDownloadUrl = is_array($enquiry?->metadata ?? null) ? ($enquiry->metadata['guide_download_url'] ?? null) : null;
        $guideTitle = is_array($enquiry?->metadata ?? null) ? ($enquiry->metadata['guide_title'] ?? null) : null;
    @endphp

    <main class="rw-thank-you">
        <section class="rw-thank-you__hero">
            <div class="rw-thank-you__hero-inner">
                <div class="rw-thank-you__success" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false">
                        <path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm-1.1 14.2-3.6-3.6 1.4-1.4 2.2 2.2 5.2-5.2 1.4 1.4z"/>
                    </svg>
                </div>

                <span class="rw-thank-you__label">Enquiry received</span>
                <h1>Thank you — we have your details.</h1>

                @if (session('mail_warning') || $mailFailed)
                    <p class="rw-form-alert rw-form-alert-error rw-thank-you__alert">
                        Your enquiry was saved, but our email notification could not be sent from the server.
                        We will still follow up using the details you provided — or call us on
                        @include('partials.phone-link', ['variant' => 'text', 'cta' => 'thank-you-call-inline']).
                    </p>
                @endif

                @if ($leadType === 'rate_review')
                    <p class="rw-thank-you__lead">
                        Your rate review request is in. {{ config('riskwisdom.rate_review.callback_promise') }}
                        If we miss you, we will email a summary of next steps.
                    </p>
                @elseif ($leadType === 'borrowing_power')
                    <p class="rw-thank-you__lead">
                        Your borrowing power enquiry is in. A broker will review your estimate and contact you to discuss next steps.
                    </p>
                @elseif ($leadType === 'guide_download')
                    <p class="rw-thank-you__lead">
                        Your guide is ready. Use the download button below now, and we will also send a copy to your email.
                    </p>
                @elseif ($leadType === 'chat_widget')
                    <p class="rw-thank-you__lead">
                        Thanks for your after-hours message. We will review it and follow up on the next business day.
                    </p>
                @else
                    <p class="rw-thank-you__lead">
                        A broker from Riskwisdom Loans will review your enquiry and contact you within 24 hours.
                        If your matter is urgent, call us directly.
                    </p>
                @endif

                <div class="rw-thank-you__actions">
                    @if ($leadType === 'guide_download' && $guideDownloadUrl)
                        <a class="rw-button rw-button--solid" href="{{ $guideDownloadUrl }}" target="_blank" rel="noreferrer" download>
                            Download {{ $guideTitle ?: 'your guide' }}
                        </a>
                    @endif
                    @include('partials.phone-link', [
                        'variant' => 'button-solid',
                        'label' => 'Call '.config('riskwisdom.phone'),
                        'cta' => 'thank-you-call',
                    ])
                    <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="thank-you-book">Book a call</a>
                    <a class="rw-button rw-button--outline rw-thank-you__outline" href="{{ route('home') }}" data-cta="thank-you-home">Back to homepage</a>
                </div>
            </div>
        </section>

        <section class="rw-thank-you__steps" aria-labelledby="thank-you-next-heading">
            <div class="rw-thank-you__steps-inner">
                <div class="rw-thank-you__steps-head">
                    <h2 id="thank-you-next-heading">What happens next</h2>
                    <p>Three simple steps from enquiry to clear advice.</p>
                </div>

                <ol class="rw-thank-you__step-grid">
                    <li class="rw-thank-you__step-card">
                        <span class="rw-thank-you__step-num" aria-hidden="true">1</span>
                        <h3>We review your enquiry</h3>
                        <p>Loan type, timeline, and goals you shared — so we understand your position before we call.</p>
                    </li>
                    <li class="rw-thank-you__step-card">
                        <span class="rw-thank-you__step-num" aria-hidden="true">2</span>
                        <h3>A broker calls or emails you</h3>
                        <p>We clarify your situation, answer initial questions, and confirm what you want to achieve.</p>
                    </li>
                    <li class="rw-thank-you__step-card">
                        <span class="rw-thank-you__step-num" aria-hidden="true">3</span>
                        <h3>You receive clear next steps</h3>
                        <p>Pre-approval, refinance comparison, or a tailored plan — without pressure or jargon.</p>
                    </li>
                </ol>
            </div>
        </section>

        <section class="rw-thank-you__band" aria-label="Need help sooner">
            <div class="rw-thank-you__band-inner">
                <div>
                    <h2>Need help sooner?</h2>
                    <p>Call us now or book a time that suits you.</p>
                </div>
                <div class="rw-thank-you__band-actions">
                    @include('partials.phone-link', [
                        'variant' => 'button-solid',
                        'label' => 'Call '.config('riskwisdom.phone'),
                        'cta' => 'thank-you-band-call',
                    ])
                    <a class="rw-button rw-button--outline rw-thank-you__band-outline" href="{{ route('book') }}" data-cta="thank-you-band-book">Book a call</a>
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

        if (typeof window.fbq === 'function') {
            window.fbq('track', 'Lead', {
                content_name: @json($leadType),
                utm_source: @json(session('utm_source')),
                utm_campaign: @json(session('utm_campaign')),
            });
        }
    </script>
@endpush
