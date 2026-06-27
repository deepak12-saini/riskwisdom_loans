@php
    $variant = $variant ?? 'text';
    $label = $label ?? config('riskwisdom.phone');
    $cta = $cta ?? 'phone-link';
    $extraClass = trim($extraClass ?? '');
    $wide = ! empty($wide);

    $classes = collect([
        'rw-phone-link',
        'rw-track-phone',
        'rw-phone-link--' . $variant,
        $variant === 'button' ? 'rw-button rw-button--outline' : null,
        $variant === 'button-solid' ? 'rw-button rw-button--solid' : null,
        $variant === 'ghost' ? 'rw-button rw-button--ghost' : null,
        $variant === 'sticky' ? 'rw-button rw-button--solid' : null,
        $variant === 'sticky-outline' ? 'rw-button rw-button--outline' : null,
        $wide ? 'rw-button--wide' : null,
        $extraClass !== '' ? $extraClass : null,
    ])->filter()->implode(' ');
@endphp

<a
    href="tel:{{ config('riskwisdom.phone_tel') }}"
    class="{{ $classes }}"
    data-cta="{{ $cta }}"
    @if ($variant === 'icon') aria-label="Call {{ config('riskwisdom.phone') }}" @endif
>
    @if ($variant === 'icon')
        <svg class="rw-phone-link__icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.3.56 3.52.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.22.19 2.4.56 3.52a1 1 0 0 1-.24 1.02l-2.2 2.25Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    @elseif ($variant === 'footer')
        <span class="rw-footer__contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.3.56 3.52.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.22.19 2.4.56 3.52a1 1 0 0 1-.24 1.02l-2.2 2.25Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
        <span>{{ $label }}</span>
    @elseif ($variant === 'sticky')
        <svg class="rw-phone-link__icon rw-phone-link__icon--inline" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.3.56 3.52.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.22.19 2.4.56 3.52a1 1 0 0 1-.24 1.02l-2.2 2.25Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>{{ $label }}</span>
    @else
        {{ $label }}
    @endif
</a>
