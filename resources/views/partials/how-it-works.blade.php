@php
    $section = config('riskwisdom.how_it_works');
    $steps = $section['steps'] ?? [];
@endphp

<section class="rw-how-it-works" aria-labelledby="how-it-works-title">
    <div class="rw-how-it-works__header">
        <span class="rw-section-label">{{ $section['eyebrow'] ?? 'How it works' }}</span>
        <h2 id="how-it-works-title">{{ $section['heading'] ?? 'How we help you move forward.' }}</h2>
    </div>

    <div class="rw-how-it-works__grid">
        @foreach ($steps as $index => $step)
            <article class="rw-how-it-works__card">
                <div class="rw-how-it-works__media">
                    <img
                        src="{{ asset($step['image']) }}"
                        alt=""
                        width="320"
                        height="200"
                        loading="lazy"
                        decoding="async"
                    >
                    <span class="rw-how-it-works__step" aria-hidden="true">{{ $index + 1 }}</span>
                </div>
                <h3>{{ $step['title'] }}</h3>
                <p>{!! $step['description'] !!}</p>
            </article>
        @endforeach
    </div>
</section>
