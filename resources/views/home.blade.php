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
    <body class="site-body">
        @php
            $audiences = [
                [
                    'label' => '01',
                    'title' => 'First Home Buyers',
                    'copy' => 'Step into the market with clear guidance, practical next steps, and lending options shaped around your first purchase.',
                ],
                [
                    'label' => '02',
                    'title' => 'Families',
                    'copy' => 'Whether you are upsizing, consolidating, or reviewing repayments, we help families structure finance with confidence.',
                ],
                [
                    'label' => '03',
                    'title' => 'Investors',
                    'copy' => 'Explore property lending strategies that support portfolio growth, cash flow planning, and future opportunities.',
                ],
                [
                    'label' => '04',
                    'title' => 'Professionals',
                    'copy' => 'Busy professionals get efficient support, responsive communication, and tailored lending pathways for complex income profiles.',
                ],
                [
                    'label' => '05',
                    'title' => 'Business Owners',
                    'copy' => 'We help business owners assess finance solutions for property, equipment, and broader borrowing needs without unnecessary friction.',
                ],
                [
                    'label' => '06',
                    'title' => 'Over 50s',
                    'copy' => 'Receive guidance that respects your stage of life, borrowing priorities, and plans for flexibility in the years ahead.',
                ],
            ];

            $solutions = [
                [
                    'title' => 'Home Loans',
                    'copy' => 'Owner-occupier lending options designed to balance flexibility, value, and long-term suitability.',
                ],
                [
                    'title' => 'Refinance Loans',
                    'copy' => 'Review your existing loan and explore opportunities to simplify repayments, improve features, or consolidate debt.',
                ],
                [
                    'title' => 'Investment Loans',
                    'copy' => 'Finance strategies for buyers who want to purchase, refinance, or strengthen their investment position.',
                ],
                [
                    'title' => 'Commercial Loans',
                    'copy' => 'Structured lending for commercial property, business expansion, and strategic borrowing requirements.',
                ],
                [
                    'title' => 'Asset Finance',
                    'copy' => 'Funding solutions for vehicles, equipment, and business assets that support day-to-day operations and growth.',
                ],
                [
                    'title' => 'Construction Loans',
                    'copy' => 'Guidance through staged funding, progress payments, and lending considerations for new builds or major renovations.',
                ],
            ];

            $benefits = [
                'Tailored lending guidance',
                'Clear explanations without jargon',
                'Support from enquiry to settlement',
                'Loan options matched to your scenario',
            ];

            $resources = [
                [
                    'title' => 'Borrowing Power',
                    'copy' => 'Build confidence around your next step with calculators and educational tools that help you understand borrowing capacity and repayments.',
                ],
                [
                    'title' => 'Loan Guides',
                    'copy' => 'Learn the fundamentals of buying, refinancing, investing, and structuring finance with clear, practical explanations.',
                ],
                [
                    'title' => 'Market Insights',
                    'copy' => 'Stay informed about interest rate changes, lending updates, and broader property finance topics that may affect your plans.',
                ],
            ];

            $promises = [
                [
                    'title' => 'Clarity First',
                    'copy' => 'We start by understanding your priorities so the advice and options stay aligned with your real goals.',
                ],
                [
                    'title' => 'Thoughtful Strategy',
                    'copy' => 'Every recommendation should make sense not just today, but for the stage you are moving toward next.',
                ],
                [
                    'title' => 'Steady Support',
                    'copy' => 'You should always know where things stand, what comes next, and what information is still needed.',
                ],
            ];

            $process = [
                'Discover your goals and current position',
                'Compare suitable lending pathways',
                'Guide documentation and lender communication',
                'Support your application through to settlement',
            ];
        @endphp

        <header class="site-header">
            <div class="container nav-shell">
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand-mark">RW</span>
                    <span class="brand-copy">
                        <strong>Riskwisdom</strong>
                        <small>Loans</small>
                    </span>
                </a>

                <nav class="main-nav" aria-label="Primary">
                    <a href="#who-we-help">Who We Help</a>
                    <a href="#solutions">Solutions</a>
                    <a href="#resources">Resources</a>
                    <a href="#contact">Contact</a>
                </nav>

                <a class="button button-primary button-nav" href="#contact">Book a Call</a>
            </div>
        </header>

        <main>
            <section class="hero-section">
                <div class="container hero-grid">
                    <div class="hero-copy">
                        <span class="eyebrow">Original finance homepage concept</span>
                        <h1>Smarter loan guidance for every stage of life.</h1>
                        <p class="hero-lead">
                            Riskwisdom Loans helps borrowers make confident finance decisions with clear explanations,
                            tailored lending options, and practical support from first enquiry to settlement.
                        </p>

                        <div class="hero-actions">
                            <a class="button button-primary" href="#contact">Book a Call</a>
                            <a class="button button-secondary" href="#solutions">Explore Solutions</a>
                        </div>

                        <ul class="hero-points">
                            @foreach ($benefits as $benefit)
                                <li>{{ $benefit }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="hero-visual" aria-hidden="true">
                        <div class="hero-card hero-card-primary">
                            <p class="card-label">Finance approach</p>
                            <h2>Calm guidance backed by practical lending strategy.</h2>
                            <p>
                                Built for borrowers who want clarity around home loans, refinancing, investment lending,
                                commercial finance, and asset finance.
                            </p>
                        </div>

                        <div class="hero-card hero-card-secondary">
                            <p class="card-label">How we work</p>
                            <ul class="process-list">
                                @foreach ($process as $index => $step)
                                    <li>
                                        <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                        <strong>{{ $step }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="hero-card hero-card-accent">
                            <p class="card-label">Contact</p>
                            <strong>info@riskwisdomloans.com.au</strong>
                            <span>www.riskwisdomloans.com.au</span>
                        </div>

                        <span class="hero-orb hero-orb-one"></span>
                        <span class="hero-orb hero-orb-two"></span>
                    </div>
                </div>
            </section>

            <section class="trust-strip">
                <div class="container trust-grid">
                    @foreach ($benefits as $benefit)
                        <article class="trust-card">
                            <span class="trust-card-mark"></span>
                            <p>{{ $benefit }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section" id="who-we-help">
                <div class="container">
                    <div class="section-heading">
                        <span class="eyebrow">Who We Help</span>
                        <h2>Finance solutions built around your goals.</h2>
                        <p>
                            Whether you are buying your first property, refinancing for flexibility, planning an
                            investment move, or funding business growth, the process should feel informed and manageable.
                        </p>
                    </div>

                    <div class="card-grid">
                        @foreach ($audiences as $audience)
                            <article class="info-card">
                                <span class="card-index">{{ $audience['label'] }}</span>
                                <h3>{{ $audience['title'] }}</h3>
                                <p>{{ $audience['copy'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="section section-alt" id="solutions">
                <div class="container">
                    <div class="section-heading">
                        <span class="eyebrow">Loan Solutions</span>
                        <h2>Find the right finance pathway for your next move.</h2>
                        <p>
                            We tailor the lending conversation to your situation so you can compare options that fit your
                            current needs and the future you are working toward.
                        </p>
                    </div>

                    <div class="card-grid">
                        @foreach ($solutions as $solution)
                            <article class="info-card info-card-alt">
                                <h3>{{ $solution['title'] }}</h3>
                                <p>{{ $solution['copy'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="container">
                    <div class="split-panel">
                        <div class="split-copy">
                            <span class="eyebrow">Why Choose Riskwisdom Loans</span>
                            <h2>Clear advice, thoughtful strategy, and support that keeps moving.</h2>
                            <p>
                                Borrowing should not feel confusing or rushed. We focus on understanding your position,
                                explaining the lending landscape clearly, and helping you move forward with confidence.
                            </p>
                            <p>
                                The goal is not just to find a loan, but to help you understand why a pathway may suit
                                your circumstances and what it means for the next stage of your plans.
                            </p>
                        </div>

                        <div class="feature-stack">
                            <article class="feature-card">
                                <strong>Personalised guidance</strong>
                                <p>Recommendations should reflect your real objectives, not a one-size-fits-all script.</p>
                            </article>
                            <article class="feature-card">
                                <strong>Responsive communication</strong>
                                <p>You stay updated on progress, requirements, and lender interactions throughout the process.</p>
                            </article>
                            <article class="feature-card">
                                <strong>Practical finance support</strong>
                                <p>We help break down the process into clear actions so each stage feels manageable.</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" id="resources">
                <div class="container">
                    <div class="section-heading">
                        <span class="eyebrow">Resources</span>
                        <h2>Tools and guidance to support better finance decisions.</h2>
                        <p>
                            Good decisions start with better information. Use this section to introduce calculators,
                            guides, and insights as the broader site grows.
                        </p>
                    </div>

                    <div class="card-grid card-grid-third">
                        @foreach ($resources as $resource)
                            <article class="info-card">
                                <h3>{{ $resource['title'] }}</h3>
                                <p>{{ $resource['copy'] }}</p>
                                <a class="text-link" href="#contact">Request More Information</a>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="section section-alt">
                <div class="container">
                    <div class="section-heading section-heading-centered">
                        <span class="eyebrow">Working With Us</span>
                        <h2>What clients can expect from the experience.</h2>
                        <p>
                            This section replaces fake testimonials with clear service promises until genuine, approved
                            client feedback is available.
                        </p>
                    </div>

                    <div class="promise-grid">
                        @foreach ($promises as $promise)
                            <article class="promise-card">
                                <h3>{{ $promise['title'] }}</h3>
                                <p>{{ $promise['copy'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="section" id="contact">
                <div class="container">
                    <div class="contact-shell">
                        <div class="contact-copy">
                            <span class="eyebrow eyebrow-dark">Free Consultation</span>
                            <h2>Start the conversation with a clear next step.</h2>
                            <p>
                                Share a few details about your goals and we will outline the lending pathways that may suit
                                your situation. This demo form is ready for backend wiring and email delivery.
                            </p>

                            <div class="contact-details">
                                <a href="mailto:info@riskwisdomloans.com.au">info@riskwisdomloans.com.au</a>
                                <a href="https://www.riskwisdomloans.com.au" target="_blank" rel="noreferrer">
                                    www.riskwisdomloans.com.au
                                </a>
                            </div>
                        </div>

                        <div class="contact-form-card">
                            @if (session('status'))
                                <div class="form-alert form-alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="form-alert form-alert-error">
                                    Please complete all required fields and try again.
                                </div>
                            @endif

                            <form action="{{ route('contact.submit') }}" method="post" class="contact-form">
                                @csrf

                                <div class="form-grid">
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

                                    <label class="form-full">
                                        <span>Enquiry</span>
                                        <textarea name="enquiry" rows="5" placeholder="Tell us about your finance goals">{{ old('enquiry') }}</textarea>
                                        @error('enquiry')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </label>
                                </div>

                                <button class="button button-primary button-wide" type="submit">Request a Call Back</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <div class="container footer-grid">
                <div>
                    <a class="brand brand-footer" href="{{ route('home') }}">
                        <span class="brand-mark">RW</span>
                        <span class="brand-copy">
                            <strong>Riskwisdom</strong>
                            <small>Loans</small>
                        </span>
                    </a>
                    <p class="footer-copy">
                        A modern finance homepage built for an original brand direction, ready to expand into a broader
                        Laravel website.
                    </p>
                </div>

                <div>
                    <h3>Quick Links</h3>
                    <ul class="footer-list">
                        <li><a href="#who-we-help">Who We Help</a></li>
                        <li><a href="#solutions">Loan Solutions</a></li>
                        <li><a href="#resources">Resources</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3>Contact</h3>
                    <ul class="footer-list">
                        <li><a href="mailto:info@riskwisdomloans.com.au">info@riskwisdomloans.com.au</a></li>
                        <li>
                            <a href="https://www.riskwisdomloans.com.au" target="_blank" rel="noreferrer">
                                riskwisdomloans.com.au
                            </a>
                        </li>
                        <li>Phone details to be added before launch</li>
                    </ul>
                </div>

                <div>
                    <h3>Compliance</h3>
                    <p class="footer-copy footer-copy-tight">
                        Australian Credit Licence and Credit Representative details should be inserted here before the
                        site goes live.
                    </p>
                    <p class="footer-copy footer-copy-tight">
                        Your full financial situation and requirements need to be considered prior to any offer and
                        acceptance of a loan product.
                    </p>
                </div>
            </div>

            <div class="container footer-bottom">
                <p>&copy; {{ now()->year }} Riskwisdom Loans. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
