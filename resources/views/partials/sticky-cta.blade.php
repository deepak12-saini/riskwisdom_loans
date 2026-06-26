<div class="rw-sticky-cta" id="rw-sticky-cta" hidden>
    <div class="rw-sticky-cta__mobile">
        <a class="rw-button rw-button--solid rw-sticky-cta__consult" href="{{ contact_url() }}" data-cta="sticky-mobile-consult">
            Free consultation
        </a>
        <a class="rw-button rw-button--outline rw-sticky-cta__phone rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}" data-cta="sticky-mobile-phone">
            Call now
        </a>
    </div>

    <div class="rw-sticky-cta__desktop">
        <p>Ready for a free loan review?</p>
        <a class="rw-button rw-button--solid" href="{{ contact_url() }}" data-cta="sticky-desktop-consult">Get free loan review</a>
        <a class="rw-link-arrow rw-track-phone" href="tel:{{ config('riskwisdom.phone_tel') }}" data-cta="sticky-desktop-phone">{{ config('riskwisdom.phone') }}</a>
    </div>
</div>
