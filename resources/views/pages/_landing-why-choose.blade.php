<section class="rw-landing-why" aria-labelledby="landing-why-title">
    <div class="rw-landing-why__grid">
        <div class="rw-landing-why__copy">
            <div class="rw-landing-why__copy-inner">
                <h2 id="landing-why-title">{{ $whyChooseHeading ?? 'Why choose us?' }}</h2>
                <p class="rw-landing-why__intro">{{ $whyChooseIntro ?? 'Self-employed, employee, tradie, first-home buyer? No problems.' }}</p>

                <div class="rw-landing-why__list-wrap">
                    <h3>{{ $whyChooseListTitle ?? 'A Riskwisdom home loan has:' }}</h3>
                    <ul class="rw-landing-why__list">
                        @foreach (($whyChooseBullets ?? $bullets ?? []) as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="rw-landing-why__actions">
                    <a class="rw-button rw-button--solid" href="{{ $ctaHref ?? contact_url($intent ?? null) }}" data-cta="landing-why-primary">{{ $ctaLabel ?? 'Get free loan review' }}</a>
                    @if (! empty($secondaryCtaHref))
                        <a class="rw-button rw-button--ghost" href="{{ $secondaryCtaHref }}" data-cta="landing-why-secondary">{{ $secondaryCtaLabel ?? 'Learn more' }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="rw-landing-why__media">
            <img
                src="{{ asset($heroImage ?? 'images/landing/home-loans-advisor.jpg') }}"
                alt="{{ $heroImageAlt ?? 'Mortgage specialist providing home loan guidance' }}"
                width="960"
                height="720"
                loading="eager"
                decoding="async"
            >
        </div>
    </div>
</section>
