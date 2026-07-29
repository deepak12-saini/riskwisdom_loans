<section class="rw-conversion__hero rw-about-broker" aria-labelledby="about-broker-headline">
    <div class="container rw-conversion__shell">
        <div class="rw-conversion__hero-card">
            <div class="rw-conversion__layout">
                <div class="rw-conversion__story">
                    <div class="rw-conversion__story-top">
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

                        <h1 id="about-broker-headline" class="rw-conversion__title">{{ $brokerHeadline }}</h1>
                        <p class="rw-conversion__lead">{{ $brokerLead }}</p>
                        @if (! empty($brokerLeadExtra))
                            <p class="rw-conversion__lead rw-conversion__lead--extra">{{ $brokerLeadExtra }}</p>
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
                                <strong>{{ number_format((float) (config('riskwisdom.google_reviews.rating') ?? 5), 1) }}</strong>
                                <span>Google review rating</span>
                            </div>
                        </div>

                        <div class="rw-conversion__actions">
                            @include('partials.phone-link', [
                                'variant' => 'button',
                                'label' => 'Call '.config('riskwisdom.phone'),
                                'cta' => 'about-broker-phone',
                            ])
                            <a class="rw-button rw-button--outline rw-conversion__secondary-cta" href="#enquiry-form" data-cta="about-broker-form">
                                Get free assessment
                            </a>
                        </div>
                    </div>
                </div>

                <div class="rw-conversion__form-wrap">
                    @include('partials.conversion-enquiry-form', [
                        'landing' => $brokerLanding,
                        'campaign' => $brokerCampaign,
                    ])
                </div>
            </div>
        </div>
    </div>
</section>
