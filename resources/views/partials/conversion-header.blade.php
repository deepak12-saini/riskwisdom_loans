@php
    $logoUrl = asset('images/risk-wisdom-loans-logo.png');
@endphp

<header class="rw-conversion-header">
    <div class="container rw-conversion-header__inner">
        <a class="rw-brand" href="{{ route('home') }}">
            <img
                class="rw-brand__logo"
                src="{{ $logoUrl }}"
                alt="Risk Wisdom Loans"
                width="200"
                height="58"
                decoding="async"
            >
        </a>

        <div class="rw-conversion-header__actions">
            @include('partials.phone-link', [
                'variant' => 'button',
                'label' => config('riskwisdom.phone'),
                'cta' => 'conversion-header-phone',
            ])
        </div>
    </div>
</header>
