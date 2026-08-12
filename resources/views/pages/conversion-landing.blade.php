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
                        @php
                            $name = is_array($lender) ? ($lender['name'] ?? '') : (string) $lender;
                            $logo = is_array($lender) ? ($lender['logo'] ?? null) : null;
                        @endphp
                        <span class="rw-conversion__brand-pill">
                            @if ($logo)
                                <img
                                    class="rw-conversion__brand-logo"
                                    src="{{ asset($logo) }}"
                                    alt="{{ $name }}"
                                    width="110"
                                    height="32"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                {{ $name }}
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-conversion__strip" aria-label="Trust signals">
            <div class="container rw-conversion__strip-inner">
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon rw-conversion__strip-icon--star" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M12 2.8l2.7 5.5 6.1.9-4.4 4.3 1 6.1L12 16.8l-5.4 2.8 1-6.1-4.4-4.3 6.1-.9L12 2.8z"/>
                        </svg>
                    </span>
                    <div class="rw-conversion__strip-copy">
                        <strong>{{ number_format((float) ($reviews['rating'] ?? 5), 1) }} Google rating</strong>
                        <p>Trusted by Australian borrowers</p>
                    </div>
                </div>
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon rw-conversion__strip-icon--bolt" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M13 2 6 13h5l-1 9 8-12h-5l0-8z"/>
                        </svg>
                    </span>
                    <div class="rw-conversion__strip-copy">
                        <strong>Fast callback</strong>
                        <p>{{ config('riskwisdom.rate_review.callback_promise') }}</p>
                    </div>
                </div>
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon rw-conversion__strip-icon--bank" aria-hidden="true">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path fill="currentColor" d="M12 3 3 8v2h18V8L12 3zm-7 8v6H4v2h16v-2h-1v-6h-2v6h-3v-6h-2v6H9v-6H7zm-3 10h20v2H2v-2z"/>
                        </svg>
                    </span>
                    <div class="rw-conversion__strip-copy">
                        <strong>Major lender panel</strong>
                        <p>ANZ · CBA · Westpac · NAB + more</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
