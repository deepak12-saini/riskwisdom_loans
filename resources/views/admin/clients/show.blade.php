@extends('admin.layout')

@section('title', $client->full_name)
@section('page_heading', $client->full_name)

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.index') }}">All clients</a>
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.edit', $client) }}">Edit file</a>
    @if ($client->isArchived())
        <form method="post" action="{{ route('admin.clients.restore', $client) }}" class="rw-admin-inline-form">
            @csrf
            @method('patch')
            <button class="rw-button rw-button--solid" type="submit">Restore active</button>
        </form>
    @else
        <form method="post" action="{{ route('admin.clients.archive', $client) }}" class="rw-admin-inline-form" onsubmit="return confirm('Archive this client file?');">
            @csrf
            @method('patch')
            <button class="rw-button rw-button--ghost" type="submit">Archive</button>
        </form>
    @endif
@endsection

@section('content')
    @php
        $openCount = $client->tasks->filter(fn ($t) => $t->isOpen())->count();
        $overdueCount = $client->tasks->filter(fn ($t) => $t->isOverdue())->count();
    @endphp

    <div class="rw-admin-client-show">
        <section class="rw-admin-card rw-admin-client-summary">
            <div class="rw-admin-client-summary__head">
                <div>
                    <h2>{{ $client->full_name }}</h2>
                    <div class="rw-admin-client-summary__badges">
                        <span class="rw-admin-pill @if ($client->isArchived()) rw-admin-pill--muted @endif">
                            {{ config('riskwisdom.client_statuses')[$client->status] ?? $client->status }}
                        </span>
                        @if ($client->assignedUser)
                            <span class="rw-admin-pill">Broker: {{ $client->assignedUser->username }}</span>
                        @endif
                        @if ($openCount > 0)
                            <span class="rw-admin-pill rw-admin-pill--accent">{{ $openCount }} open task{{ $openCount === 1 ? '' : 's' }}</span>
                        @endif
                        @if ($overdueCount > 0)
                            <span class="rw-admin-pill rw-admin-pill--urgent">{{ $overdueCount }} overdue</span>
                        @endif
                    </div>
                </div>
                <div class="rw-admin-client-summary__contact">
                    <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                    @if ($client->phone)
                        <a href="tel:{{ $client->phone }}">{{ $client->phone }}</a>
                    @endif
                </div>
            </div>

            <dl class="rw-admin-client-summary__meta">
                <div>
                    <dt>Loan type</dt>
                    <dd>{{ config('riskwisdom.loan_types')[$client->loan_type] ?? $client->loan_type ?: '—' }}</dd>
                </div>
                <div>
                    <dt>State</dt>
                    <dd>{{ $client->state ?: '—' }}</dd>
                </div>
                <div>
                    <dt>File created</dt>
                    <dd>{{ $client->created_at?->format('d M Y') }}</dd>
                </div>
            </dl>

            @if ($client->notes)
                <div class="rw-admin-client-summary__notes">
                    <strong>Notes</strong>
                    <p>{{ $client->notes }}</p>
                </div>
            @endif

            @if ($client->enquiry)
                <div class="rw-admin-client-summary__source">
                    <strong>Source enquiry</strong>
                    <p>
                        {{ config('riskwisdom.lead_types')[$client->enquiry->lead_type] ?? $client->enquiry->lead_type }}
                        · {{ $client->enquiry->created_at?->format('d M Y') }}
                        @if ($client->enquiry->enquiry)
                            — {{ $client->enquiry->enquiry }}
                        @endif
                    </p>
                </div>
            @endif
        </section>

        <section class="rw-admin-card rw-admin-card--tasks">
            <div class="rw-admin-card__header">
                <div>
                    <h2>Outstanding tasks</h2>
                    <p>Checklist items to track — client completes outside the system (email, phone, etc.).</p>
                </div>
                <a class="rw-admin-link" href="{{ route('admin.tasks.index') }}">All tasks</a>
            </div>

            @if ($client->tasks->isNotEmpty())
                <div class="rw-admin-task-list">
                    @foreach ($client->tasks as $task)
                        <article
                            class="rw-admin-task-item @if ($task->status === 'done') is-done @endif @if ($task->isOverdue()) is-overdue @endif"
                            id="task-{{ $task->id }}"
                        >
                            <div class="rw-admin-task-item__status" aria-hidden="true">
                                @if ($task->status === 'done')
                                    <span class="rw-admin-task-item__icon rw-admin-task-item__icon--done"></span>
                                @elseif ($task->isOverdue())
                                    <span class="rw-admin-task-item__icon rw-admin-task-item__icon--overdue"></span>
                                @else
                                    <span class="rw-admin-task-item__icon"></span>
                                @endif
                            </div>

                            <div class="rw-admin-task-item__body">
                                <h3>{{ $task->title }}</h3>
                                @if ($task->description)
                                    <p>{{ $task->description }}</p>
                                @endif
                                <div class="rw-admin-task-item__meta">
                                    <span class="rw-admin-pill">{{ config('riskwisdom.task_owners')[$task->owner] ?? $task->owner }}</span>
                                    <span class="rw-admin-pill @if ($task->status === 'done') rw-admin-pill--muted @endif">
                                        {{ config('riskwisdom.task_statuses')[$task->status] ?? $task->status }}
                                    </span>
                                    @if ($task->priority === 'high')
                                        <span class="rw-admin-pill rw-admin-pill--urgent">High priority</span>
                                    @endif
                                    @if ($task->due_date)
                                        <span class="rw-admin-pill @if ($task->isOverdue()) rw-admin-pill--urgent @endif">
                                            Due {{ $task->due_date->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="rw-admin-task-item__actions">
                                @if ($task->isOpen())
                                    <form method="post" action="{{ route('admin.clients.tasks.close', [$client, $task]) }}">
                                        @csrf
                                        @method('patch')
                                        <button class="rw-button rw-button--solid rw-button--sm" type="submit">Mark done</button>
                                    </form>
                                @endif
                                <button
                                    class="rw-button rw-button--ghost rw-button--sm"
                                    type="button"
                                    data-open-drawer="task-drawer-{{ $task->id }}"
                                >
                                    Edit
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="rw-admin-task-list__empty">No tasks yet. Add the first checklist item below.</p>
            @endif

            <div class="rw-admin-task-add">
                <h3>Add checklist item</h3>
                <p class="rw-admin-task-add__hint">Title describes what’s needed — e.g. “Provide photo ID”. This is not a file upload.</p>
                <form method="post" action="{{ route('admin.clients.tasks.store', $client) }}" class="rw-admin-form rw-admin-form--tasks">
                    @csrf
                    <div class="rw-admin-form-grid rw-admin-form-grid--tasks">
                        <label class="rw-admin-form-full">
                            <span>Task title</span>
                            <input type="text" name="title" placeholder="e.g. Provide photo ID" required>
                        </label>
                        <label class="rw-admin-form-full">
                            <span>Description <small>(optional)</small></span>
                            <textarea name="description" rows="2" placeholder="Any extra instructions for admin or client"></textarea>
                        </label>
                        <label>
                            <span>Owner</span>
                            <select name="owner" required>
                                @foreach (config('riskwisdom.task_owners') as $value => $label)
                                    <option value="{{ $value }}" @selected($value === 'client')>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Priority</span>
                            <select name="priority" required>
                                @foreach (config('riskwisdom.task_priorities') as $value => $label)
                                    <option value="{{ $value }}" @selected($value === 'normal')>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Due date</span>
                            <input type="date" name="due_date">
                        </label>
                        <label>
                            <span>Status</span>
                            <select name="status" required>
                                @foreach (config('riskwisdom.task_statuses') as $value => $label)
                                    <option value="{{ $value }}" @selected($value === 'open')>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="rw-admin-form-actions">
                        <button class="rw-button rw-button--solid" type="submit">Add task</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="rw-admin-card rw-admin-card--documents">
            <div class="rw-admin-card__header">
                <div>
                    <h2>E-sign documents</h2>
                    <p>Send PDFs for digital signature via {{ $signingProviderLabel }}. Signed copies are stored on this client file.</p>
                </div>
                @if ($signingConfigured)
                    <span class="rw-admin-pill rw-admin-pill--accent">{{ $signingProviderLabel }} connected</span>
                @else
                    <span class="rw-admin-pill rw-admin-pill--muted">Awaiting API keys</span>
                @endif
            </div>

            @if (! $signingConfigured)
                <div class="rw-admin-docusign-notice">
                    <strong>{{ $signingProviderLabel }} not configured yet</strong>
                    <p>Add <code>{{ strtoupper($signingProviderLabel === 'Annature' ? 'ANNATURE' : 'DOCUSIGN') }}_*</code> keys to <code>.env</code> to send documents. You can still upload PDFs — they will save as drafts until keys are added.</p>
                </div>
            @endif

            @if ($client->documents->isNotEmpty())
                <div class="rw-admin-doc-list">
                    @foreach ($client->documents as $document)
                        <article class="rw-admin-doc-item @if ($document->isSigned()) is-signed @endif">
                            <div class="rw-admin-doc-item__body">
                                <h3>{{ $document->title }}</h3>
                                <p>
                                    {{ config('signing.document_types')[$document->document_type] ?? $document->document_type }}
                                    · {{ $document->signer_name }} &lt;{{ $document->signer_email }}&gt;
                                </p>
                                <div class="rw-admin-task-item__meta">
                                    <span class="rw-admin-pill @if ($document->status === 'signed') rw-admin-pill--accent @elseif ($document->status === 'error') rw-admin-pill--urgent @else rw-admin-pill--muted @endif">
                                        {{ config('signing.statuses')[$document->status] ?? $document->status }}
                                    </span>
                                    @if ($document->sent_at)
                                        <span class="rw-admin-pill">Sent {{ $document->sent_at->format('d M Y') }}</span>
                                    @endif
                                    @if ($document->signed_at)
                                        <span class="rw-admin-pill rw-admin-pill--accent">Signed {{ $document->signed_at->format('d M Y') }}</span>
                                    @endif
                                </div>
                                @if ($document->error_message)
                                    <p class="rw-admin-doc-item__error">{{ $document->error_message }}</p>
                                @endif
                            </div>
                            <div class="rw-admin-doc-item__actions">
                                @if ($document->isSigned())
                                    <a class="rw-button rw-button--solid rw-button--sm" href="{{ route('admin.clients.documents.download', [$client, $document]) }}">Download PDF</a>
                                @elseif ($document->envelope_id && $signingConfigured)
                                    <form method="post" action="{{ route('admin.clients.documents.sync', [$client, $document]) }}">
                                        @csrf
                                        <button class="rw-button rw-button--ghost rw-button--sm" type="submit">Sync status</button>
                                    </form>
                                @endif
                                <form method="post" action="{{ route('admin.clients.documents.destroy', [$client, $document]) }}" onsubmit="return confirm('Remove this document record?');">
                                    @csrf
                                    @method('delete')
                                    <button class="rw-admin-link rw-admin-link--danger" type="submit">Remove</button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <p class="rw-admin-doc-list__empty">No documents sent yet.</p>
            @endif

            <div class="rw-admin-task-add rw-admin-task-add--documents">
                <h3>Send document for signature</h3>
                <form method="post" action="{{ route('admin.clients.documents.store', $client) }}" enctype="multipart/form-data" class="rw-admin-form rw-admin-form--tasks">
                    @csrf
                    <div class="rw-admin-form-grid rw-admin-form-grid--tasks">
                        <label>
                            <span>Document type</span>
                            <select name="document_type" required>
                                @foreach (config('signing.document_types') as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Document title</span>
                            <input type="text" name="title" placeholder="e.g. Privacy consent form" required>
                        </label>
                        <label>
                            <span>Signer name</span>
                            <input type="text" name="signer_name" value="{{ $client->full_name }}" required>
                        </label>
                        <label>
                            <span>Signer email</span>
                            <input type="email" name="signer_email" value="{{ $client->email }}" required>
                        </label>
                        <label class="rw-admin-form-full">
                            <span>PDF file</span>
                            <input type="file" name="pdf" accept="application/pdf" required>
                        </label>
                        @if ($client->tasks->isNotEmpty())
                            <label class="rw-admin-form-full">
                                <span>Link to task <small>(optional — auto-closes when signed)</small></span>
                                <select name="task_id">
                                    <option value="">— None —</option>
                                    @foreach ($client->tasks->filter(fn ($t) => $t->isOpen()) as $task)
                                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                                    @endforeach
                                </select>
                            </label>
                        @endif
                    </div>
                    <div class="rw-admin-form-actions">
                        <button class="rw-button rw-button--solid" type="submit">
                            @if ($signingConfigured)
                                Send via {{ $signingProviderLabel }}
                            @else
                                Save draft
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @foreach ($client->tasks as $task)
        <dialog class="rw-admin-drawer" id="task-drawer-{{ $task->id }}" aria-labelledby="task-drawer-title-{{ $task->id }}">
            <div class="rw-admin-drawer__panel">
                <header class="rw-admin-drawer__header">
                    <div>
                        <p class="rw-admin-drawer__eyebrow">Edit task</p>
                        <h2 id="task-drawer-title-{{ $task->id }}">{{ $task->title }}</h2>
                    </div>
                    <button class="rw-admin-drawer__close" type="button" data-close-drawer aria-label="Close">×</button>
                </header>

                <form method="post" action="{{ route('admin.clients.tasks.update', [$client, $task]) }}" class="rw-admin-form rw-admin-drawer__form">
                    @csrf
                    @method('put')

                    <label>
                        <span>Title</span>
                        <input type="text" name="title" value="{{ $task->title }}" required>
                    </label>

                    <label>
                        <span>Description</span>
                        <textarea name="description" rows="3">{{ $task->description }}</textarea>
                    </label>

                    <div class="rw-admin-form-grid">
                        <label>
                            <span>Owner</span>
                            <select name="owner">
                                @foreach (config('riskwisdom.task_owners') as $value => $label)
                                    <option value="{{ $value }}" @selected($task->owner === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Status</span>
                            <select name="status">
                                @foreach (config('riskwisdom.task_statuses') as $value => $label)
                                    <option value="{{ $value }}" @selected($task->status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Priority</span>
                            <select name="priority">
                                @foreach (config('riskwisdom.task_priorities') as $value => $label)
                                    <option value="{{ $value }}" @selected($task->priority === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>
                            <span>Due date</span>
                            <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}">
                        </label>
                    </div>

                    <label>
                        <span>Internal notes</span>
                        <textarea name="notes" rows="2">{{ $task->notes }}</textarea>
                    </label>

                    <div class="rw-admin-drawer__footer">
                        <button class="rw-button rw-button--solid" type="submit">Save changes</button>
                        <button class="rw-button rw-button--ghost" type="button" data-close-drawer>Cancel</button>
                    </div>
                </form>

                <form
                    method="post"
                    action="{{ route('admin.clients.tasks.destroy', [$client, $task]) }}"
                    class="rw-admin-drawer__delete"
                    onsubmit="return confirm('Delete this task permanently?');"
                >
                    @csrf
                    @method('delete')
                    <button class="rw-admin-link rw-admin-link--danger" type="submit">Delete task</button>
                </form>
            </div>
        </dialog>
    @endforeach
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-open-drawer]').forEach((button) => {
            button.addEventListener('click', () => {
                const dialog = document.getElementById(button.dataset.openDrawer);
                dialog?.showModal();
            });
        });

        document.querySelectorAll('[data-close-drawer]').forEach((button) => {
            button.addEventListener('click', () => {
                button.closest('dialog')?.close();
            });
        });

        document.querySelectorAll('.rw-admin-drawer').forEach((dialog) => {
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) {
                    dialog.close();
                }
            });
        });
    </script>
@endpush
