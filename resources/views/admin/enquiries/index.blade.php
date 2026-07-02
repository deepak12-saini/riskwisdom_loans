@extends('admin.layout')

@section('title', 'Enquiries')
@section('page_heading', $pageHeading ?? 'Website enquiries')

@section('topbar_actions')
    <a class="rw-button rw-button--solid" href="{{ route('admin.enquiries.export') }}">Export CSV</a>
@endsection

@section('content')
    <div class="rw-admin-stats">
        <a
            href="{{ route('admin.enquiries.index') }}"
            class="rw-admin-stat rw-admin-stat-tab @if ($filter === 'all') is-active @endif"
        >
            <span>Total leads</span>
            <strong>{{ number_format($stats['total']) }}</strong>
        </a>
        <a
            href="{{ route('admin.enquiries.index', ['filter' => 'ready_now']) }}"
            class="rw-admin-stat rw-admin-stat-tab @if ($filter === 'ready_now') is-active @endif"
        >
            <span>Ready now</span>
            <strong>{{ number_format($stats['ready_now']) }}</strong>
        </a>
        <a
            href="{{ route('admin.enquiries.index', ['filter' => 'this_week']) }}"
            class="rw-admin-stat rw-admin-stat-tab @if ($filter === 'this_week') is-active @endif"
        >
            <span>This week</span>
            <strong>{{ number_format($stats['this_week']) }}</strong>
        </a>
        <a
            href="{{ route('admin.enquiries.index', ['filter' => 'today']) }}"
            class="rw-admin-stat rw-admin-stat-tab @if ($filter === 'today') is-active @endif"
        >
            <span>Today</span>
            <strong>{{ number_format($stats['today']) }}</strong>
        </a>
        @if ($showPaidAds ?? config('riskwisdom.admin_show_paid_ads', false))
            <a
                href="{{ route('admin.enquiries.index', ['filter' => 'paid']) }}"
                class="rw-admin-stat rw-admin-stat-tab @if ($filter === 'paid') is-active @endif"
            >
                <span>Paid (CPC)</span>
                <strong>{{ number_format($stats['paid'] ?? 0) }}</strong>
            </a>
        @endif
    </div>

    <section class="rw-admin-card">
        <div class="rw-admin-card__header">
            <div>
                <h2>{{ $pageHeading ?? 'All enquiries' }}</h2>
                <p>Leads submitted through the website contact form.</p>
            </div>
        </div>

        <div class="rw-admin-table-wrap">
            <table class="rw-admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Loan type</th>
                        <th>Timeline</th>
                        <th>State</th>
                        <th>Source</th>
                        <th>UTM</th>
                        <th>Marketing</th>
                        <th>Mailchimp</th>
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
                            <td colspan="13" class="rw-admin-table__empty">No enquiries yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($enquiries->hasPages())
            <div class="rw-admin-pagination">
                {{ $enquiries->links() }}
            </div>
        @endif
    </section>
@endsection
