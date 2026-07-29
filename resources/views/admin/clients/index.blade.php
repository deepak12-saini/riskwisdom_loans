@extends('admin.layout')

@section('title', 'Client files')
@section('page_heading', $pageHeading ?? 'Client files')

@section('topbar_actions')
    <a class="rw-button rw-button--solid" href="{{ route('admin.clients.create') }}">New client file</a>
@endsection

@section('content')
    <section class="rw-admin-card">
        <div class="rw-admin-filters">
            <form method="get" action="{{ route('admin.clients.index') }}" class="rw-admin-filters__search">
                @if ($filter !== 'active')
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <label class="rw-admin-filters__search-field">
                    <span class="visually-hidden">Search clients</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Search name, email, phone…"
                    >
                </label>
                <button class="rw-button rw-button--solid rw-button--sm" type="submit">Search</button>
                @if (($q ?? '') !== '')
                    <a class="rw-admin-link" href="{{ route('admin.clients.index', array_filter(['filter' => $filter !== 'active' ? $filter : null])) }}">Clear</a>
                @endif
            </form>

            <div class="rw-admin-filter-tabs">
                <a href="{{ route('admin.clients.index', array_filter(['q' => $q ?: null])) }}" class="@if ($filter === 'active') is-active @endif">
                    Active <em>{{ number_format($stats['active']) }}</em>
                </a>
                <a href="{{ route('admin.clients.index', array_filter(['filter' => 'archived', 'q' => $q ?: null])) }}" class="@if ($filter === 'archived') is-active @endif">
                    Archived <em>{{ number_format($stats['archived']) }}</em>
                </a>
                <a href="{{ route('admin.clients.index', array_filter(['filter' => 'all', 'q' => $q ?: null])) }}" class="@if ($filter === 'all') is-active @endif">
                    All <em>{{ number_format($stats['all']) }}</em>
                </a>
            </div>
        </div>

        <div class="rw-admin-table-wrap">
            <table class="rw-admin-table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Loan type</th>
                        <th>State</th>
                        <th>Open tasks</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>
                                <strong>{{ $client->full_name }}</strong>
                                <br><small>Created {{ $client->created_at?->format('d M Y') }}</small>
                                @if ($client->enquiry)
                                    <br>
                                    <a class="rw-admin-link" href="{{ route('admin.enquiries.show', $client->enquiry) }}">From lead</a>
                                @else
                                    <br><small class="rw-admin-muted">Manual file</small>
                                @endif
                            </td>
                            <td class="rw-admin-table__contact">
                                <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                                @if ($client->phone)
                                    <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                                @endif
                            </td>
                            <td>{{ config('riskwisdom.loan_types')[$client->loan_type] ?? $client->loan_type ?: '—' }}</td>
                            <td>{{ $client->state ?: '—' }}</td>
                            <td>
                                @if ($client->open_tasks_count > 0)
                                    <span class="rw-admin-pill">{{ $client->open_tasks_count }} open</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="rw-admin-pill @if ($client->status === 'archived') rw-admin-pill--muted @endif">
                                    {{ config('riskwisdom.client_statuses')[$client->status] ?? $client->status }}
                                </span>
                            </td>
                            <td>
                                <a class="rw-admin-link" href="{{ route('admin.clients.show', $client) }}">View file</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="rw-admin-table__empty">No client files yet. Convert a lead or create one manually.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($clients->total() > 0)
            <div class="rw-admin-pagination">
                {{ $clients->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
@endsection
