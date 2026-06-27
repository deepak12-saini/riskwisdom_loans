@php
    $tools = [
        'borrowing-power' => [
            'label' => 'Borrowing power',
            'hint' => 'How much can I borrow?',
            'route' => route('tools.borrowing-power'),
        ],
        'repayment' => [
            'label' => 'Repayment',
            'hint' => 'Monthly repayments',
            'route' => route('tools.repayment-calculator'),
        ],
        'stamp-duty' => [
            'label' => 'Stamp duty',
            'hint' => 'Upfront government costs',
            'route' => route('tools.stamp-duty'),
        ],
    ];
@endphp

<nav class="rw-calc-switcher" aria-label="Calculator tools">
    @foreach ($tools as $key => $tool)
        <a
            href="{{ $tool['route'] }}"
            @class(['rw-calc-switcher__item', 'is-active' => ($calculator ?? '') === $key])
            @if (($calculator ?? '') === $key) aria-current="page" @endif
        >
            <span class="rw-calc-switcher__label">{{ $tool['label'] }}</span>
            <span class="rw-calc-switcher__hint">{{ $tool['hint'] }}</span>
        </a>
    @endforeach
</nav>
