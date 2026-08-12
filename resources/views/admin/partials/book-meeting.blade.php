@php
    $bookName = trim((string) ($bookName ?? ''));
    $bookEmail = trim((string) ($bookEmail ?? ''));
    $bookPhone = trim((string) ($bookPhone ?? ''));
    $bookFirstName = trim((string) ($bookFirstName ?? ''));
    $bookLastName = trim((string) ($bookLastName ?? ''));
    $calendlyPrefill = calendly_prefill_url($bookName ?: null, $bookEmail ?: null, $bookPhone ?: null, $bookFirstName ?: null, $bookLastName ?: null);
    $shareBookUrl = route('book');
    $variant = $variant ?? 'buttons'; // buttons | compact
@endphp

@if ($calendlyPrefill || $shareBookUrl)
    <div class="rw-book-meeting @if ($variant === 'compact') rw-book-meeting--compact @endif">
        @if ($variant !== 'compact')
            <div class="rw-book-meeting__copy">
                <strong>Book meeting</strong>
                <p>Open Calendly with this contact prefilled, or copy the public booking link to send by SMS.</p>
            </div>
        @endif

        <div class="rw-book-meeting__actions">
            @if ($calendlyPrefill)
                <a
                    class="rw-button rw-button--solid @if ($variant === 'compact') rw-button--sm @endif"
                    href="{{ $calendlyPrefill }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    data-cta="admin-book-meeting"
                >
                    Book meeting
                </a>
            @endif

            <button
                type="button"
                class="rw-button rw-button--ghost @if ($variant === 'compact') rw-button--sm @endif js-copy-booking-link"
                data-copy-url="{{ $shareBookUrl }}"
                data-copy-label="Copy booking link"
                data-copied-label="Link copied"
            >
                Copy booking link
            </button>
        </div>
    </div>
@endif
