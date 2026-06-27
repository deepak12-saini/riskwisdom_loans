<span class="rw-section-label">{{ $eyebrow }}</span>
<h1>{{ $heading }}</h1>
<p class="rw-page-lead">{{ $lead }}</p>

@if (! empty($bullets))
    <ul class="rw-page-bullets">
        @foreach ($bullets as $bullet)
            <li>{{ $bullet }}</li>
        @endforeach
    </ul>
@endif

@if (! empty($faqs))
    <div class="rw-faq">
        <h2>Frequently asked questions</h2>
        @foreach ($faqs as $faq)
            <details class="rw-faq__item">
                <summary>{{ $faq['question'] }}</summary>
                <p>{{ $faq['answer'] }}</p>
            </details>
        @endforeach
    </div>
@endif

<div class="rw-page-cta-band">
    <h2>{{ $ctaHeading ?? 'Ready for a free loan review?' }}</h2>
    <p>{{ $ctaCopy ?? 'Share your details and we will follow up with guidance tailored to your borrowing goals.' }}</p>
    <div class="rw-page-actions">
        <a class="rw-button rw-button--solid" href="{{ $ctaHref ?? contact_url($intent ?? null) }}" data-cta="landing-primary">{{ $ctaLabel ?? 'Get free loan review' }}</a>
        @include('partials.phone-link', [
            'variant' => 'button',
            'label' => 'Or call ' . config('riskwisdom.phone'),
            'cta' => 'landing-phone',
        ])
    </div>
</div>
