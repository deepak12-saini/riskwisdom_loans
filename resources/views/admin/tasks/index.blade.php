@extends('admin.layout')

@section('title', 'Tasks')
@section('page_heading', $pageHeading ?? 'Tasks')

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.index') }}">Client files</a>
@endsection

@section('content')
    <section class="rw-admin-card">
        <div class="rw-admin-filters">
            <form method="get" action="{{ route('admin.tasks.index') }}" class="rw-admin-filters__search">
                @if ($filter !== 'open')
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <label class="rw-admin-filters__search-field">
                    <span class="visually-hidden">Search tasks</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Search task or client…"
                    >
                </label>
                <button class="rw-button rw-button--solid rw-button--sm" type="submit">Search</button>
                @if (($q ?? '') !== '')
                    <a class="rw-admin-link" href="{{ route('admin.tasks.index', array_filter(['filter' => $filter !== 'open' ? $filter : null])) }}">Clear</a>
                @endif
            </form>

            <div class="rw-admin-filter-tabs">
                <a href="{{ route('admin.tasks.index', array_filter(['q' => $q ?: null])) }}" class="@if ($filter === 'open') is-active @endif">
                    Open <em>{{ number_format($stats['open']) }}</em>
                </a>
                <a href="{{ route('admin.tasks.index', array_filter(['filter' => 'overdue', 'q' => $q ?: null])) }}" class="@if ($filter === 'overdue') is-active @endif">
                    Overdue <em>{{ number_format($stats['overdue']) }}</em>
                </a>
                <a href="{{ route('admin.tasks.index', array_filter(['filter' => 'done', 'q' => $q ?: null])) }}" class="@if ($filter === 'done') is-active @endif">
                    Done <em>{{ number_format($stats['done']) }}</em>
                </a>
                <a href="{{ route('admin.tasks.index', array_filter(['filter' => 'all', 'q' => $q ?: null])) }}" class="@if ($filter === 'all') is-active @endif">
                    All <em>{{ number_format($stats['all']) }}</em>
                </a>
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

        @if ($tasks->total() > 0)
            <div class="rw-admin-pagination">
                {{ $tasks->onEachSide(1)->links() }}
            </div>
        @endif
    </section>
@endsection
