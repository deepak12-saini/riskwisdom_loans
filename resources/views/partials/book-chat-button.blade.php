@php
    $variant = $variant ?? 'solid';
    $cta = $cta ?? 'book-chat';
    $buttonLabel = $buttonLabel ?? 'Book a call';
    $extraClass = $extraClass ?? '';
@endphp

@if (calendly_url())
    <a
        href="{{ route('book') }}"
        class="rw-button rw-button--{{ $variant }} js-book-chat {{ $extraClass }}"
        data-cta="{{ $cta }}"
    >
        {{ $buttonLabel }}
    </a>
@endif
