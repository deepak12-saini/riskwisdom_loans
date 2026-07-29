@extends('layouts.site')

@php
    $faqs = [
        ['question' => 'What documents do first home buyers need?', 'answer' => 'Typically ID, payslips or tax returns, bank statements, savings history, and details of assets and liabilities. Requirements vary by lender and employment type.'],
        ['question' => 'Can I buy with less than a 20% deposit?', 'answer' => 'Often yes, though lenders mortgage insurance may apply. Government schemes and guarantor options may also be available depending on eligibility.'],
        ['question' => 'Should I get pre-approval before inspecting properties?', 'answer' => 'Pre-approval is strongly recommended. It clarifies your budget and helps you act quickly when you find the right property.'],
        ['question' => 'How much can I borrow as a first home buyer?', 'answer' => 'It depends on income, expenses, deposit, and lender policy. Use our borrowing power calculator for a guide, then speak with a broker for an accurate assessment.'],
    ];

    $journey = [
        [
            'step' => '01',
            'title' => 'Deposit & budget',
            'copy' => 'Work out a realistic deposit, borrowing range, and what purchase price feels comfortable.',
            'image' => 'images/landing/home-loans-advisor.jpg',
            'image_alt' => 'First home buyer reviewing deposit and budget options',
        ],
        [
            'step' => '02',
            'title' => 'Pre-approval',
            'copy' => 'Get clear on your borrowing capacity before you make an offer, so you can shop with confidence.',
            'image' => 'images/landing/about-broker-team.jpg',
            'image_alt' => 'Broker helping a first home buyer with pre-approval',
        ],
        [
            'step' => '03',
            'title' => 'Grants & schemes',
            'copy' => 'Understand first home owner grants, stamp duty concessions, and scheme eligibility in plain English.',
            'image' => 'images/landing/refinance-advisor.jpg',
            'image_alt' => 'Homebuyer reviewing grants and stamp duty guidance',
        ],
        [
            'step' => '04',
            'title' => 'Offer to settlement',
            'copy' => 'From application through lender conditions and settlement, we keep each step clear and moving.',
            'image' => 'images/landing/investment-property-advisor.jpg',
            'image_alt' => 'First home buyer moving toward settlement',
        ],
    ];

    $resources = [
        [
            'title' => 'Borrowing power calculator',
            'copy' => 'Estimate how much you may be able to borrow based on income and expenses.',
            'href' => route('tools.borrowing-power'),
            'cta' => 'Check borrowing power',
            'image' => 'images/landing/home-loans-advisor.jpg',
            'image_alt' => 'Borrowing power calculator for first home buyers',
        ],
        [
            'title' => 'Stamp duty estimator',
            'copy' => 'Get a guide estimate of stamp duty and government charges by state.',
            'href' => route('tools.stamp-duty'),
            'cta' => 'Estimate stamp duty',
            'image' => 'images/landing/about-broker-team.jpg',
            'image_alt' => 'Stamp duty calculator for property purchase',
        ],
        [
            'title' => 'First home buyer checklist',
            'copy' => 'A practical guide covering documents, deposits, and what to do before you make an offer.',
            'href' => route('guides.show', 'first-home-buyer-checklist-australia'),
            'cta' => 'Read the checklist',
            'image' => 'images/landing/refinance-advisor.jpg',
            'image_alt' => 'First home buyer checklist guide',
        ],
        [
            'title' => 'Download the FHB guide',
            'copy' => 'Get the First Home Buyer’s Guide sent to your inbox with clear next steps.',
            'href' => route('guides.download.show', 'first-home-buyers-guide'),
            'cta' => 'Get the free guide',
            'image' => 'images/landing/partners-referral.jpg',
            'image_alt' => 'Downloadable first home buyer guide',
        ],
    ];
@endphp

@section('title', 'First Home Buyer Loans Australia | Riskwisdom Loans')
@section('meta_description', 'First home buyer loans Australia — deposits, pre-approval, grants, and step-by-step broker guidance for your first property purchase.')
@section('canonical', route('pages.first-home-buyer'))
@section('header_class', 'rw-header--static')
@section('body_class', 'rw-page-fhb')

@section('content')
    <main class="rw-fhb">
        <section class="rw-fhb__hero">
            <div class="rw-fhb__hero-inner">
                <div class="rw-fhb__story">
                    <span class="rw-fhb__label">First home buyer</span>
                    <h1>First home buyer loans Australia — step-by-step guidance.</h1>
                    <p class="rw-fhb__lead">
                        Buying your first property can feel overwhelming. We help first home buyers understand
                        borrowing capacity, grants, documentation, and the path from pre-approval to settlement.
                    </p>

                    <div class="rw-fhb__actions">
                        <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="fhb-hero-book">Book a free call</a>
                        <a class="rw-button rw-button--outline rw-fhb__ghost" href="{{ route('tools.borrowing-power') }}" data-cta="fhb-hero-calculator">Borrowing power calculator</a>
                    </div>
                </div>

                <div class="rw-fhb__media">
                    <img
                        src="{{ asset('images/landing/home-loans-advisor.jpg') }}"
                        alt="First home buyer receiving clear mortgage broker guidance"
                        width="900"
                        height="640"
                        loading="eager"
                        decoding="async"
                    >
                </div>
            </div>
        </section>

        <section class="rw-fhb__journey" aria-labelledby="fhb-journey-title">
            <div class="rw-fhb__section-inner">
                <div class="rw-fhb__section-head">
                    <span class="rw-section-label">Your journey</span>
                    <h2 id="fhb-journey-title">Four clear stages to your first home</h2>
                    <p>Interactive steps with practical guidance at each stage — no jargon, no pressure.</p>
                </div>

                <div class="rw-fhb__journey-grid">
                    @foreach ($journey as $item)
                        <article class="rw-fhb__journey-card">
                            <div class="rw-fhb__journey-media">
                                <img
                                    src="{{ asset($item['image']) }}"
                                    alt="{{ $item['image_alt'] }}"
                                    width="640"
                                    height="420"
                                    loading="lazy"
                                    decoding="async"
                                >
                                <span class="rw-fhb__journey-step" aria-hidden="true">{{ $item['step'] }}</span>
                            </div>
                            <div class="rw-fhb__journey-copy">
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['copy'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-fhb__resources" aria-labelledby="fhb-resources-title">
            <div class="rw-fhb__section-inner">
                <div class="rw-fhb__section-head">
                    <span class="rw-section-label">Tools &amp; guides</span>
                    <h2 id="fhb-resources-title">Explore helpful resources</h2>
                    <p>Use these tools and guides to plan your deposit, costs, and next conversation with a broker.</p>
                </div>

                <div class="rw-fhb__resource-grid">
                    @foreach ($resources as $resource)
                        <a class="rw-fhb__resource-card" href="{{ $resource['href'] }}" data-cta="fhb-resource">
                            <div class="rw-fhb__resource-media">
                                <img
                                    src="{{ asset($resource['image']) }}"
                                    alt="{{ $resource['image_alt'] }}"
                                    width="640"
                                    height="360"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </div>
                            <div class="rw-fhb__resource-copy">
                                <h3>{{ $resource['title'] }}</h3>
                                <p>{{ $resource['copy'] }}</p>
                                <span>{{ $resource['cta'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-fhb__trust">
            <div class="rw-fhb__section-inner">
                @include('partials.google-reviews')
                @include('partials.lender-logos')
            </div>
        </section>

        <section class="rw-fhb__faq" aria-labelledby="fhb-faq-title">
            <div class="rw-fhb__section-inner rw-fhb__faq-inner">
                <div class="rw-fhb__section-head">
                    <span class="rw-section-label">FAQ</span>
                    <h2 id="fhb-faq-title">Common first home buyer questions</h2>
                    <p>Tap a question to expand — clear answers for deposits, documents, and pre-approval.</p>
                </div>

                <div class="rw-fhb__faq-list" data-fhb-faq>
                    @foreach ($faqs as $index => $faq)
                        <details class="rw-fhb__faq-item" @if ($index === 0) open @endif>
                            <summary>
                                <span>{{ $faq['question'] }}</span>
                                <em aria-hidden="true"></em>
                            </summary>
                            <div class="rw-fhb__faq-answer">
                                <p>{{ $faq['answer'] }}</p>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-fhb__cta">
            <div class="rw-fhb__cta-inner">
                <h2>Book a free first home buyer call</h2>
                <p>Speak with a broker about your deposit, borrowing capacity, and the steps to buy your first home.</p>
                <div class="rw-page-actions">
                    <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="fhb-cta-book">Book a call</a>
                    <a class="rw-button rw-button--outline rw-fhb__ghost" href="{{ contact_url('first_home_buyer') }}" data-cta="fhb-cta-enquiry">Send an enquiry</a>
                    @include('partials.phone-link', [
                        'variant' => 'button',
                        'label' => 'Call '.config('riskwisdom.phone'),
                        'cta' => 'fhb-cta-phone',
                    ])
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        (() => {
            const root = document.querySelector('[data-fhb-faq]');
            if (!root) return;

            root.querySelectorAll('details').forEach((panel) => {
                panel.addEventListener('toggle', () => {
                    if (!panel.open) return;
                    root.querySelectorAll('details').forEach((other) => {
                        if (other !== panel) other.open = false;
                    });
                });
            });
        })();
    </script>
@endpush

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush
