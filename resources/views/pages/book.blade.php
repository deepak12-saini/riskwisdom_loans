@if (calendly_embed_url())
    @push('head')
        <link rel="preconnect" href="https://assets.calendly.com" crossorigin>
        <link rel="preconnect" href="https://calendly.com" crossorigin>
        <link rel="dns-prefetch" href="https://assets.calendly.com">
        <link rel="dns-prefetch" href="https://calendly.com">
        <link rel="preload" href="https://assets.calendly.com/assets/external/widget.js" as="script">
        <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
        <script src="https://assets.calendly.com/assets/external/widget.js" async></script>
        @include('partials.calendly-inline-init')
    @endpush
@endif

@extends('layouts.site')

@section('title', 'Book a Call | Riskwisdom Loans')
@section('meta_description', 'Book a free 15-minute phone call with a Riskwisdom Loans broker. Pick a time that suits you — synced straight to our calendar.')
@section('canonical', route('book'))
@section('body_class', 'rw-book-page')
@section('header_class', 'rw-header--static')
@section('sticky_variant', 'call-only')

@section('content')
    <main class="rw-book">
        <section class="rw-book__hero">
            <div class="container rw-book__hero-inner">
                <span class="rw-section-label">Book a call</span>
                <h1>Book a free 15-minute phone call</h1>
                <p class="rw-book__lead">
                    Pick a time below. We will call you, review your goals, and outline clear next steps for your home loan, refinance, or finance enquiry.
                </p>

                <ol class="rw-book__steps" aria-label="Booking steps">
                    <li class="rw-book__steps-item is-active">
                        <span class="rw-book__steps-num">1</span>
                        <span>Pick a time</span>
                    </li>
                    <li class="rw-book__steps-item">
                        <span class="rw-book__steps-num">2</span>
                        <span>Confirm details</span>
                    </li>
                    <li class="rw-book__steps-item">
                        <span class="rw-book__steps-num">3</span>
                        <span>We call you</span>
                    </li>
                </ol>
            </div>
        </section>

        <section class="rw-book__main">
            <div class="container rw-book__grid">
                <aside class="rw-book__aside" aria-label="What to expect">
                    <div class="rw-book__card">
                        <h2>What to expect</h2>
                        <ul class="rw-book__list">
                            <li>
                                <span class="rw-book__list-icon rw-book__list-icon--phone" aria-hidden="true"></span>
                                <span><strong>15 minutes</strong> — phone call with a broker</span>
                            </li>
                            <li>
                                <span class="rw-book__list-icon rw-book__list-icon--target" aria-hidden="true"></span>
                                <span>Review your goals, timeline, and borrowing position</span>
                            </li>
                            <li>
                                <span class="rw-book__list-icon rw-book__list-icon--check" aria-hidden="true"></span>
                                <span>Clear next steps — no obligation, no pressure</span>
                            </li>
                        </ul>
                    </div>

                    <div class="rw-book__card rw-book__card--soft">
                        <h3>Prefer to talk now?</h3>
                        <p>Call us directly and we will help straight away.</p>
                        @include('partials.phone-link', [
                            'variant' => 'button-solid',
                            'label' => config('riskwisdom.phone'),
                            'cta' => 'book-page-phone',
                            'wide' => true,
                        ])
                    </div>

                    <div class="rw-book__trust">
                        <div class="rw-book__trust-item">
                            <strong>Instant confirmation</strong>
                            <span>Synced to our calendar</span>
                        </div>
                        <div class="rw-book__trust-item">
                            <strong>Email reminders</strong>
                            <span>Before your call</span>
                        </div>
                    </div>
                </aside>

                <div class="rw-book__scheduler">
                    @if (calendly_embed_url())
                        <div @class([
                            'rw-book__embed-wrap',
                            'rw-book__embed-wrap--hide-brand' => calendly_hide_branding(),
                        ])>
                            <div class="rw-book__embed-header">
                                <div>
                                    <h2>Select a date &amp; time</h2>
                                    <p>Times shown in your local timezone.</p>
                                </div>
                                <span class="rw-book__embed-badge" data-calendly-badge>Loading calendar…</span>
                            </div>

                            <div
                                id="rw-calendly-loader"
                                class="rw-book__loader"
                                role="status"
                                aria-live="polite"
                                aria-label="Loading calendar"
                            >
                                <div class="rw-book__loader-skeleton" aria-hidden="true">
                                    <div class="rw-book__loader-toolbar"></div>
                                    <div class="rw-book__loader-week">
                                        @for ($i = 0; $i < 7; $i++)
                                            <span></span>
                                        @endfor
                                    </div>
                                    <div class="rw-book__loader-grid">
                                        @for ($i = 0; $i < 35; $i++)
                                            <span></span>
                                        @endfor
                                    </div>
                                </div>
                                <p>Fetching live availability from Calendly…</p>
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
                                @include('partials.phone-link', ['variant' => 'text', 'cta' => 'book-page-phone-fallback']).
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
