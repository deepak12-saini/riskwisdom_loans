@php
    $logoUrl = asset('images/risk-wisdom-loans-logo.png');
    $faviconUrl = asset('images/favicon.png');


    $navigation = [
        [
            'label' => 'About',
            'links' => [
                ['title' => 'About Riskwisdom Loans', 'href' => route('pages.about')],
                ['title' => 'Our Approach', 'href' => route('home').'#why-riskwisdom'],
                ['title' => 'Contact Us', 'href' => contact_url()],
            ],
        ],
        [
            'label' => 'Who We Help',
            'links' => [
                ['title' => 'First Home Buyers', 'href' => route('pages.first-home-buyer')],
                ['title' => 'Families', 'href' => route('home').'#who-we-help'],
                ['title' => 'Investors', 'href' => route('pages.investment')],
                ['title' => 'Business Owners', 'href' => route('pages.commercial')],
            ],
        ],
        [
            'label' => 'Solutions',
            'links' => [
                ['title' => 'Home Loans', 'href' => route('pages.home-loans')],
                ['title' => 'Refinance Loans', 'href' => route('pages.refinance')],
                ['title' => 'Commercial Loans', 'href' => route('pages.commercial')],
                ['title' => 'Investment Loans', 'href' => route('pages.investment')],
            ],
        ],
        [
            'label' => 'Resources',
            'links' => [
                ['title' => 'Book a call', 'href' => route('book')],
                ['title' => 'Free rate review', 'href' => route('rate-review')],
                ['title' => 'Borrowing Power Calculator', 'href' => route('tools.borrowing-power')],
                ['title' => 'Repayment Calculator', 'href' => route('tools.repayment-calculator')],
                ['title' => 'Stamp Duty Calculator', 'href' => route('tools.stamp-duty')],
            ],
        ],
        [
            'label' => 'Community',
            'links' => [
                ['title' => 'Referral Partners', 'href' => route('pages.partners')],
                ['title' => 'Client Experience', 'href' => route('home').'#community'],
                ['title' => 'Book a Consultation', 'href' => route('book')],
            ],
        ],
    ];
@endphp

<header class="rw-header {{ $headerClass ?? 'rw-header--static' }}">
    <div class="container rw-header__wrap">
        <div class="rw-header__bar">
            <a class="rw-brand" href="{{ route('home') }}">
                <img
                    class="rw-brand__logo"
                    src="{{ $logoUrl }}"
                    alt="Risk Wisdom Loans"
                    width="220"
                    height="64"
                    decoding="async"
                >
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

            <div class="rw-header__actions">
                @include('partials.book-chat-button', [
                    'variant' => 'solid',
                    'cta' => 'header-book-chat',
                    'extraClass' => 'rw-button--compact',
                ])
                <a class="rw-button rw-button--outline rw-button--compact" href="{{ rate_review_url() }}" data-cta="header-primary">Free review</a>
            </div>

            <div class="rw-header__mobile-actions">
                @include('partials.phone-link', [
                    'variant' => 'icon',
                    'cta' => 'header-mobile-phone',
                    'extraClass' => 'rw-header__phone',
                ])

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

            @include('partials.phone-link', [
                'variant' => 'button',
                'label' => 'Call ' . config('riskwisdom.phone'),
                'cta' => 'mobile-menu-phone',
                'extraClass' => 'rw-mobile-menu__cta',
                'wide' => true,
            ])
            @include('partials.book-chat-button', ['variant' => 'solid', 'cta' => 'mobile-menu-book-chat', 'extraClass' => 'rw-mobile-menu__cta'])
            <a class="rw-button rw-button--outline rw-mobile-menu__cta" href="{{ rate_review_url() }}" data-cta="mobile-menu-primary">Get free loan review</a>
        </div>
    </div>
</header>
