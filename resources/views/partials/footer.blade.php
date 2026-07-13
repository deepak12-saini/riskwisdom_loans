@php
    $logoUrl = asset('images/risk-wisdom-loans-logo.png');
@endphp

<footer class="rw-footer">
    <div class="container rw-footer__grid">
        <div>
            <a class="rw-brand rw-brand--footer" href="{{ route('home') }}">
                <img
                    class="rw-brand__logo"
                    src="{{ $logoUrl }}"
                    alt="Risk Wisdom Loans"
                    width="240"
                    height="70"
                    decoding="async"
                >
            </a>
            <p>
                Riskwisdom Loans provides clear, practical lending guidance for home buyers, refinancers,
                investors, professionals, and business owners.
            </p>
            <p>{{ config('riskwisdom.legal_name') }}</p>
        </div>

        <div>
            <h3>Services</h3>
            <ul>
                <li><a href="{{ route('pages.home-loans') }}">Home Loans</a></li>
                <li><a href="{{ route('pages.refinance') }}">Refinance Loans</a></li>
                <li><a href="{{ route('pages.refinance-rates') }}">Refinance Rates</a></li>
                <li><a href="{{ route('rate-review') }}">Free Rate Review</a></li>
                <li><a href="{{ route('pages.commercial') }}">Commercial Loans</a></li>
                <li><a href="{{ route('pages.investment') }}">Investment Loans</a></li>
            </ul>
        </div>

        <div>
            <h3>Information</h3>
            <ul>
                <li><a href="{{ route('pages.about') }}">About Us</a></li>
                <li><a href="{{ route('guides.index') }}">Guides & Insights</a></li>
                <li><a href="{{ route('tools.borrowing-power') }}">Calculators</a></li>
                <li><a href="{{ route('book') }}">Book a call</a></li>
                <li><a href="{{ contact_url() }}">Contact Us</a></li>
                <li><a href="{{ route('pages.privacy') }}">Privacy Policy</a></li>
                <li><a href="{{ route('pages.credit-guide') }}">Credit Guide</a></li>
            </ul>
        </div>

        <div class="rw-footer__contact">
            <h3>Contact Us</h3>
            <ul>
                @if (calendly_url())
                    <li>
                        <a
                            class="rw-footer__contact-link js-book-chat"
                            href="{{ route('book') }}"
                            data-cta="footer-book-chat"
                        >
                            <span class="rw-footer__contact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="5" width="18" height="16" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </span>
                            <span>Book a call</span>
                        </a>
                    </li>
                @endif
                <li>
                    @include('partials.phone-link', [
                        'variant' => 'footer',
                        'cta' => 'footer-phone',
                        'extraClass' => 'rw-footer__contact-link',
                    ])
                </li>
                <li>
                    <a class="rw-footer__contact-link" href="mailto:{{ config('riskwisdom.email') }}">
                        <span class="rw-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 7.5 11.2 12.9c.48.36 1.12.36 1.6 0L20 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                            </svg>
                        </span>
                        <span>{{ config('riskwisdom.email') }}</span>
                    </a>
                </li>
                @if (business_address_line() !== '')
                    <li>
                        <span class="rw-footer__contact-link rw-footer__contact-link--static">
                            <span class="rw-footer__contact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 21s7-4.5 7-10a7 7 0 1 0-14 0c0 5.5 7 10 7 10Z" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <span>{{ business_address_line() }}</span>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="container">
        @include('partials.newsletter-signup')
    </div>

    <div class="container rw-footer__bottom">
        <p>&copy; {{ now()->year }} {{ config('riskwisdom.brand_name') }}. {{ config('riskwisdom.legal_name') }}. All rights reserved.</p>
    </div>
</footer>
