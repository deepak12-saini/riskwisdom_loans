@extends('layouts.page')

@section('title', 'Guides & Insights | Riskwisdom Loans')
@section('meta_description', 'Practical home loan guides for Australian borrowers — refinancing, first home buyers, borrowing power, and investment lending.')
@section('canonical', route('guides.index'))

@section('page_content')
    <div class="rw-guides">
        <header class="rw-guides__intro">
            <span class="rw-section-label">Guides & Insights</span>
            <h1>Finance guides for Australian borrowers</h1>
            <p class="rw-page-lead">Practical articles and downloadable guides to help property owners and buyers make clearer lending decisions.</p>
        </header>

        <section class="rw-guides__section" aria-labelledby="download-guides-heading">
            <div class="rw-guides__section-head">
                <h2 id="download-guides-heading">Downloadable guides</h2>
                <p>Free PDFs and checklists you can keep and share.</p>
            </div>

            <div class="rw-guides__grid rw-guides__grid--downloads">
                @foreach (config('riskwisdom.download_guides') as $slug => $guide)
                    <a class="rw-guide-card rw-guide-card--download" href="{{ route('guides.download.show', $slug) }}">
                        <div class="rw-guide-card__media">
                            <img
                                src="{{ asset($guide['image'] ?? 'images/landing/home-loans-advisor.jpg') }}"
                                alt="{{ $guide['image_alt'] ?? $guide['title'] }}"
                                width="640"
                                height="400"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                        <div class="rw-guide-card__body">
                            <h3>{{ $guide['title'] }}</h3>
                            <p>{{ $guide['description'] }}</p>
                            <span class="rw-guide-card__cta">{{ $guide['cta'] ?? 'Get guide' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="rw-guides__section" aria-labelledby="articles-heading">
            <div class="rw-guides__section-head">
                <h2 id="articles-heading">Latest articles</h2>
                <p>Clear, practical advice for refinance, first home buyers, and investors.</p>
            </div>

            <div class="rw-guides__grid">
                @foreach ($posts as $post)
                    <a class="rw-guide-card" href="{{ route('guides.show', $post['slug']) }}">
                        <div class="rw-guide-card__media">
                            <img
                                src="{{ asset($post['image']) }}"
                                alt="{{ $post['image_alt'] }}"
                                width="640"
                                height="400"
                                loading="lazy"
                                decoding="async"
                            >
                            <span class="rw-guide-card__badge rw-guide-card__badge--article">{{ $post['category'] }}</span>
                        </div>
                        <div class="rw-guide-card__body">
                            <time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('j M Y') }}</time>
                            <h3>{{ $post['title'] }}</h3>
                            <p>{{ $post['excerpt'] }}</p>
                            <span class="rw-guide-card__cta">Read article</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
@endsection
