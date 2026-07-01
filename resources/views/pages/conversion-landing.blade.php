@extends('layouts.conversion')

@section('title', $landing['title'])
@section('meta_description', $landing['meta_description'])
@section('canonical', $campaign === 'default' ? route('enquire.show') : route('enquire.campaign', ['campaign' => $campaign]))
@section('og_title', $landing['headline'].' | '.config('riskwisdom.brand_name'))
@section('og_description', $landing['subheadline'])

@section('content')
    @php
        $reviews = config('riskwisdom.google_reviews');
    @endphp

    <main class="rw-conversion">
        <section class="rw-conversion__hero" aria-labelledby="conversion-headline">
            <div class="container rw-conversion__shell">
                <div class="rw-conversion__layout">
                    <div class="rw-conversion__story">
                        <div class="rw-conversion__story-top">
                            <span class="rw-conversion__eyebrow">{{ $landing['eyebrow'] }}</span>

                            <div class="rw-conversion__rating" aria-label="{{ number_format((float) ($reviews['rating'] ?? 5), 1) }} out of 5 stars from Google reviews">
                                <span class="rw-conversion__stars" aria-hidden="true">★★★★★</span>
                                <strong>{{ number_format((float) ($reviews['rating'] ?? 5), 1) }}/5</strong>
                                <span>{{ number_format((int) ($reviews['count'] ?? 0)) }}+ Google reviews</span>
                            </div>

                            <h1 id="conversion-headline">{{ $landing['headline'] }}</h1>
                            <p class="rw-conversion__lead">{{ $landing['subheadline'] }}</p>

                            <ul class="rw-conversion__benefits">
                                @foreach ($landing['benefits'] as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="rw-conversion__visual">
                            <img
                                src="{{ asset($landing['image']) }}"
                                alt="{{ $landing['image_alt'] }}"
                                width="720"
                                height="540"
                                loading="eager"
                                decoding="async"
                            >
                            <div class="rw-conversion__visual-badge">
                                <strong>Free broker review</strong>
                                <span>No obligation · Australian borrowers</span>
                            </div>
                        </div>

                        <div class="rw-conversion__badges" aria-label="Why enquire with Riskwisdom Loans">
                            @foreach ($landing['trust_badges'] as $badge)
                                <div class="rw-conversion__badge">
                                    <strong>{{ $badge['label'] }}</strong>
                                    <span>{{ $badge['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rw-conversion__form-wrap">
                        @include('partials.conversion-enquiry-form', [
                            'landing' => $landing,
                            'campaign' => $campaign,
                        ])
                    </div>
                </div>
            </div>
        </section>

        <section class="rw-conversion__strip" aria-label="Trust signals">
            <div class="container rw-conversion__strip-inner">
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon" aria-hidden="true">★</span>
                    <div>
                        <strong>{{ number_format((float) ($reviews['rating'] ?? 5), 1) }} Google rating</strong>
                        <span>Trusted by Australian borrowers</span>
                    </div>
                </div>
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon" aria-hidden="true">⚡</span>
                    <div>
                        <strong>Fast callback</strong>
                        <span>{{ config('riskwisdom.rate_review.callback_promise') }}</span>
                    </div>
                </div>
                <div class="rw-conversion__strip-item">
                    <span class="rw-conversion__strip-icon" aria-hidden="true">🏦</span>
                    <div>
                        <strong>Major lender panel</strong>
                        <span>ANZ · CBA · Westpac · NAB + more</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
