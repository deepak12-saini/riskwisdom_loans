@extends('admin.layout')

@section('title', 'Tasks')
@section('page_heading', $pageHeading ?? 'Tasks')

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.index') }}">Client files</a>
@endsection

@section('content')
    <div class="rw-admin-stats">
        <article class="rw-admin-stat rw-admin-stat--accent">
            <span>Open</span>
            <strong>{{ number_format($stats['open']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>Overdue</span>
            <strong>{{ number_format($stats['overdue']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>Done</span>
            <strong>{{ number_format($stats['done']) }}</strong>
        </article>
        <article class="rw-admin-stat">
            <span>Total</span>
            <strong>{{ number_format($stats['total']) }}</strong>
        </article>
    </div>

    <section class="rw-admin-card">
        <div class="rw-admin-card__header">
            <div>
                <h2>{{ $pageHeading ?? 'Tasks' }}</h2>
                <p>Outstanding work across all client files.</p>
            </div>
            <div class="rw-admin-filter-tabs">
                <a href="{{ route('admin.tasks.index', ['filter' => 'open']) }}" class="@if ($filter === 'open') is-active @endif">Open</a>
                <a href="{{ route('admin.tasks.index', ['filter' => 'overdue']) }}" class="@if ($filter === 'overdue') is-active @endif">Overdue</a>
                <a href="{{ route('admin.tasks.index', ['filter' => 'done']) }}" class="@if ($filter === 'done') is-active @endif">Done</a>
                <a href="{{ route('admin.tasks.index', ['filter' => 'all']) }}" class="@if ($filter === 'all') is-active @endif">All</a>
            </div>
        </div>

        <div class="rw-admin-table-wrap">
            <table class="rw-admin-table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Client</th>
                        <th>Owner</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr @if ($task->isOverdue()) class="rw-admin-row--overdue" @endif>
                            <td>
                                <strong>{{ $task->title }}</strong>
                                @if ($task->priority === 'high')
                                    <span class="rw-admin-pill rw-admin-pill--urgent">High</span>
                                @endif
                            </td>
                            <td>
                                <a class="rw-admin-link" href="{{ route('admin.clients.show', $task->client) }}">{{ $task->client->full_name }}</a>
                            </td>
                            <td>{{ config('riskwisdom.task_owners')[$task->owner] ?? $task->owner }}</td>
                            <td>
                                @if ($task->due_date)
                                    {{ $task->due_date->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <span class="rw-admin-pill @if ($task->status === 'done') rw-admin-pill--muted @endif">
                                    {{ config('riskwisdom.task_statuses')[$task->status] ?? $task->status }}
                                </span>
                            </td>
                            <td>
                                @if ($task->isOpen())
                                    <form method="post" action="{{ route('admin.clients.tasks.close', [$task->client, $task]) }}" class="rw-admin-inline-form">
                                        @csrf
                                        @method('patch')
                                        <button class="rw-admin-link" type="submit">Close</button>
                                    </form>
                                @endif
                                <a class="rw-admin-link" href="{{ route('admin.clients.show', $task->client) }}">View file</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="rw-admin-table__empty">No tasks match this filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tasks->hasPages())
            <div class="rw-admin-pagination">
                {{ $tasks->links() }}
            </div>
        @endif
    </section>
@endsection
