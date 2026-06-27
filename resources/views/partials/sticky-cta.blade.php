@php
    $stickyVariant = $stickyVariant ?? 'default';
@endphp

<div class="rw-sticky-cta @if ($stickyVariant === 'call-only') rw-sticky-cta--call-only @endif" id="rw-sticky-cta" hidden>
    @if ($stickyVariant === 'call-only')
        <div class="rw-sticky-cta__mobile rw-sticky-cta__mobile--call-only">
            @include('partials.phone-link', [
                'variant' => 'sticky',
                'label' => 'Call now',
                'cta' => 'sticky-mobile-phone',
                'extraClass' => 'rw-sticky-cta__phone rw-sticky-cta__phone--solo',
                'wide' => true,
            ])
        </div>
    @else
        <div class="rw-sticky-cta__mobile">
            @include('partials.phone-link', [
                'variant' => 'sticky',
                'label' => 'Call now',
                'cta' => 'sticky-mobile-phone',
                'extraClass' => 'rw-sticky-cta__phone',
            ])
            @include('partials.book-chat-button', [
                'variant' => 'outline',
                'cta' => 'sticky-mobile-book-chat',
                'extraClass' => 'rw-sticky-cta__consult',
            ])
        </div>
    @endif

    <div class="rw-sticky-cta__desktop">
        <p>Ready for a free loan review?</p>
        @include('partials.book-chat-button', ['variant' => 'solid', 'cta' => 'sticky-desktop-book-chat'])
        @include('partials.phone-link', [
            'variant' => 'text',
            'cta' => 'sticky-desktop-phone',
            'extraClass' => 'rw-link-arrow',
        ])
    </div>
</div>
