@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_heading', 'Dashboard')

@section('topbar_actions')
    @if ($canLeads)
        <a class="rw-button rw-button--ghost" href="{{ route('admin.enquiries.index', ['filter' => 'mine']) }}">My leads</a>
        <a class="rw-button rw-button--ghost" href="{{ route('admin.enquiries.index', ['filter' => 'new_leads']) }}">New leads</a>
        <a class="rw-button rw-button--solid" href="{{ route('admin.enquiries.index', ['filter' => 'callbacks_due']) }}">My callbacks today</a>
    @endif
@endsection

@section('content')
    <section class="rw-dash">
        <header class="rw-dash__hero">
            <div>
                <p class="rw-dash__eyebrow">{{ $todayLabel }}</p>
                <h2>{{ $greeting }}, {{ $userName }}</h2>
                <p>Work today’s queue first — new leads, promised callbacks, then meetings and overdue tasks.</p>
            </div>
            <div class="rw-dash__hero-meta">
                @if ($canLeads)
                    <span><strong>{{ number_format($weekLeadsCount) }}</strong> leads this week</span>
                @endif
                @if ($canClients)
                    <span><strong>{{ number_format($activeClientsCount) }}</strong> active clients</span>
                @endif
                @if ($canTasks)
                    <span><strong>{{ number_format($openTasksCount) }}</strong> open tasks</span>
                @endif
            </div>
        </header>

        <div class="rw-dash__stats">
            @foreach ($stats as $stat)
                @if ($stat['href'])
                    <a class="rw-dash-stat rw-dash-stat--{{ $stat['tone'] }}" href="{{ $stat['href'] }}">
                        <span>{{ $stat['label'] }}</span>
                        <strong>{{ number_format($stat['value']) }}</strong>
                        <small>{{ $stat['hint'] }}</small>
                    </a>
                @else
                    <div class="rw-dash-stat rw-dash-stat--muted">
                        <span>{{ $stat['label'] }}</span>
                        <strong>{{ number_format($stat['value']) }}</strong>
                        <small>{{ $stat['hint'] }}</small>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="rw-dash__grid">
            <article class="rw-dash-panel">
                <div class="rw-dash-panel__head">
                    <div>
                        <h3>My leads</h3>
                        <p>Leads assigned to you — keep working these first.</p>
                    </div>
                    @if ($canLeads)
                        <a class="rw-admin-link" href="{{ route('admin.enquiries.index', ['filter' => 'mine']) }}">View all</a>
                    @endif
                </div>
                @include('admin.partials.dashboard-lead-list', [
                    'items' => $myLeads,
                    'empty' => 'Nothing assigned to you yet. Take a new lead or ask an admin to assign one.',
                    'showCallback' => false,
                ])
            </article>

            <article class="rw-dash-panel">
                <div class="rw-dash-panel__head">
                    <div>
                        <h3>New leads to call</h3>
                        <p>First contact — call these before they go cold.</p>
                    </div>
                    @if ($canLeads)
                        <a class="rw-admin-link" href="{{ route('admin.enquiries.index', ['filter' => 'new_leads']) }}">View all</a>
                    @endif
                </div>
                @include('admin.partials.dashboard-lead-list', [
                    'items' => $newLeads,
                    'empty' => 'No new leads waiting. You are up to date.',
                    'showCallback' => false,
                ])
            </article>

            <article class="rw-dash-panel rw-dash-panel--callbacks">
                <div class="rw-dash-panel__head">
                    <div>
                        <h3>My callbacks today</h3>
                        <p>Promised follow-ups due today or overdue.</p>
                    </div>
                    @if ($canLeads)
                        <a class="rw-admin-link" href="{{ route('admin.enquiries.index', ['filter' => 'callbacks_due']) }}">View all</a>
                    @endif
                </div>
                @include('admin.partials.dashboard-lead-list', [
                    'items' => $callbacks,
                    'empty' => 'No callbacks due today — you are all caught up.',
                    'showCallback' => true,
                ])
            </article>

            <article class="rw-dash-panel">
                <div class="rw-dash-panel__head">
                    <div>
                        <h3>Meetings today</h3>
                        <p>Calendly bookings scheduled for today.</p>
                    </div>
                    @if ($canLeads)
                        <a class="rw-admin-link" href="{{ route('admin.enquiries.index', ['filter' => 'calendly']) }}">View all</a>
                    @endif
                </div>
                @if ($todayBookings->isEmpty())
                    <p class="rw-dash-empty">No meetings booked for today.</p>
                @else
                    <ul class="rw-dash-list">
                        @foreach ($todayBookings as $enquiry)
                            @php
                                $startLabel = 'Time TBC';
                                try {
                                    $start = $enquiry->metadata['calendly_start_time'] ?? null;
                                    if ($start) {
                                        $startLabel = (new \DateTimeImmutable((string) $start))
                                            ->setTimezone(new \DateTimeZone((string) ($enquiry->metadata['calendly_timezone'] ?? config('app.timezone'))))
                                            ->format('g:ia');
                                    }
                                } catch (\Throwable) {
                                    $startLabel = 'Time TBC';
                                }
                            @endphp
                            <li>
                                <a href="{{ route('admin.enquiries.show', $enquiry) }}">
                                    <strong>{{ $enquiry->full_name }}</strong>
                                    <span>{{ $startLabel }} · {{ $enquiry->phone }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>

            <article class="rw-dash-panel">
                <div class="rw-dash-panel__head">
                    <div>
                        <h3>{{ $canTasks ? 'Overdue tasks' : 'Recent leads' }}</h3>
                        <p>{{ $canTasks ? 'Client checklist items past due.' : 'Latest website enquiries.' }}</p>
                    </div>
                    @if ($canTasks)
                        <a class="rw-admin-link" href="{{ route('admin.tasks.index', ['filter' => 'overdue']) }}">View all</a>
                    @elseif ($canLeads)
                        <a class="rw-admin-link" href="{{ route('admin.enquiries.index') }}">View all</a>
                    @endif
                </div>

                @if ($canTasks)
                    @if ($overdueTasks->isEmpty())
                        <p class="rw-dash-empty">No overdue tasks.</p>
                    @else
                        <ul class="rw-dash-list">
                            @foreach ($overdueTasks as $task)
                                <li>
                                    <a href="{{ route('admin.clients.show', $task->client) }}#task-{{ $task->id }}">
                                        <strong>{{ $task->title }}</strong>
                                        <span>{{ $task->client?->full_name ?? 'Client' }} · due {{ $task->due_date?->format('d M') }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    @include('admin.partials.dashboard-lead-list', [
                        'items' => $recentLeads,
                        'empty' => 'No recent leads yet.',
                        'showCallback' => false,
                    ])
                @endif
            </article>
        </div>
    </section>
@endsection
