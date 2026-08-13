@extends('admin.layout')

@section('title', 'Enquiries')
@section('page_heading', $pageHeading ?? 'Website enquiries')

@section('topbar_actions')
    @if (auth()->user()?->canAdmin('enquiries.export'))
        <a class="rw-button rw-button--solid" href="{{ route('admin.enquiries.export') }}">Export CSV</a>
    @endif
@endsection

@section('content')
    <section class="rw-admin-card">
        <div class="rw-admin-filters">
            <form method="get" action="{{ route('admin.enquiries.index') }}" class="rw-admin-filters__search">
                @if ($filter !== 'all')
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <label class="rw-admin-filters__search-field">
                    <span class="visually-hidden">Search leads</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Search name, email, phone…"
                    >
                </label>
                <button class="rw-button rw-button--solid rw-button--sm" type="submit">Search</button>
                @if (($q ?? '') !== '')
                    <a class="rw-admin-link" href="{{ route('admin.enquiries.index', array_filter(['filter' => $filter !== 'all' ? $filter : null])) }}">Clear</a>
                @endif
            </form>

            <div class="rw-admin-filter-tabs">
                <a href="{{ route('admin.enquiries.index', array_filter(['q' => $q ?: null])) }}" class="@if ($filter === 'all') is-active @endif">
                    All <em>{{ number_format($stats['total']) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'ready_now', 'q' => $q ?: null])) }}" class="@if ($filter === 'ready_now') is-active @endif">
                    Ready now <em>{{ number_format($stats['ready_now']) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'this_week', 'q' => $q ?: null])) }}" class="@if ($filter === 'this_week') is-active @endif">
                    This week <em>{{ number_format($stats['this_week']) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'today', 'q' => $q ?: null])) }}" class="@if ($filter === 'today') is-active @endif">
                    Today <em>{{ number_format($stats['today']) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'new_leads', 'q' => $q ?: null])) }}" class="@if ($filter === 'new_leads') is-active @endif">
                    New <em>{{ number_format($stats['new_leads'] ?? 0) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'callbacks_due', 'q' => $q ?: null])) }}" class="@if ($filter === 'callbacks_due') is-active @endif">
                    Callbacks due <em>{{ number_format($stats['callbacks_due'] ?? 0) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'mine', 'q' => $q ?: null])) }}" class="@if ($filter === 'mine') is-active @endif">
                    My leads <em>{{ number_format($stats['mine'] ?? 0) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'unassigned', 'q' => $q ?: null])) }}" class="@if ($filter === 'unassigned') is-active @endif">
                    Unassigned <em>{{ number_format($stats['unassigned'] ?? 0) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'calendly', 'q' => $q ?: null])) }}" class="@if ($filter === 'calendly') is-active @endif">
                    Calendly <em>{{ number_format($stats['calendly'] ?? 0) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'lead_only', 'q' => $q ?: null])) }}" class="@if ($filter === 'lead_only') is-active @endif">
                    Lead only <em>{{ number_format($stats['lead_only']) }}</em>
                </a>
                <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'converted', 'q' => $q ?: null])) }}" class="@if ($filter === 'converted') is-active @endif">
                    Has client file <em>{{ number_format($stats['converted']) }}</em>
                </a>
                @if ($showPaidAds ?? config('riskwisdom.admin_show_paid_ads', false))
                    <a href="{{ route('admin.enquiries.index', array_filter(['filter' => 'paid', 'q' => $q ?: null])) }}" class="@if ($filter === 'paid') is-active @endif">
                        Paid (CPC) <em>{{ number_format($stats['paid'] ?? 0) }}</em>
                    </a>
                @endif
            </div>
        </div>

        <div class="rw-admin-table-wrap">
            <table class="rw-admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Assigned</th>
                        <th>Contact</th>
                        <th>Loan type</th>
                        <th>Timeline</th>
                        <th>Call status</th>
                        <th>State</th>
                        <th>Source</th>
                        <th>UTM</th>
                        <th>Marketing</th>
                        <th>Mailchimp</th>
                        <th>File</th>
                        <th>Enquiry</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enquiries as $enquiry)
                        <tr>
                            <td class="rw-admin-table__date">{{ $enquiry->created_at?->format('d M Y') }}<br><small>{{ $enquiry->created_at?->format('H:i') }}</small></td>
                            <td>
                                <span class="rw-admin-pill">{{ config('riskwisdom.lead_types')[$enquiry->lead_type] ?? $enquiry->lead_type }}</span>
                            </td>
                            <td><strong>{{ $enquiry->full_name }}</strong></td>
                            <td>
                                <span class="rw-admin-pill @if ($enquiry->assigned_user_id) rw-admin-pill--accent @else rw-admin-pill--muted @endif">
                                    {{ $enquiry->assigneeLabel() }}
                                </span>
                            </td>
                            <td class="rw-admin-table__contact">
                                <a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                                <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                            </td>
                            <td>{{ config('riskwisdom.loan_types')[$enquiry->loan_type] ?? $enquiry->loan_type }}</td>
                            <td>
                                @if ($enquiry->timeline === 'ready_now')
                                    <span class="rw-admin-pill rw-admin-pill--urgent">Ready now</span>
                                @else
                                    <span class="rw-admin-pill">{{ config('riskwisdom.timelines')[$enquiry->timeline] ?? $enquiry->timeline }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="rw-admin-pill rw-admin-pill--call-{{ $enquiry->call_status ?? 'new' }}">
                                    {{ $enquiry->callStatusLabel() }}
                                </span>
                                @if (($enquiry->call_status ?? '') === 'callback' && $enquiry->callback_at)
                                    <br><small>{{ $enquiry->callback_at->format('d M g:ia') }}</small>
                                @endif
                            </td>
                            <td>{{ $enquiry->state }}</td>
                            <td>{{ $enquiry->source ?: '—' }}</td>
                            <td class="rw-admin-table__utm">
                                @if ($enquiry->utm_source || $enquiry->utm_campaign)
                                    <small>{{ $enquiry->utm_source ?: '—' }} / {{ $enquiry->utm_medium ?: '—' }}</small>
                                    @if ($enquiry->utm_campaign)
                                        <br><small>{{ $enquiry->utm_campaign }}</small>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($enquiry->marketing_consent)
                                    <span class="rw-admin-pill rw-admin-pill--accent">Opt-in</span>
                                @else
                                    <span class="rw-admin-pill rw-admin-pill--muted">No</span>
                                @endif
                            </td>
                            <td class="rw-admin-table__mailchimp">
                                @if ($enquiry->mailchimp_synced_at)
                                    <span class="rw-admin-pill rw-admin-pill--accent" title="{{ $enquiry->mailchimp_synced_at->format('d M Y H:i') }}">Synced</span>
                                @elseif ($enquiry->marketing_consent && $enquiry->mailchimp_sync_error)
                                    <span class="rw-admin-pill rw-admin-pill--urgent" title="{{ $enquiry->mailchimp_sync_error }}">Error</span>
                                @elseif ($enquiry->marketing_consent)
                                    <span class="rw-admin-pill rw-admin-pill--muted">Pending</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($enquiry->client)
                                    <a class="rw-admin-pill rw-admin-pill--accent" href="{{ route('admin.clients.show', $enquiry->client) }}">Client file</a>
                                @else
                                    <span class="rw-admin-pill rw-admin-pill--muted">Lead only</span>
                                @endif
                            </td>
                            <td class="rw-admin-table__enquiry">
                                @if ($enquiry->enquiry && strlen($enquiry->enquiry) > 100)
                                    <details class="rw-admin-enquiry-details">
                                        <summary>
                                            <span class="rw-admin-enquiry-details__preview">{{ \Illuminate\Support\Str::limit($enquiry->enquiry, 100) }}</span>
                                            <span class="rw-admin-enquiry-details__toggle" data-label-more="Show more" data-label-less="Show less"></span>
                                        </summary>
                                        <p class="rw-admin-enquiry-details__full">{{ $enquiry->enquiry }}</p>
                                    </details>
                                @elseif ($enquiry->enquiry)
                                    <span class="rw-admin-enquiry-details__preview" title="{{ $enquiry->enquiry }}">{{ $enquiry->enquiry }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="rw-admin-table__actions">
                                @include('admin.enquiries.partials.actions', ['enquiry' => $enquiry])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="rw-admin-table__empty">No enquiries yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($enquiries->total() > 0)
            <div class="rw-admin-pagination">
                {{ $enquiries->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
@endsection
