@php
    $logoUrl = asset('images/risk-wisdom-loans-logo.png');
    $user = auth()->user();
    $filter = request()->query('filter', 'all');

    $menuItems = [
        [
            'label' => 'All leads',
            'href' => route('admin.enquiries.index'),
            'icon' => 'leads',
            'active' => request()->routeIs('admin.enquiries.index') && $filter === 'all',
        ],
        [
            'label' => 'Ready now',
            'href' => route('admin.enquiries.index', ['filter' => 'ready_now']),
            'icon' => 'urgent',
            'active' => $filter === 'ready_now',
        ],
        [
            'label' => 'This week',
            'href' => route('admin.enquiries.index', ['filter' => 'this_week']),
            'icon' => 'week',
            'active' => $filter === 'this_week',
        ],
        [
            'label' => 'Today',
            'href' => route('admin.enquiries.index', ['filter' => 'today']),
            'icon' => 'today',
            'active' => $filter === 'today',
        ],
    ];

    $toolItems = [
        [
            'label' => 'Export CSV',
            'href' => route('admin.enquiries.export'),
            'icon' => 'export',
            'active' => false,
        ],
        [
            'label' => 'Website',
            'href' => route('home'),
            'icon' => 'website',
            'external' => true,
            'active' => false,
        ],
    ];
@endphp

<aside class="rw-admin-sidebar">
    <div class="rw-admin-sidebar__brand">
        <a href="{{ route('admin.enquiries.index') }}" title="Riskwisdom Loans Admin">
            <img src="{{ $logoUrl }}" alt="Risk Wisdom Loans">
        </a>
    </div>

    <nav class="rw-admin-sidebar__nav" aria-label="Admin navigation">
        <p class="rw-admin-sidebar__section">Leads</p>
        @foreach ($menuItems as $item)
            <a
                class="rw-admin-sidebar__link @if ($item['active']) is-active @endif"
                href="{{ $item['href'] }}"
                title="{{ $item['label'] }}"
            >
                @include('admin.partials.sidebar-icon', ['icon' => $item['icon']])
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        <p class="rw-admin-sidebar__section">Tools</p>
        @foreach ($toolItems as $item)
            <a
                class="rw-admin-sidebar__link @if ($item['active']) is-active @endif"
                href="{{ $item['href'] }}"
                @if (! empty($item['external'])) target="_blank" rel="noreferrer" @endif
                title="{{ $item['label'] }}"
            >
                @include('admin.partials.sidebar-icon', ['icon' => $item['icon']])
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="rw-admin-sidebar__footer">
        @if ($user)
            <div class="rw-admin-sidebar__user" title="{{ $user->email }}">
                <span class="rw-admin-sidebar__avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                <span class="rw-admin-sidebar__user-text">
                    <strong>{{ $user->username }}</strong>
                </span>
            </div>
        @endif
        <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button class="rw-admin-sidebar__logout" type="submit" title="Log out">
                @include('admin.partials.sidebar-icon', ['icon' => 'logout'])
                <span>Log out</span>
            </button>
        </form>
    </div>
</aside>
