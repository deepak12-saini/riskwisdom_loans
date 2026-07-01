@extends('layouts.conversion')

@section('title', $landing['title'])
@section('meta_description', $landing['meta_description'])
@section('canonical', $campaign === 'default' ? route('enquire.show') : route('enquire.campaign', ['campaign' => $campaign]))
@section('og_title', $landing['headline'].' | '.config('riskwisdom.brand_name'))
@section('og_description', $landing['subheadline'])

@section('content')
    @php
        $reviews = config('riskwisdom.google_reviews');
        $broker = $landing['broker'] ?? [
            'name' => config('riskwisdom.brand_name'),
            'tagline' => 'Mortgage broker guidance for Australian borrowers',
            'credential' => config('riskwisdom.legal_name'),
            'avatar' => $landing['image'],
            'avatar_alt' => $landing['image_alt'],
        ];
    @endphp

    <main class="rw-conversion">
        <section class="rw-conversion__hero" aria-labelledby="conversion-headline">
            <div class="container rw-conversion__shell">
                <div class="rw-conversion__hero-card">
                    <div class="rw-conversion__layout">
                        <div class="rw-conversion__story">
                            <div class="rw-conversion__story-top">
                                @if (! empty($landing['eyebrow']))
                                    <span class="rw-conversion__eyebrow">{{ $landing['eyebrow'] }}</span>
                                @endif

                                <div class="rw-conversion__profile">
                                    <div class="rw-conversion__profile-avatar">
                                        <img
                                            src="{{ asset($broker['avatar']) }}"
                                            alt="{{ $broker['avatar_alt'] }}"
                                            width="96"
                                            height="96"
                                            loading="eager"
                                            decoding="async"
                                        >
                                    </div>
                                    <div class="rw-conversion__profile-copy">
                                        <strong>{{ $broker['name'] }}</strong>
                                        <span>{{ $broker['tagline'] }}</span>
                                        <small>{{ $broker['credential'] }}</small>
                                    </div>
                                </div>

                                <h1 id="conversion-headline" class="rw-conversion__title">{{ $landing['headline'] }}</h1>
                                <p class="rw-conversion__lead">{{ $landing['subheadline'] }}</p>
                                @if (! empty($landing['subheadline_extra']))
                                    <p class="rw-conversion__lead rw-conversion__lead--extra">{{ $landing['subheadline_extra'] }}</p>
                                @endif

                                @if (! empty($landing['benefits']))
                                    <ul class="rw-conversion__benefits">
                                        @foreach ($landing['benefits'] as $benefit)
                                            <li>{{ $benefit }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div class="rw-conversion__stats" aria-label="Why enquire with Riskwisdom Loans">
                                    <div class="rw-conversion__stat">
                                        <strong>{{ count(config('riskwisdom.lender_panel.items', [])) }}</strong>
                                        <span>Lenders</span>
                                    </div>
                                    <div class="rw-conversion__stat">
                                        <strong>22 Years</strong>
                                        <span>Experience</span>
                                    </div>
                                    <div class="rw-conversion__stat">
                                        <strong>$0</strong>
                                        <span>Cost to you</span>
                                    </div>
                                    <div class="rw-conversion__stat">
                                        <strong>{{ number_format((float) ($reviews['rating'] ?? 5), 1) }}</strong>
                                        <span>Google review rating</span>
                                    </div>
                                </div>

                                <div class="rw-conversion__actions">
                                    @include('partials.phone-link', [
                                        'variant' => 'button',
                                        'label' => 'Call '.config('riskwisdom.phone'),
                                        'cta' => 'conversion-hero-phone',
                                    ])
                                    <a class="rw-button rw-button--outline rw-conversion__secondary-cta" href="#enquiry-form" data-cta="conversion-hero-form">
                                        Get free assessment
                                    </a>
                                </div>
                            </div>

                            @if ($landing['show_hero_image'] ?? true)
                                <div class="rw-conversion__visual">
                                    <img
                                        src="{{ asset($landing['image']) }}"
                                        alt="{{ $landing['image_alt'] }}"
                                        width="900"
                                        height="620"
                                        loading="eager"
                                        decoding="async"
                                    >
                                    <div class="rw-conversion__visual-badge">
                                        <strong>Fast callback</strong>
                                        <span>{{ config('riskwisdom.rate_review.callback_promise') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="rw-conversion__form-wrap">
                            @include('partials.conversion-enquiry-form', [
                                'landing' => $landing,
                                'campaign' => $campaign,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="rw-conversion__brands" aria-label="Lenders and finance partners">
            <div class="container">
                <p class="rw-conversion__brands-title">Trusted across major banks, challenger lenders, and specialist finance providers</p>
                <div class="rw-conversion__brands-row">
                    @foreach (config('riskwisdom.lender_panel.items', []) as $lender)
                        <span class="rw-conversion__brand-pill">{{ $lender }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-conversion__strip" aria-label="Trust signals">
            <div class="container rw-conversion__strip-inner">
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon rw-conversion__strip-icon--star" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2.5l2.76 5.59 6.17.9-4.47 4.35 1.06 6.15L12 16.9l-5.52 2.9 1.06-6.15-4.47-4.35 6.17-.9L12 2.5z"/>
                        </svg>
                    </span>
                    <div>
                        <strong>{{ number_format((float) ($reviews['rating'] ?? 5), 1) }} Google rating</strong>
                        <span>Trusted by Australian borrowers</span>
                    </div>
                </div>
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon rw-conversion__strip-icon--bolt" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 2.5 5.5 14h6.5l-1 7.5L18.5 10H12l1-7.5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div>
                        <strong>Fast callback</strong>
                        <span>{{ config('riskwisdom.rate_review.callback_promise') }}</span>
                    </div>
                </div>
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon rw-conversion__strip-icon--bank" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 10h18M5 10v8m4-8v8m6-8v8m4-8v8M2 10 20h20M4 10 12 4.5 20 10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div>
                        <strong>Major lender panel</strong>
                        <span>ANZ · CBA · Westpac · NAB + more</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
