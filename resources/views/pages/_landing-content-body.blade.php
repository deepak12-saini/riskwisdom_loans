@if (! empty($relatedLinks))
    <div class="rw-page-related">
        <h2>Related tools &amp; guides</h2>
        <ul class="rw-page-bullets">
            @foreach ($relatedLinks as $link)
                <li><a href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
            @endforeach
        </ul>
    </div>
@endif

@include('partials.how-it-works')
@include('partials.google-reviews')
@include('partials.lender-logos')

<div class="rw-page-cta-band">
    <h2>{{ $ctaHeading ?? 'Ready for a free loan review?' }}</h2>
    <p>{{ $ctaCopy ?? 'Share your details and we will follow up with guidance tailored to your borrowing goals.' }}</p>
    <div class="rw-page-actions">
        <a class="rw-button rw-button--solid" href="{{ $ctaHref ?? contact_url($intent ?? null) }}" data-cta="landing-primary">{{ $ctaLabel ?? 'Get free loan review' }}</a>
        @if (! empty($secondaryCtaHref))
            <a class="rw-button rw-button--outline" href="{{ $secondaryCtaHref }}" data-cta="landing-secondary">{{ $secondaryCtaLabel ?? 'Learn more' }}</a>
        @endif
        @include('partials.phone-link', [
            'variant' => 'button',
            'label' => 'Or call ' . config('riskwisdom.phone'),
            'cta' => 'landing-phone',
        ])
    </div>
</div>
