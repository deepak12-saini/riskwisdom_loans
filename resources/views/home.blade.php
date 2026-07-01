@extends('layouts.site')

@section('title', 'Risk Wisdom Loans | Riskwisdom Loans – Home, Refinance & Commercial Finance')
@section('meta_description', 'Risk Wisdom Loans (Riskwisdom Loans) helps Australian borrowers with home loans, refinancing, investment lending, commercial finance, and asset finance. Risk Wisdom Loans Pty Ltd.')
@section('canonical', url('/'))

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FinancialService',
            'name' => 'Risk Wisdom Loans',
            'alternateName' => 'Riskwisdom Loans',
            'legalName' => config('riskwisdom.legal_name'),
            'url' => url('/'),
            'logo' => asset('images/risk-wisdom-loans-logo.png'),
            'image' => asset('images/risk-wisdom-loans-logo.png'),
            'telephone' => config('riskwisdom.phone'),
            'email' => config('riskwisdom.email'),
            'areaServed' => 'AU',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    @php
        $heroVideo = asset('videos/hero-background.mp4');

        $audiences = [
            ['tag' => 'First Home Buyers', 'title' => 'Guidance that makes a first purchase feel more manageable.', 'copy' => 'Understand borrowing options, documentation requirements, and the path from planning through to settlement.', 'cta' => 'First home buyer guide', 'intent' => 'first_home_buyer'],
            ['tag' => 'Families', 'title' => 'Finance strategies that adapt as your household changes.', 'copy' => 'We help families review repayments, explore refinancing, and structure finance for the next chapter.', 'cta' => 'Check refinance options', 'intent' => 'families'],
            ['tag' => 'Investors', 'title' => 'Lending support for buyers building long-term property plans.', 'copy' => 'Compare investment pathways with a focus on cash flow, flexibility, and future opportunities.', 'cta' => 'Explore investment lending', 'intent' => 'investors'],
            ['tag' => 'Professionals', 'title' => 'Straightforward support for busy borrowers with complex income.', 'copy' => 'Efficient communication, practical guidance, and finance options suited to professional workloads.', 'cta' => 'Get tailored advice', 'intent' => 'professionals'],
            ['tag' => 'Business Owners', 'title' => 'Practical lending help for growth, property, and equipment decisions.', 'copy' => 'Navigate finance with a clearer view of what suits business cash flow and future expansion plans.', 'cta' => 'Discuss business finance', 'intent' => 'business_owners'],
            ['tag' => 'Over 50s', 'title' => 'Loan conversations that respect lifestyle, flexibility, and future priorities.', 'copy' => 'Explore options with calm guidance tailored to where you are now and where you want to be next.', 'cta' => 'Review your options', 'intent' => 'over_50s'],
        ];

        $solutions = [
            ['title' => 'Home Loans', 'copy' => 'Owner-occupier finance with clear guidance around structure, repayments, and lender fit.', 'href' => route('pages.home-loans'), 'intent' => 'home_loans'],
            ['title' => 'Refinance Loans', 'copy' => 'Review your current finance and explore ways to simplify, improve features, or reduce pressure.', 'href' => route('pages.refinance'), 'intent' => 'refinance'],
            ['title' => 'Commercial Loans', 'copy' => 'Support for commercial property purchases, working capital plans, and broader business borrowing.', 'href' => route('pages.commercial'), 'intent' => 'commercial'],
            ['title' => 'Property Investment', 'copy' => 'Finance pathways designed for investors who want strategy as well as loan comparison.', 'href' => route('pages.investment'), 'intent' => 'investment'],
            ['title' => 'Asset Finance', 'copy' => 'Funding for vehicles, plant, or equipment with a practical focus on business usability.', 'href' => contact_url('commercial'), 'intent' => 'commercial'],
            ['title' => 'Construction Loans', 'copy' => 'Help navigating progress payments, staging, and lender expectations for build projects.', 'href' => contact_url('home_loans'), 'intent' => 'home_loans'],
        ];

        $resourceCards = [
            ['title' => 'Borrowing Power', 'copy' => 'Estimate borrowing capacity, review repayments, and plan your next property or finance move with more confidence.', 'cta' => 'Use calculator', 'href' => route('tools.borrowing-power')],
            ['title' => 'First Home Buyer\'s Guide', 'copy' => 'Download a practical guide covering deposits, pre-approval, grants, and what to do before you make an offer.', 'cta' => 'Download guide', 'href' => route('guides.download.show', 'first-home-buyers-guide')],
            ['title' => 'Construction Finance Guide', 'copy' => 'Get a simple overview of progress payments, buffers, and the finance structure behind knockdown-rebuild projects.', 'cta' => 'Download guide', 'href' => route('guides.download.show', 'construction-knockdown-rebuild-finance-guide')],
            ['title' => 'News & Insights', 'copy' => 'Practical updates on interest rates, lending trends, and borrower tips that help you make better finance decisions.', 'cta' => 'Read insights', 'href' => route('guides.index')],
            ['title' => 'Repayment Calculator', 'copy' => 'Model monthly repayments across loan amounts, rates, and terms before you speak with a broker.', 'cta' => 'Calculate repayments', 'href' => route('tools.repayment-calculator')],
            ['title' => 'Stamp Duty Calculator', 'copy' => 'Estimate transfer duty and government charges by state before you commit to a purchase price.', 'cta' => 'Estimate stamp duty', 'href' => route('tools.stamp-duty')],
        ];

        $serviceHighlights = [
            ['title' => 'Advice shaped around your situation', 'copy' => 'We start by understanding your goals, income, timing, and priorities before recommending suitable finance pathways.'],
            ['title' => 'Clear communication at every step', 'copy' => 'You should know what is happening, what comes next, and what each stage means for your application and timeline.'],
            ['title' => 'Support from enquiry to settlement', 'copy' => 'From documents and lender conversations to final approval, we help keep the process organised, calm, and moving forward.'],
        ];

        $experienceStats = [
            ['value' => 'Clear', 'label' => 'Straightforward guidance without unnecessary jargon'],
            ['value' => 'Tailored', 'label' => 'Finance pathways aligned to your goals and timing'],
            ['value' => 'Supported', 'label' => 'Ongoing help from enquiry through to settlement'],
        ];

        $experienceJourney = [
            'Understand your goals, borrowing position, and priorities',
            'Compare suitable options and explain each pathway clearly',
            'Guide the application, documents, and lender communication',
        ];

        $consultationBenefits = ['Free consultation', 'Plan to move forward'];
    @endphp

    <main class="rw-home">
        <section class="rw-hero">
            <video class="rw-hero__video" autoplay muted loop playsinline poster="">
                <source src="{{ $heroVideo }}" type="video/mp4">
            </video>
            <div class="rw-hero__overlay"></div>

            <div class="container rw-hero__content">
                <h1>Smarter loan guidance<br>for every stage of life.</h1>
                <p>
                    Riskwisdom Loans helps Australian property owners move forward with clarity across home loans,
                    refinancing, investment lending, commercial finance, and asset finance.
                </p>

                <div class="rw-hero__actions">
                    @include('partials.book-chat-button', ['variant' => 'solid', 'cta' => 'hero-book-chat'])
                    @include('partials.phone-link', [
                        'variant' => 'ghost',
                        'label' => 'Call ' . config('riskwisdom.phone'),
                        'cta' => 'hero-phone',
                    ])
                    <a class="rw-button rw-button--ghost" href="{{ rate_review_url() }}" data-cta="hero-primary">Get free loan review</a>
                    <a class="rw-button rw-button--ghost" href="#solutions" data-cta="hero-secondary">See solutions</a>
                </div>
            </div>
        </section>

        <section class="rw-promo">
            <div class="container rw-promo__inner">
                <div>
                    <span class="rw-section-label">Ready to move forward?</span>
                    <p>Book a free consultation and get clear next steps for your home loan, refinance, or finance enquiry.</p>
                </div>
                <a class="rw-button rw-button--solid" href="{{ route('book') }}" data-cta="promo-primary">Book a call</a>
            </div>
        </section>

        <section class="rw-section rw-section--intro" id="about">
            <div class="container rw-solution">
                <div class="rw-solution__copy">
                    <span class="rw-section-label">Helping you find the right solution</span>
                    <h2>Finance guidance designed to help you move forward with more confidence.</h2>
                    <p>
                        At Riskwisdom Loans, part of {{ config('riskwisdom.legal_name') }}, the focus is on making finance feel
                        clearer, more practical, and less overwhelming. Whether you are buying, refinancing,
                        investing, or funding business growth, we help you understand the options in front of you.
                    </p>
                    <p>
                        We take the time to understand your circumstances, compare suitable lending pathways, and guide
                        you through the process with straightforward advice and responsive support.
                    </p>
                    <div class="rw-solution__actions">
                        <a class="rw-button rw-button--solid" href="#solutions" data-cta="about-solutions">Find out more</a>
                        <a class="rw-button rw-button--outline" href="{{ route('pages.about') }}" data-cta="about-page">Read our story</a>
                        <a class="rw-button rw-button--text" href="{{ rate_review_url() }}" data-cta="about-contact">Get free loan review</a>
                    </div>
                </div>

                <div class="rw-solution__panel">
                    <div class="rw-solution__panel-top">
                        <span>What you can expect</span>
                        <strong>Practical support, clear options, and a smoother finance journey</strong>
                    </div>
                    <ul class="rw-feature-list">
                        <li>Finance options explained in a way that is easy to understand</li>
                        <li>Guidance tailored to your goals, timing, and financial position</li>
                        <li>Responsive support with lender communication and document guidance</li>
                        <li>A clear path from enquiry through to approval and settlement</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="rw-section rw-section--process" id="how-it-works">
            <div class="container">
                @include('partials.how-it-works')
            </div>
        </section>

        <section class="rw-section rw-section--light">
            <div class="container">
                @include('partials.google-reviews')
            </div>
        </section>

        <section class="rw-section rw-section--light" id="who-we-help">
            <div class="container">
                <div class="rw-section-heading">
                    <span class="rw-section-label">Who We Help</span>
                    <h2>Finance made simpler for Australian property owners and buyers.</h2>
                    <p>
                        Every borrower has different priorities. We tailor our approach to your stage of life, your
                        income structure, and the outcome you want to achieve.
                    </p>
                </div>

                <div class="rw-grid rw-grid--audiences">
                    @foreach ($audiences as $audience)
                        <article class="rw-card rw-card--audience">
                            <span class="rw-card__tag">{{ $audience['tag'] }}</span>
                            <h3>{{ $audience['title'] }}</h3>
                            <p>{{ $audience['copy'] }}</p>
                            <a class="rw-link-arrow" href="{{ contact_url($audience['intent']) }}" data-cta="audience-{{ $audience['intent'] }}">{{ $audience['cta'] }}</a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-section" id="solutions">
            <div class="container">
                <div class="rw-section-heading">
                    <span class="rw-section-label">Solutions</span>
                    <h2>Finance solutions built to support your next property or business decision.</h2>
                    <p>
                        From home loans and refinancing to commercial and asset finance, we help you compare the
                        options that best align with your plans and financial goals.
                    </p>
                </div>

                <div class="rw-grid rw-grid--solutions">
                    @foreach ($solutions as $solution)
                        <article class="rw-card rw-card--solution">
                            <h3>{{ $solution['title'] }}</h3>
                            <p>{{ $solution['copy'] }}</p>
                            <a class="rw-link-arrow" href="{{ $solution['href'] }}" data-cta="solution-{{ $solution['intent'] }}">Learn more</a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-section rw-section--band">
            <div class="container">
                @include('partials.lender-logos')
            </div>
        </section>

        <section class="rw-section" id="resources">
            <div class="container">
                <div class="rw-section-heading">
                    <span class="rw-section-label">Resources</span>
                    <h2>Take the first step with useful tools, guides, and finance insights.</h2>
                    <p>
                        Good finance decisions start with better information. Use these resources to plan ahead,
                        understand your options, and move forward with greater confidence.
                    </p>
                </div>

                <div class="rw-grid rw-grid--resources">
                    @foreach ($resourceCards as $resource)
                        <article class="rw-card rw-card--resource">
                            <h3>{{ $resource['title'] }}</h3>
                            <p>{{ $resource['copy'] }}</p>
                            <a class="rw-link-arrow" href="{{ $resource['href'] }}" data-cta="resource-{{ Str::slug($resource['title']) }}">{{ $resource['cta'] }}</a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-section rw-section--light" id="community">
            <div class="container">
                <div class="rw-experience">
                    <div class="rw-experience__intro">
                        <span class="rw-section-label">Client Experience</span>
                        <h2>Experience a finance journey that feels clear, considered, and well supported.</h2>
                        <p>
                            The best client experience is not about noise. It is about knowing your options,
                            understanding the process, and feeling confident that each next step is handled with care.
                        </p>

                        <div class="rw-experience__stats">
                            @foreach ($experienceStats as $stat)
                                <article class="rw-experience__stat">
                                    <strong>{{ $stat['value'] }}</strong>
                                    <span>{{ $stat['label'] }}</span>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="rw-experience__spotlight">
                        <span class="rw-experience__eyebrow">What the process feels like</span>
                        <h3>Calm guidance, practical strategy, and communication that keeps moving.</h3>
                        <p>
                            We focus on making the process easier to navigate by breaking each step into something
                            clear, manageable, and relevant to your goals.
                        </p>
                        <ul class="rw-experience__list">
                            @foreach ($experienceJourney as $step)
                                <li>{{ $step }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="rw-grid rw-grid--highlights">
                    @foreach ($serviceHighlights as $highlight)
                        <article class="rw-card rw-card--highlight">
                            <span class="rw-card--highlight__index">{{ str_pad((string) ($loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $highlight['title'] }}</h3>
                            <p>{{ $highlight['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rw-section rw-section--cta" id="why-riskwisdom">
            <div class="container rw-cta">
                <div class="rw-cta__copy">
                    <span class="rw-section-label rw-section-label--dark">Why Riskwisdom Loans</span>
                    <h2>Work with a finance partner focused on making the process easier to navigate.</h2>
                    <p>
                        The right loan is not just about rates. It is about structure, flexibility, timing, and
                        understanding how the decision supports your next step with confidence.
                    </p>
                </div>

                <div class="rw-cta__panel">
                    <strong>Why borrowers choose us</strong>
                    <ul>
                        <li>Clear explanations without unnecessary jargon</li>
                        <li>Finance guidance tailored to your individual goals</li>
                        <li>Ongoing support through documents, lenders, and approvals</li>
                        <li>A relationship-focused approach from first enquiry onward</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="rw-section rw-section--contact" id="contact">
            <div class="container rw-contact">
                <div class="rw-contact__copy">
                    <span class="rw-section-label rw-section-label--dark">Free consultation</span>
                    <h2>Tap into practical lending expertise built around your next move.</h2>
                    <h3>Clear guidance for buyers, refinancers, investors, and business owners.</h3>
                    <p>
                        Riskwisdom Loans is focused on making the finance process easier to understand and easier to
                        act on. Share your details and we will follow up with the guidance that best suits your
                        borrowing goals.
                    </p>
                    <div class="rw-contact__details">
                        @include('partials.phone-link', ['variant' => 'text', 'cta' => 'contact-phone'])
                        <a href="mailto:{{ config('riskwisdom.email') }}">{{ config('riskwisdom.email') }}</a>
                        <a href="https://www.riskwisdomloans.com.au" target="_blank" rel="noreferrer">www.riskwisdomloans.com.au</a>
                    </div>
                </div>

                @include('partials.contact-form', ['consultationBenefits' => $consultationBenefits])
            </div>
        </section>
    </main>
@endsection

@section('header_class')
    rw-header--overlay
@endsection
