@php
    $reviews = config('riskwisdom.google_reviews');
@endphp

<section class="rw-reviews" aria-labelledby="google-reviews-title">
    <div class="rw-reviews__intro">
        <span class="rw-section-label">Google Reviews</span>
        <h2 id="google-reviews-title">See what borrowers say about working with Riskwisdom Loans.</h2>
        <p>Social proof matters when you are choosing a broker. Explore our latest Google feedback and see why borrowers trust us for clear guidance and responsive support.</p>
        <div class="rw-reviews__meta">
            <strong>{{ number_format((float) ($reviews['rating'] ?? 5), 1) }}/5</strong>
            <span>based on {{ number_format((int) ($reviews['count'] ?? 0)) }} Google reviews</span>
        </div>
        <a class="rw-button rw-button--outline" href="{{ $reviews['profile_url'] ?? '#' }}" target="_blank" rel="noreferrer">Read Google reviews</a>
    </div>

    <div class="rw-reviews__panel">
        @if (! empty($reviews['widget_embed']))
            {!! $reviews['widget_embed'] !!}
        @else
            @foreach (($reviews['highlights'] ?? []) as $highlight)
                <article class="rw-review-card">
                    <div class="rw-review-card__stars" aria-hidden="true">★★★★★</div>
                    <p>“{{ $highlight['quote'] }}”</p>
                    <strong>{{ $highlight['author'] }}</strong>
                </article>
            @endforeach
        @endif
    </div>
</section>
