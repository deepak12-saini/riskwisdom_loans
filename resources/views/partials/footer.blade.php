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
                <li><a href="{{ route('pages.commercial') }}">Commercial Loans</a></li>
                <li><a href="{{ route('pages.investment') }}">Investment Loans</a></li>
            </ul>
        </div>

        <div>
            <h3>Information</h3>
            <ul>
                <li><a href="{{ route('home').'#about' }}">About Us</a></li>
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
                    <a class="rw-footer__contact-link rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}" data-cta="footer-phone">
                        <span class="rw-footer__contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.3.56 3.52.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.22.19 2.4.56 3.52a1 1 0 0 1-.24 1.02l-2.2 2.25Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span>{{ config('riskwisdom.phone') }}</span>
                    </a>
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
            </ul>
        </div>
    </div>

    <div class="container rw-footer__bottom">
        <p>&copy; {{ now()->year }} {{ config('riskwisdom.brand_name') }}. {{ config('riskwisdom.legal_name') }}. All rights reserved.</p>
    </div>
</footer>
