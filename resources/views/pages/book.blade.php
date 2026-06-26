@if (calendly_embed_url())
    @push('head')
        <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript"></script>
        @include('partials.calendly-inline-init')
    @endpush
@endif

@extends('layouts.site')

@section('title', 'Book a Call | Riskwisdom Loans')
@section('meta_description', 'Book a free 15-minute phone call with a Riskwisdom Loans broker. Pick a time that suits you — synced straight to our calendar.')
@section('canonical', route('book'))
@section('body_class', 'rw-book-page')
@section('header_class', 'rw-header--static')

@section('content')
    <main class="rw-book">
        <section class="rw-book__hero">
            <div class="container rw-book__hero-inner">
                <span class="rw-section-label">Book a call</span>
                <h1>Book a free 15-minute phone call</h1>
                <p class="rw-book__lead">
                    Pick a time below. We will call you, review your goals, and outline clear next steps for your home loan, refinance, or finance enquiry.
                </p>
            </div>
        </section>

        <section class="rw-book__main">
            <div class="container rw-book__grid">
                <aside class="rw-book__aside" aria-label="What to expect">
                    <div class="rw-book__card">
                        <h2>What to expect</h2>
                        <ul class="rw-book__list">
                            <li>
                                <span class="rw-book__list-icon" aria-hidden="true">📞</span>
                                <span><strong>15 minutes</strong> — phone call with a broker</span>
                            </li>
                            <li>
                                <span class="rw-book__list-icon" aria-hidden="true">🎯</span>
                                <span>Review your goals, timeline, and borrowing position</span>
                            </li>
                            <li>
                                <span class="rw-book__list-icon" aria-hidden="true">✓</span>
                                <span>Clear next steps — no obligation, no pressure</span>
                            </li>
                        </ul>
                    </div>

                    <div class="rw-book__card rw-book__card--soft">
                        <h3>Prefer to talk now?</h3>
                        <p>Call us directly and we will help straight away.</p>
                        <a class="rw-button rw-button--outline rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}" data-cta="book-page-phone">
                            {{ config('riskwisdom.phone') }}
                        </a>
                    </div>

                    <p class="rw-book__note">
                        Your booking is confirmed instantly and synced to our calendar. You will receive email reminders from Calendly.
                    </p>
                </aside>

                <div class="rw-book__scheduler">
                    @if (calendly_embed_url())
                        <div class="rw-book__embed-wrap">
                            <div class="rw-book__embed-header">
                                <h2>Select a date &amp; time</h2>
                                <p>Times shown in your local timezone.</p>
                            </div>

                            <div
                                id="rw-calendly-loader"
                                class="rw-book__loader"
                                role="status"
                                aria-live="polite"
                                aria-label="Loading calendar"
                            >
                                <div class="rw-book__loader-skeleton" aria-hidden="true"></div>
                                <p>Loading available times…</p>
                            </div>

                            <div
                                id="rw-calendly-mount"
                                class="rw-book__embed"
                                data-url="{{ calendly_embed_url() }}"
                            ></div>
                        </div>
                    @else
                        <div class="rw-book__card">
                            <h2>Online booking unavailable</h2>
                            <p>
                                Please <a href="{{ contact_url() }}">contact us</a> or call
                                <a class="rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}">{{ config('riskwisdom.phone') }}</a>.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
