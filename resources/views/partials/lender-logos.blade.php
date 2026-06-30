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
            <span role="listitem" class="rw-lender-strip__item">{{ $item }}</span>
        @endforeach
    </div>
</section>
