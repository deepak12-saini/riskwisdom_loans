@extends('admin.layout')

@section('title', 'Client files')
@section('page_heading', $pageHeading ?? 'Client files')

@section('topbar_actions')
    <a class="rw-button rw-button--solid" href="{{ route('admin.clients.create') }}">New client file</a>
@endsection

@section('content')
    <div class="rw-admin-stats">
        <article class="rw-admin-stat">
            <span>Active files</span>
            <strong>{{ number_format($stats['active']) }}</strong>
        </article>
        <article class="rw-admin-stat rw-admin-stat--accent">
            <span>Open tasks</span>
            <strong>{{ number_format($stats['open_tasks']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>Overdue</span>
            <strong>{{ number_format($stats['overdue_tasks']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>Archived</span>
            <strong>{{ number_format($stats['archived']) }}</strong>
        </article>
    </div>

    <section class="rw-admin-card">
        <div class="rw-admin-card__header">
            <div>
                <h2>{{ $pageHeading ?? 'Client files' }}</h2>
                <p>Track active loan files and outstanding work per client.</p>
            </div>
            <div class="rw-admin-filter-tabs">
                <a href="{{ route('admin.clients.index', ['filter' => 'active']) }}" class="@if ($filter === 'active') is-active @endif">Active</a>
                <a href="{{ route('admin.clients.index', ['filter' => 'archived']) }}" class="@if ($filter === 'archived') is-active @endif">Archived</a>
                <a href="{{ route('admin.clients.index', ['filter' => 'all']) }}" class="@if ($filter === 'all') is-active @endif">All</a>
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

        @if ($clients->hasPages())
            <div class="rw-admin-pagination">
                {{ $clients->links() }}
            </div>
        @endif
    </section>
@endsection
