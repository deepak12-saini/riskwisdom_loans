@php
    $panel = config('riskwisdom.lender_panel');
@endphp

<section class="rw-lender-strip" aria-labelledby="lender-strip-title">
    <div class="rw-lender-strip__copy">
        <span class="rw-section-label">Lender Panel</span>
        <h2 id="lender-strip-title">{{ $panel['title'] ?? 'Lender panel support across major banks and specialist lenders.' }}</h2>
    </div>

    <div class="rw-lender-strip__list" role="list" aria-label="Representative lender panel">
        @foreach (($panel['items'] ?? []) as $item)
            @php
                $name = is_array($item) ? ($item['name'] ?? '') : (string) $item;
                $logo = is_array($item) ? ($item['logo'] ?? null) : null;
            @endphp
            <span role="listitem" class="rw-lender-strip__item">
                @if ($logo)
                    <img
                        class="rw-lender-strip__logo"
                        src="{{ asset($logo) }}"
                        alt="{{ $name }}"
                        width="140"
                        height="40"
                        loading="lazy"
                        decoding="async"
                    >
                @else
                    <span class="rw-lender-strip__name">{{ $name }}</span>
                @endif
            </span>
        @endforeach
    </div>
</section>
