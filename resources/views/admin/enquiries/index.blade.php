@extends('admin.layout')

@section('title', 'Enquiries')
@section('page_heading', $pageHeading ?? 'Website enquiries')

@section('topbar_actions')
    <a class="rw-button rw-button--solid" href="{{ route('admin.enquiries.export') }}">Export CSV</a>
@endsection

@section('content')
    <div class="rw-admin-stats">
        <article class="rw-admin-stat">
            <span>Total leads</span>
            <strong>{{ number_format($stats['total']) }}</strong>
        </article>
        <article class="rw-admin-stat rw-admin-stat--accent">
            <span>Ready now</span>
            <strong>{{ number_format($stats['ready_now']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>This week</span>
            <strong>{{ number_format($stats['this_week']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>Today</span>
            <strong>{{ number_format($stats['today']) }}</strong>
        </article>
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
                        <th>Enquiry</th>
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
                            <td class="rw-admin-table__enquiry">{{ $enquiry->enquiry }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="rw-admin-table__empty">No enquiries yet.</td>
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
