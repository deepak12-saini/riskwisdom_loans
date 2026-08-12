@php
    $logoUrl = asset('images/risk-wisdom-loans-logo.png');
    $user = auth()->user();
    $taskFilter = request()->routeIs('admin.tasks.*') ? request()->query('filter', 'open') : 'open';

    $leadItems = [];
    if ($user?->canAdmin('enquiries.view')) {
        $leadItems[] = [
            'label' => 'Leads',
            'href' => route('admin.enquiries.index'),
            'icon' => 'leads',
            'active' => request()->routeIs('admin.enquiries.*'),
        ];
    }

    $clientItems = [];
    if ($user?->canAdmin('clients.view')) {
        $clientItems[] = [
            'label' => 'Client files',
            'href' => route('admin.clients.index'),
            'icon' => 'clients',
            'active' => request()->routeIs('admin.clients.*') && ! request()->routeIs('admin.tasks.*'),
        ];
    }
    if ($user?->canAdmin('tasks.view')) {
        $openTaskCount = \App\Models\Task::query()->open()->count();
        $overdueTaskCount = \App\Models\Task::query()->overdue()->count();

        if ($openTaskCount > 0 || (request()->routeIs('admin.tasks.index') && $taskFilter === 'open')) {
            $clientItems[] = [
                'label' => 'Open tasks',
                'href' => route('admin.tasks.index', ['filter' => 'open']),
                'icon' => 'tasks',
                'active' => request()->routeIs('admin.tasks.index') && $taskFilter === 'open',
                'count' => $openTaskCount,
            ];
        }

        if ($overdueTaskCount > 0 || (request()->routeIs('admin.tasks.index') && $taskFilter === 'overdue')) {
            $clientItems[] = [
                'label' => 'Overdue tasks',
                'href' => route('admin.tasks.index', ['filter' => 'overdue']),
                'icon' => 'urgent',
                'active' => request()->routeIs('admin.tasks.index') && $taskFilter === 'overdue',
                'count' => $overdueTaskCount,
                'urgent' => true,
            ];
        }
    }

    $toolItems = [];
    if ($user?->canAdmin('enquiries.export')) {
        $toolItems[] = [
            'label' => 'Export CSV',
            'href' => route('admin.enquiries.export'),
            'icon' => 'export',
            'active' => false,
        ];
    }
    if ($user?->canAdmin('users.manage')) {
        $toolItems[] = [
            'label' => 'Users',
            'href' => route('admin.users.index'),
            'icon' => 'clients',
            'active' => request()->routeIs('admin.users.*'),
        ];
    }
    $toolItems[] = [
        'label' => 'Website',
        'href' => route('home'),
        'icon' => 'website',
        'external' => true,
        'active' => false,
    ];
@endphp

<aside class="rw-admin-sidebar">
    <div class="rw-admin-sidebar__brand">
        <a href="{{ $user?->canAdmin('enquiries.view') ? route('admin.enquiries.index') : route('home') }}" title="Riskwisdom Loans Admin">
            <img src="{{ $logoUrl }}" alt="Risk Wisdom Loans">
        </a>
    </div>

    <nav class="rw-admin-sidebar__nav" aria-label="Admin navigation">
        @if ($leadItems !== [])
            <p class="rw-admin-sidebar__section">Leads</p>
            @foreach ($leadItems as $item)
                <a
                    class="rw-admin-sidebar__link @if ($item['active']) is-active @endif"
                    href="{{ $item['href'] }}"
                    title="{{ $item['label'] }}"
                >
                    @include('admin.partials.sidebar-icon', ['icon' => $item['icon']])
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endif

        @if ($clientItems !== [])
            <p class="rw-admin-sidebar__section">Clients</p>
            @foreach ($clientItems as $item)
                <a
                    class="rw-admin-sidebar__link @if ($item['active']) is-active @endif"
                    href="{{ $item['href'] }}"
                    title="{{ $item['label'] }}{{ isset($item['count']) ? ' ('.$item['count'].')' : '' }}"
                >
                    @include('admin.partials.sidebar-icon', ['icon' => $item['icon']])
                    <span>{{ $item['label'] }}</span>
                    @if (isset($item['count']))
                        <em class="rw-admin-sidebar__count @if (! empty($item['urgent'])) is-urgent @endif">{{ number_format($item['count']) }}</em>
                    @endif
                </a>
            @endforeach
        @endif

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
                <span class="rw-admin-sidebar__avatar">{{ strtoupper(substr((string) $user->username, 0, 1)) }}</span>
                <span class="rw-admin-sidebar__user-text">
                    <strong>{{ $user->username }}</strong>
                    <small>{{ $user->roleLabel() }}</small>
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
