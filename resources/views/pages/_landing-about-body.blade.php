<section class="rw-about-story" aria-labelledby="about-story-title">
    <div class="rw-about-story__intro">
        <h2 id="about-story-title">Our approach to lending</h2>
        <p>{{ config('riskwisdom.about.story.0') }}</p>
        <p>{{ config('riskwisdom.about.story.1') }}</p>
    </div>

    <div class="rw-about-principles">
        @foreach (config('riskwisdom.about.principles', []) as $principle)
            <article class="rw-about-principles__card">
                <span class="rw-about-principles__icon" aria-hidden="true">✓</span>
                <p>{{ $principle }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="rw-lead-actions" aria-labelledby="lead-actions-title">
    <div class="rw-lead-actions__header">
        <h2 id="lead-actions-title">Take the next step in under a minute</h2>
        <p>Choose the option that fits you best — we respond quickly and keep the process clear.</p>
    </div>

    <div class="rw-lead-actions__grid">
        <a class="rw-lead-actions__card" href="{{ route('book') }}" data-cta="about-book-call">
            <strong>Book a free call</strong>
            <span>Pick a time that suits you and speak with a broker directly.</span>
            <em>15-minute phone consult</em>
        </a>

        <a class="rw-lead-actions__card" href="{{ route('rate-review') }}" data-cta="about-rate-review">
            <strong>Free rate review</strong>
            <span>Find out if your current home loan rate is still competitive.</span>
            <em>Fast callback promise</em>
        </a>

        <a class="rw-lead-actions__card" href="{{ route('guides.download.show', 'first-home-buyers-guide') }}" data-cta="about-download-guide">
            <strong>Download a guide</strong>
            <span>Get practical first-home-buyer tips in your inbox instantly.</span>
            <em>Free PDF guide</em>
        </a>

        <a class="rw-lead-actions__card" href="{{ contact_url() }}" data-cta="about-send-enquiry">
            <strong>Send an enquiry</strong>
            <span>Tell us your goals and we will follow up with tailored guidance.</span>
            <em>Reply within business hours</em>
        </a>
    </div>
</section>
