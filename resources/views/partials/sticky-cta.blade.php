<div class="rw-sticky-cta" id="rw-sticky-cta" hidden>
    <div class="rw-sticky-cta__mobile">
        @include('partials.book-chat-button', ['variant' => 'solid', 'cta' => 'sticky-mobile-book-chat', 'extraClass' => 'rw-sticky-cta__consult'])
        <a class="rw-button rw-button--outline rw-sticky-cta__phone rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}" data-cta="sticky-mobile-phone">
            Call now
        </a>
    </div>

    <div class="rw-sticky-cta__desktop">
        <p>Ready for a free loan review?</p>
        @include('partials.book-chat-button', ['variant' => 'solid', 'cta' => 'sticky-desktop-book-chat'])
        <a class="rw-link-arrow rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}" data-cta="sticky-desktop-phone">{{ config('riskwisdom.phone') }}</a>
    </div>
</div>
