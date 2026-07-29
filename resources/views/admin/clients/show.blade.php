@extends('admin.layout')

@section('title', $client->full_name)
@section('page_heading', $client->full_name)

@section('topbar_actions')
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.index') }}">All clients</a>
    <a class="rw-button rw-button--ghost" href="{{ route('admin.clients.edit', $client) }}">Edit details</a>
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
        $openTasks = $client->tasks->filter(fn ($t) => $t->isOpen());
        $doneTasks = $client->tasks->filter(fn ($t) => $t->status === 'done');
        $openCount = $openTasks->count();
        $overdueCount = $client->tasks->filter(fn ($t) => $t->isOverdue())->count();
        $docCount = $client->documents->count();
        $signedCount = $client->documents->filter(fn ($d) => $d->isSigned())->count();
        $initials = collect(preg_split('/\s+/', trim($client->full_name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
        $statusLabel = config('riskwisdom.client_statuses')[$client->status] ?? $client->status;
        $loanLabel = config('riskwisdom.loan_types')[$client->loan_type] ?? $client->loan_type ?: '—';
    @endphp

    <div class="rw-client-hub" data-client-hub>
        <aside class="rw-client-hub__profile">
            <div class="rw-client-profile">
                <div class="rw-client-profile__identity">
                    <div class="rw-client-profile__avatar" aria-hidden="true">{{ $initials ?: 'CL' }}</div>
                    <div>
                        <p class="rw-client-profile__eyebrow">Client file</p>
                        <h2 class="rw-client-profile__name">{{ $client->full_name }}</h2>
                        <div class="rw-client-profile__badges">
                            <span class="rw-admin-pill @if ($client->isArchived()) rw-admin-pill--muted @endif">{{ $statusLabel }}</span>
                            @if ($client->assignedUser)
                                <span class="rw-admin-pill">{{ $client->assignedUser->username }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rw-client-profile__actions">
                    <a class="rw-client-profile__action" href="mailto:{{ $client->email }}">
                        <span>Email</span>
                        <strong>{{ $client->email }}</strong>
                    </a>
                    @if ($client->phone)
                        <a class="rw-client-profile__action" href="tel:{{ $client->phone }}">
                            <span>Phone</span>
                            <strong>{{ $client->phone }}</strong>
                        </a>
                    @endif
                </div>

                <dl class="rw-client-profile__meta">
                    <div>
                        <dt>Loan type</dt>
                        <dd>{{ $loanLabel }}</dd>
                    </div>
                    <div>
                        <dt>State</dt>
                        <dd>{{ $client->state ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt>Opened</dt>
                        <dd>{{ $client->created_at?->format('d M Y') }}</dd>
                    </div>
                </dl>

                <div class="rw-client-profile__stats" aria-label="File snapshot">
                    <div class="rw-client-stat @if ($overdueCount > 0) is-urgent @endif">
                        <strong>{{ $openCount }}</strong>
                        <span>Open tasks</span>
                    </div>
                    <div class="rw-client-stat @if ($overdueCount > 0) is-urgent @endif">
                        <strong>{{ $overdueCount }}</strong>
                        <span>Overdue</span>
                    </div>
                    <div class="rw-client-stat">
                        <strong>{{ $signedCount }}/{{ $docCount }}</strong>
                        <span>Signed docs</span>
                    </div>
                </div>

                @if ($client->notes)
                    <div class="rw-client-profile__block">
                        <h3>Internal notes</h3>
                        <p>{{ $client->notes }}</p>
                    </div>
                @endif

                @if ($client->enquiry)
                    <div class="rw-client-profile__block">
                        <h3>Source lead</h3>
                        <p>
                            <a class="rw-admin-link" href="{{ route('admin.enquiries.show', $client->enquiry) }}">
                                {{ config('riskwisdom.lead_types')[$client->enquiry->lead_type] ?? $client->enquiry->lead_type }}
                                · {{ $client->enquiry->created_at?->format('d M Y') }}
                            </a>
                        </p>
                        @if ($client->enquiry->enquiry)
                            <p class="rw-client-profile__quote">{{ $client->enquiry->enquiry }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </aside>

        <div class="rw-client-hub__workspace">
            <nav class="rw-client-tabs" role="tablist" aria-label="Client workspace">
                <button
                    class="rw-client-tabs__btn is-active"
                    type="button"
                    role="tab"
                    id="tab-tasks"
                    aria-selected="true"
                    aria-controls="panel-tasks"
                    data-client-tab="tasks"
                >
                    <span>Checklist</span>
                    @if ($openCount > 0)
                        <em>{{ $openCount }}</em>
                    @endif
                </button>
                <button
                    class="rw-client-tabs__btn"
                    type="button"
                    role="tab"
                    id="tab-docs"
                    aria-selected="false"
                    aria-controls="panel-docs"
                    data-client-tab="docs"
                >
                    <span>E-sign</span>
                    @if ($docCount > 0)
                        <em>{{ $docCount }}</em>
                    @endif
                </button>
            </nav>

            <section
                class="rw-client-panel is-active"
                id="panel-tasks"
                role="tabpanel"
                aria-labelledby="tab-tasks"
                data-client-panel="tasks"
            >
                <div class="rw-client-panel__intro">
                    <div>
                        <h2>What still needs doing</h2>
                        <p>Track follow-ups the client completes offline — ID, payslips, bank statements, and similar checklist items. This is not a file upload area.</p>
                    </div>
                    <div class="rw-client-panel__intro-actions">
                        <a class="rw-admin-link" href="{{ route('admin.tasks.index') }}">All tasks</a>
                        <button class="rw-button rw-button--solid rw-button--sm" type="button" data-open-panel="add-task">
                            Add task
                        </button>
                    </div>
                </div>

                <div class="rw-client-filters" data-task-filters>
                    <button class="rw-client-filters__chip is-active" type="button" data-task-filter="open">
                        Open <span>{{ $openCount }}</span>
                    </button>
                    <button class="rw-client-filters__chip" type="button" data-task-filter="done">
                        Done <span>{{ $doneTasks->count() }}</span>
                    </button>
                    <button class="rw-client-filters__chip" type="button" data-task-filter="all">
                        All <span>{{ $client->tasks->count() }}</span>
                    </button>
                </div>

                <div class="rw-client-composer" id="add-task" hidden>
                    <div class="rw-client-composer__head">
                        <div>
                            <h3>Add checklist item</h3>
                            <p>Example: “Provide photo ID” or “Send last 3 payslips”.</p>
                        </div>
                        <button class="rw-admin-drawer__close" type="button" data-close-panel="add-task" aria-label="Close add task">×</button>
                    </div>
                    <form method="post" action="{{ route('admin.clients.tasks.store', $client) }}" class="rw-admin-form rw-admin-form--tasks">
                        @csrf
                        <div class="rw-admin-form-grid rw-admin-form-grid--tasks">
                            <label class="rw-admin-form-full">
                                <span>Task title</span>
                                <input type="text" name="title" placeholder="e.g. Provide photo ID" required>
                            </label>
                            <label class="rw-admin-form-full">
                                <span>Description <small>(optional)</small></span>
                                <textarea name="description" rows="2" placeholder="Any extra instructions"></textarea>
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
                            <button class="rw-button rw-button--solid" type="submit">Save task</button>
                            <button class="rw-button rw-button--ghost" type="button" data-close-panel="add-task">Cancel</button>
                        </div>
                    </form>
                </div>

                @if ($client->tasks->isNotEmpty())
                    <div class="rw-admin-task-list rw-admin-task-list--hub" data-task-list>
                        @foreach ($client->tasks as $task)
                            <article
                                class="rw-admin-task-item @if ($task->status === 'done') is-done @endif @if ($task->isOverdue()) is-overdue @endif"
                                id="task-{{ $task->id }}"
                                data-task-state="{{ $task->status === 'done' ? 'done' : 'open' }}"
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
                    <p class="rw-admin-task-list__empty" data-task-empty hidden>No tasks in this view.</p>
                @else
                    <div class="rw-client-empty">
                        <strong>No checklist items yet</strong>
                        <p>Add the first task so nothing falls through before settlement.</p>
                        <button class="rw-button rw-button--solid rw-button--sm" type="button" data-open-panel="add-task">Add first task</button>
                    </div>
                @endif
            </section>

            <section
                class="rw-client-panel"
                id="panel-docs"
                role="tabpanel"
                aria-labelledby="tab-docs"
                data-client-panel="docs"
                hidden
            >
                <div class="rw-client-panel__intro">
                    <div>
                        <h2>Send PDFs for signature</h2>
                        <p>Upload a PDF, choose the signer, and send via {{ $signingProviderLabel }}. Signed copies stay on this file.</p>
                    </div>
                    <div class="rw-client-panel__intro-actions">
                        @if ($signingConfigured)
                            <span class="rw-admin-pill rw-admin-pill--accent">{{ $signingProviderLabel }} ready</span>
                        @else
                            <span class="rw-admin-pill rw-admin-pill--muted">Setup needed</span>
                        @endif
                        <button class="rw-button rw-button--solid rw-button--sm" type="button" data-open-panel="add-doc">
                            Send document
                        </button>
                    </div>
                </div>

                @if (! $signingConfigured)
                    <div class="rw-admin-docusign-notice">
                        <strong>{{ $signingProviderLabel }} is not connected</strong>
                        <p>Ask the account owner to add API keys in the server environment. You can still save PDFs as drafts until sending is enabled.</p>
                    </div>
                @endif

                <div class="rw-client-composer" id="add-doc" hidden>
                    <div class="rw-client-composer__head">
                        <div>
                            <h3>Send document for signature</h3>
                            <p>Client receives an email link to sign. You can sync status after sending.</p>
                        </div>
                        <button class="rw-admin-drawer__close" type="button" data-close-panel="add-doc" aria-label="Close send document">×</button>
                    </div>
                    <form
                        method="post"
                        action="{{ route('admin.clients.documents.store', $client) }}"
                        enctype="multipart/form-data"
                        class="rw-admin-form rw-admin-form--tasks"
                        data-submit-loader-form
                    >
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
                            @if ($openTasks->isNotEmpty())
                                <label class="rw-admin-form-full">
                                    <span>Link to open task <small>(optional — auto-closes when signed)</small></span>
                                    <select name="task_id">
                                        <option value="">— None —</option>
                                        @foreach ($openTasks as $task)
                                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            @endif
                        </div>
                        <div class="rw-admin-form-actions">
                            <button
                                class="rw-button rw-button--solid"
                                type="submit"
                                data-submit-loader-button
                                data-loading-text="{{ $signingConfigured ? 'Sending...' : 'Saving...' }}"
                            >
                                @if ($signingConfigured)
                                    Send via {{ $signingProviderLabel }}
                                @else
                                    Save draft
                                @endif
                            </button>
                            <button class="rw-button rw-button--ghost" type="button" data-close-panel="add-doc">Cancel</button>
                        </div>
                    </form>
                </div>

                @if ($client->documents->isNotEmpty())
                    <div class="rw-admin-doc-list rw-admin-doc-list--hub">
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
                    <div class="rw-client-empty">
                        <strong>No e-sign documents yet</strong>
                        <p>When you need a signed consent or disclosure, send it from here.</p>
                        <button class="rw-button rw-button--solid rw-button--sm" type="button" data-open-panel="add-doc">Send first document</button>
                    </div>
                @endif
            </section>
        </div>
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
        (() => {
            const hub = document.querySelector('[data-client-hub]');
            if (!hub) return;

            const setTab = (name) => {
                hub.querySelectorAll('[data-client-tab]').forEach((tab) => {
                    const active = tab.dataset.clientTab === name;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });

                hub.querySelectorAll('[data-client-panel]').forEach((panel) => {
                    const active = panel.dataset.clientPanel === name;
                    panel.classList.toggle('is-active', active);
                    panel.hidden = !active;
                });
            };

            hub.querySelectorAll('[data-client-tab]').forEach((tab) => {
                tab.addEventListener('click', () => setTab(tab.dataset.clientTab));
            });

            hub.querySelectorAll('[data-open-panel]').forEach((button) => {
                button.addEventListener('click', () => {
                    const panel = document.getElementById(button.dataset.openPanel);
                    if (!panel) return;
                    panel.hidden = false;
                    panel.querySelector('input, select, textarea')?.focus();
                });
            });

            hub.querySelectorAll('[data-close-panel]').forEach((button) => {
                button.addEventListener('click', () => {
                    const panel = document.getElementById(button.dataset.closePanel);
                    if (panel) panel.hidden = true;
                });
            });

            const filterTasks = (state) => {
                const items = hub.querySelectorAll('[data-task-state]');
                let visible = 0;

                items.forEach((item) => {
                    const show = state === 'all' || item.dataset.taskState === state;
                    item.hidden = !show;
                    if (show) visible += 1;
                });

                hub.querySelectorAll('[data-task-filter]').forEach((chip) => {
                    chip.classList.toggle('is-active', chip.dataset.taskFilter === state);
                });

                const empty = hub.querySelector('[data-task-empty]');
                if (empty) empty.hidden = visible > 0 || items.length === 0;
            };

            hub.querySelectorAll('[data-task-filter]').forEach((chip) => {
                chip.addEventListener('click', () => filterTasks(chip.dataset.taskFilter));
            });

            filterTasks('open');

            const hash = window.location.hash.replace('#', '');
            if (hash === 'docs' || hash.startsWith('doc')) {
                setTab('docs');
            } else if (hash.startsWith('task-')) {
                setTab('tasks');
                filterTasks('all');
                document.getElementById(hash)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })();

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

        document.querySelectorAll('[data-submit-loader-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-submit-loader-button]');

                if (! button || button.disabled) {
                    return;
                }

                button.dataset.originalText = button.innerHTML.trim();
                button.textContent = button.dataset.loadingText || 'Submitting...';
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');
            });
        });
    </script>
@endpush
