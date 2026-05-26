<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Riskwisdom Loans | Smarter Loan Guidance</title>
        <meta
            name="description"
            content="Riskwisdom Loans helps borrowers navigate home loans, refinancing, investment lending, commercial finance, and asset finance with clarity."
        >
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="site-body rw-theme">
        @php
            $heroVideo = asset('videos/hero-background.mp4');

            $navigation = [
                [
                    'label' => 'About',
                    'links' => [
                        ['title' => 'About Riskwisdom Loans', 'href' => '#about'],
                        ['title' => 'Our Approach', 'href' => '#why-riskwisdom'],
                        ['title' => 'Contact Us', 'href' => '#contact'],
                    ],
                ],
                [
                    'label' => 'Who We Help',
                    'links' => [
                        ['title' => 'First Home Buyers', 'href' => '#who-we-help'],
                        ['title' => 'Families', 'href' => '#who-we-help'],
                        ['title' => 'Investors', 'href' => '#who-we-help'],
                        ['title' => 'Business Owners', 'href' => '#who-we-help'],
                    ],
                ],
                [
                    'label' => 'Solutions',
                    'links' => [
                        ['title' => 'Home Loans', 'href' => '#solutions'],
                        ['title' => 'Refinance Loans', 'href' => '#solutions'],
                        ['title' => 'Commercial Loans', 'href' => '#solutions'],
                        ['title' => 'Asset Finance', 'href' => '#solutions'],
                    ],
                ],
                [
                    'label' => 'Resources',
                    'links' => [
                        ['title' => 'Borrowing Power', 'href' => '#resources'],
                        ['title' => 'Guides & Insights', 'href' => '#resources'],
                        ['title' => 'Next Step Checklist', 'href' => '#resources'],
                    ],
                ],
                [
                    'label' => 'Community',
                    'links' => [
                        ['title' => 'Referral Partners', 'href' => '#community'],
                        ['title' => 'Client Experience', 'href' => '#community'],
                        ['title' => 'Book a Consultation', 'href' => '#contact'],
                    ],
                ],
            ];

            $audiences = [
                [
                    'tag' => 'First Home Buyers',
                    'title' => 'Guidance that makes a first purchase feel more manageable.',
                    'copy' => 'Understand borrowing options, documentation requirements, and the path from planning through to settlement.',
                ],
                [
                    'tag' => 'Families',
                    'title' => 'Finance strategies that adapt as your household changes.',
                    'copy' => 'We help families review repayments, explore refinancing, and structure finance for the next chapter.',
                ],
                [
                    'tag' => 'Investors',
                    'title' => 'Lending support for buyers building long-term property plans.',
                    'copy' => 'Compare investment pathways with a focus on cash flow, flexibility, and future opportunities.',
                ],
                [
                    'tag' => 'Professionals',
                    'title' => 'Straightforward support for busy borrowers with complex income.',
                    'copy' => 'Efficient communication, practical guidance, and finance options suited to professional workloads.',
                ],
                [
                    'tag' => 'Business Owners',
                    'title' => 'Practical lending help for growth, property, and equipment decisions.',
                    'copy' => 'Navigate finance with a clearer view of what suits business cash flow and future expansion plans.',
                ],
                [
                    'tag' => 'Over 50s',
                    'title' => 'Loan conversations that respect lifestyle, flexibility, and future priorities.',
                    'copy' => 'Explore options with calm guidance tailored to where you are now and where you want to be next.',
                ],
            ];

            $solutions = [
                [
                    'title' => 'Home Loans',
                    'copy' => 'Owner-occupier finance with clear guidance around structure, repayments, and lender fit.',
                ],
                [
                    'title' => 'Refinance Loans',
                    'copy' => 'Review your current finance and explore ways to simplify, improve features, or reduce pressure.',
                ],
                [
                    'title' => 'Commercial Loans',
                    'copy' => 'Support for commercial property purchases, working capital plans, and broader business borrowing.',
                ],
                [
                    'title' => 'Property Investment',
                    'copy' => 'Finance pathways designed for investors who want strategy as well as loan comparison.',
                ],
                [
                    'title' => 'Asset Finance',
                    'copy' => 'Funding for vehicles, plant, or equipment with a practical focus on business usability.',
                ],
                [
                    'title' => 'Construction Loans',
                    'copy' => 'Help navigating progress payments, staging, and lender expectations for build projects.',
                ],
            ];

            $resourceCards = [
                [
                    'title' => 'Borrowing Power',
                    'copy' => 'Introduce calculators, repayment tools, and planning resources that help borrowers understand the next step.',
                    'cta' => 'View tools',
                ],
                [
                    'title' => 'News & Insights',
                    'copy' => 'Share original market commentary, interest rate updates, and practical finance guidance as content grows.',
                    'cta' => 'Read insights',
                ],
                [
                    'title' => 'Property Profile Reports',
                    'copy' => 'Offer downloadable or enquiry-based reports that help clients connect lending decisions with property goals.',
                    'cta' => 'Request a report',
                ],
            ];

            $serviceHighlights = [
                [
                    'title' => 'Tailored lending guidance',
                    'copy' => 'The process starts with understanding your situation before looking at suitable lender pathways.',
                ],
                [
                    'title' => 'Clear communication',
                    'copy' => 'Borrowers should know what is happening, what is needed next, and how each step supports the outcome.',
                ],
                [
                    'title' => 'Support from enquiry to settlement',
                    'copy' => 'Documentation, lender communication, and next actions are guided with a calm, practical approach.',
                ],
            ];

            $lenderLabels = [
                'Major Bank Options',
                'Specialist Lending',
                'Refinance Pathways',
                'Investment Strategy',
                'Commercial Support',
                'Asset Finance',
            ];

            $consultationBenefits = [
                'Free consultation',
                'Plan to move forward',
            ];
        @endphp

        <header class="rw-header">
            <div class="container rw-header__wrap">
                <div class="rw-header__bar">
                    <a class="rw-brand" href="{{ route('home') }}">
                        <span class="rw-brand__mark">RW</span>
                        <span class="rw-brand__copy">
                            <strong>Riskwisdom</strong>
                            <small>Loans</small>
                        </span>
                    </a>

                    <nav class="rw-nav" aria-label="Primary">
                        @foreach ($navigation as $item)
                            <div class="rw-nav__item">
                                <button class="rw-nav__trigger" type="button">{{ $item['label'] }}</button>
                                <div class="rw-nav__dropdown">
                                    @foreach ($item['links'] as $link)
                                        <a href="{{ $link['href'] }}">{{ $link['title'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </nav>

                    <a class="rw-button rw-button--outline" href="#contact">Book a call</a>

                    <button
                        class="rw-mobile-toggle"
                        type="button"
                        aria-label="Open menu"
                        aria-expanded="false"
                        aria-controls="rw-mobile-menu"
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>

                <div class="rw-mobile-menu" id="rw-mobile-menu">
                    <nav class="rw-mobile-menu__nav" aria-label="Mobile">
                        @foreach ($navigation as $item)
                            <details class="rw-mobile-menu__group">
                                <summary>{{ $item['label'] }}</summary>
                                <div class="rw-mobile-menu__links">
                                    @foreach ($item['links'] as $link)
                                        <a href="{{ $link['href'] }}">{{ $link['title'] }}</a>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </nav>

                    <a class="rw-button rw-button--solid rw-mobile-menu__cta" href="#contact">Book a call</a>
                </div>
            </div>
        </header>

        <main class="rw-home">
            <section class="rw-hero">
                <video class="rw-hero__video" autoplay muted loop playsinline poster="">
                    <source src="{{ $heroVideo }}" type="video/mp4">
                </video>
                <div class="rw-hero__overlay"></div>

                <div class="container rw-hero__content">
                    <h1>Smarter loan guidance<br>for every stage of life.</h1>
                    <p>
                        Riskwisdom Loans helps borrowers move forward with clarity across home loans, refinancing,
                        investment lending, commercial finance, and asset finance.
                    </p>

                    <div class="rw-hero__actions">
                        <a class="rw-button rw-button--solid" href="#contact">Book a call</a>
                        <a class="rw-button rw-button--ghost" href="#solutions">Explore solutions</a>
                    </div>
                </div>
            </section>

            <section class="rw-promo">
                <div class="container rw-promo__inner">
                    <div>
                        <span class="rw-section-label">Ready to move forward?</span>
                        <p>Use this section for campaign banners, limited-time offers, or a simple consultation message.</p>
                    </div>
                    <a class="rw-link-arrow" href="#contact">Request a free consultation</a>
                </div>
            </section>

            <section class="rw-section rw-section--intro" id="about">
                <div class="container rw-solution">
                    <div class="rw-solution__copy">
                        <span class="rw-section-label">Helping you find the right solution</span>
                        <h2>Original content built around your own finance brand, not copied wording.</h2>
                        <p>
                            This homepage follows the broad structure and premium feel of the reference site while keeping
                            the copy specific to Riskwisdom Loans. Every heading, card, and menu item can be updated from
                            this Blade file.
                        </p>
                        <p>
                            It is designed to feel modern, trustworthy, and easy to expand into inner pages as the site
                            grows.
                        </p>
                        <div class="rw-solution__actions">
                            <a class="rw-button rw-button--solid" href="#solutions">Find out more</a>
                            <a class="rw-button rw-button--text" href="#contact">Book a call</a>
                        </div>
                    </div>

                    <div class="rw-solution__panel">
                        <div class="rw-solution__panel-top">
                            <span>Brand direction</span>
                            <strong>Clean, confident, and easy to adapt</strong>
                        </div>
                        <ul class="rw-feature-list">
                            <li>Editable menu groups with dropdown links</li>
                            <li>Hero banner with background video and layered overlay</li>
                            <li>Section cards built from arrays for easier content changes</li>
                            <li>Production-ready contact section and footer structure</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="rw-section rw-section--light" id="who-we-help">
                <div class="container">
                    <div class="rw-section-heading">
                        <span class="rw-section-label">Who We Help</span>
                        <h2>Finance made simpler for the people and plans you support.</h2>
                        <p>
                            These audience cards are written as original placeholder content and can be adjusted as your
                            service positioning becomes more specific.
                        </p>
                    </div>

                    <div class="rw-grid rw-grid--audiences">
                        @foreach ($audiences as $audience)
                            <article class="rw-card rw-card--audience">
                                <span class="rw-card__tag">{{ $audience['tag'] }}</span>
                                <h3>{{ $audience['title'] }}</h3>
                                <p>{{ $audience['copy'] }}</p>
                                <a class="rw-link-arrow" href="#contact">Learn more</a>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="rw-section" id="solutions">
                <div class="container">
                    <div class="rw-section-heading">
                        <span class="rw-section-label">Solutions</span>
                        <h2>Finance options arranged in a format that is easy to expand later.</h2>
                        <p>
                            Use these cards as the starting point for individual service pages such as home loans,
                            refinance, commercial finance, and construction lending.
                        </p>
                    </div>

                    <div class="rw-grid rw-grid--solutions">
                        @foreach ($solutions as $solution)
                            <article class="rw-card rw-card--solution">
                                <h3>{{ $solution['title'] }}</h3>
                                <p>{{ $solution['copy'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="rw-section rw-section--band">
                <div class="container">
                    <div class="rw-lenders">
                        <div class="rw-lenders__heading">
                            <span class="rw-section-label">A flexible lending panel message</span>
                            <h2>Present breadth and confidence without inventing unsupported claims.</h2>
                        </div>
                        <div class="rw-lenders__list">
                            @foreach ($lenderLabels as $label)
                                <span>{{ $label }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section class="rw-section" id="resources">
                <div class="container">
                    <div class="rw-section-heading">
                        <span class="rw-section-label">Resources</span>
                        <h2>Take the first step with useful tools, guides, and finance insights.</h2>
                        <p>
                            The cards below are styled to match the reference direction while staying fully original in
                            wording and layout implementation.
                        </p>
                    </div>

                    <div class="rw-grid rw-grid--resources">
                        @foreach ($resourceCards as $resource)
                            <article class="rw-card rw-card--resource">
                                <h3>{{ $resource['title'] }}</h3>
                                <p>{{ $resource['copy'] }}</p>
                                <a class="rw-link-arrow" href="#contact">{{ $resource['cta'] }}</a>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="rw-section rw-section--light" id="community">
                <div class="container">
                    <div class="rw-section-heading rw-section-heading--center">
                        <span class="rw-section-label">Client Experience</span>
                        <h2>Use this area for approved testimonials, or keep it focused on service promises.</h2>
                        <p>
                            To avoid fake reviews, this version uses clear experience highlights instead of invented
                            customer quotes.
                        </p>
                    </div>

                    <div class="rw-grid rw-grid--highlights">
                        @foreach ($serviceHighlights as $highlight)
                            <article class="rw-card rw-card--highlight">
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
                        <h2>Give borrowers a reason to trust the process from the first click.</h2>
                        <p>
                            This section can be used for awards, lender-panel information, service methodology, or team
                            positioning once your final business profile is confirmed.
                        </p>
                    </div>

                    <div class="rw-cta__panel">
                        <strong>Current version includes</strong>
                        <ul>
                            <li>Editable homepage arrays for easier content updates</li>
                            <li>Video hero layout similar in feel to the reference screenshot</li>
                            <li>Dropdown navigation ready for future inner pages</li>
                            <li>Original finance-focused copy for your own brand</li>
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
                            <a href="tel:+61421670636">+61 421 670 636</a>
                            <a href="mailto:info@riskwisdomloans.com.au">info@riskwisdomloans.com.au</a>
                            <a href="https://www.riskwisdomloans.com.au" target="_blank" rel="noreferrer">
                                www.riskwisdomloans.com.au
                            </a>
                        </div>
                    </div>

                    <div class="rw-contact__card">
                        @if (session('status'))
                            <div class="rw-form-alert rw-form-alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="rw-form-alert rw-form-alert-error">
                                Please complete all required fields and try again.
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="post" class="rw-form">
                            @csrf

                            <div class="rw-form-grid">
                                <label>
                                    <span>First name</span>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name">
                                    @error('first_name')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </label>

                                <label>
                                    <span>Last name</span>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name">
                                    @error('last_name')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </label>

                                <label>
                                    <span>Phone</span>
                                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number">
                                    @error('phone')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </label>

                                <label>
                                    <span>Email</span>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address">
                                    @error('email')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </label>

                                <label class="rw-form-full">
                                    <span>Enquiry</span>
                                    <textarea name="enquiry" rows="5" placeholder="Tell us about your finance goals">{{ old('enquiry') }}</textarea>
                                    @error('enquiry')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </label>
                            </div>

                            <button class="rw-button rw-button--solid rw-button--wide" type="submit">Book a discovery call</button>
                        </form>

                        <div class="rw-contact__benefits">
                            @foreach ($consultationBenefits as $benefit)
                                <span>{{ $benefit }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="rw-footer">
            <div class="container rw-footer__grid">
                <div>
                    <a class="rw-brand rw-brand--footer" href="{{ route('home') }}">
                        <span class="rw-brand__mark">RW</span>
                        <span class="rw-brand__copy">
                            <strong>Riskwisdom</strong>
                            <small>Loans</small>
                        </span>
                    </a>
                    <p>
                        Riskwisdom Loans provides clear, practical lending guidance for home buyers, refinancers,
                        investors, professionals, and business owners.
                    </p>
                </div>

                <div>
                    <h3>Services</h3>
                    <ul>
                        <li><a href="#solutions">Home Loans</a></li>
                        <li><a href="#solutions">Refinance Loans</a></li>
                        <li><a href="#solutions">Commercial Loans</a></li>
                        <li><a href="#solutions">Asset Finance</a></li>
                    </ul>
                </div>

                <div>
                    <h3>Information</h3>
                    <ul>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#resources">Resources</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                        <li><a href="#community">Client Experience</a></li>
                    </ul>
                </div>

                <div class="rw-footer__contact">
                    <h3>Contact Us</h3>
                    <ul>
                        <li>
                            <a class="rw-footer__contact-link" href="tel:+61421670636">
                                <span class="rw-footer__contact-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.3.56 3.52.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.22.19 2.4.56 3.52a1 1 0 0 1-.24 1.02l-2.2 2.25Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <span>+61 421 670 636</span>
                            </a>
                        </li>
                        <li>
                            <a class="rw-footer__contact-link" href="mailto:info@riskwisdomloans.com.au">
                                <span class="rw-footer__contact-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 7.5 11.2 12.9c.48.36 1.12.36 1.6 0L20 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                    </svg>
                                </span>
                                <span>info@riskwisdomloans.com.au</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="container rw-footer__bottom">
                <p>&copy; {{ now()->year }} Riskwisdom Loans. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
