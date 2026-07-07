@extends('layouts.site')

@section('title', $post['title'].' | Riskwisdom Loans')
@section('meta_description', $post['excerpt'])
@section('canonical', route('guides.show', $post['slug']))
@section('header_class', 'rw-header--static')
@section('body_class', 'rw-page-guide-article')

@section('content')
    <main class="rw-guide-article">
        <section class="rw-guide-article__hero">
            <div class="rw-guide-article__hero-inner">
                <div class="rw-guide-article__story">
                    <a class="rw-guide-article__back" href="{{ route('guides.index') }}">&larr; All guides</a>
                    <div class="rw-guide-article__meta">
                        <span class="rw-guide-article__category">{{ $post['category'] }}</span>
                        <time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('j F Y') }}</time>
                    </div>
                    <h1>{{ $post['title'] }}</h1>
                    <p class="rw-guide-article__excerpt">{{ $post['excerpt'] }}</p>
                </div>

                <div class="rw-guide-article__media">
                    <img
                        src="{{ asset($post['image']) }}"
                        alt="{{ $post['image_alt'] }}"
                        width="720"
                        height="480"
                        loading="eager"
                        decoding="async"
                    >
                </div>
            </div>
        </section>

        <section class="rw-guide-article__body-section">
            <article class="rw-guide-article__body">
                @if ($intro !== '')
                    <div class="rw-guide-article__intro">
                        {!! $intro !!}
                    </div>
                @endif

                @if ($sections !== [])
                    <div class="rw-guide-article__accordion" data-guide-accordion>
                        @foreach ($sections as $index => $section)
                            <details class="rw-guide-article__panel" @if ($index === 0) open @endif>
                                <summary>
                                    <span class="rw-guide-article__panel-title">{{ $section['title'] }}</span>
                                    <span class="rw-guide-article__panel-toggle" aria-hidden="true"></span>
                                </summary>
                                <div class="rw-guide-article__panel-body">
                                    {!! $section['body'] !!}
                                </div>
                            </details>
                        @endforeach
                    </div>
                @endif
            </article>
        </section>

        @if (! empty($related))
            <section class="rw-guide-article__related" aria-labelledby="related-guides-heading">
                <div class="rw-guide-article__related-inner">
                    <div class="rw-guide-article__related-head">
                        <h2 id="related-guides-heading">More guides</h2>
                        <p>Keep reading for clearer lending decisions.</p>
                    </div>

                    <div class="rw-guide-article__related-grid">
                        @foreach ($related as $item)
                            <a class="rw-guide-card" href="{{ route('guides.show', $item['slug']) }}">
                                <div class="rw-guide-card__media">
                                    <img
                                        src="{{ asset($item['image']) }}"
                                        alt="{{ $item['image_alt'] }}"
                                        width="640"
                                        height="400"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                    <span class="rw-guide-card__badge rw-guide-card__badge--article">{{ $item['category'] }}</span>
                                </div>
                                <div class="rw-guide-card__body">
                                    <time datetime="{{ $item['date'] }}">{{ \Illuminate\Support\Carbon::parse($item['date'])->format('j M Y') }}</time>
                                    <h3>{{ $item['title'] }}</h3>
                                    <p>{{ $item['excerpt'] }}</p>
                                    <span class="rw-guide-card__cta">Read article</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="rw-guide-article__cta" aria-label="Personalised advice">
            <div class="rw-guide-article__cta-inner">
                <div>
                    <h2>Want personalised advice?</h2>
                    <p>Book a free consultation and get guidance tailored to your borrowing goals.</p>
                </div>
                <div class="rw-guide-article__cta-actions">
                    <a class="rw-button rw-button--solid" href="{{ contact_url() }}" data-cta="guide-cta">Get free loan review</a>
                    <a class="rw-button rw-button--outline rw-guide-article__cta-outline" href="{{ route('book') }}" data-cta="guide-book">Book a call</a>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-guide-accordion]').forEach((accordion) => {
            accordion.querySelectorAll('details').forEach((panel) => {
                panel.addEventListener('toggle', () => {
                    if (! panel.open) {
                        return;
                    }

                    accordion.querySelectorAll('details').forEach((other) => {
                        if (other !== panel) {
                            other.open = false;
                        }
                    });
                });
            });
        });
    </script>
@endpush
